<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiAsrama::with(['peserta.diklat', 'kamar.gedung'])
            ->where('status', 'menginap');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('peserta', function ($q) use ($search) {
                $q->where('nama_peserta', 'like', "%{$search}%")
                  ->orWhere('nip_nik', 'like', "%{$search}%");
            })->orWhereHas('kamar', function ($q) use ($search) {
                $q->where('nomor_kamar', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->latest('tanggal_masuk')->paginate(15)->withQueryString();

        return view('resepsionis.checkin.index', compact('transaksis'));
    }

    public function create(Request $request)
    {
        $today = now()->toDateString();

        // Peserta yang belum check-in (tidak memiliki transaksi berstatus 'menginap')
        // dan jadwal kegiatan diklat belum selesai (tanggal_selesai >= hari ini)
        $pesertas = Peserta::with('diklat')
            ->whereDoesntHave('transaksi', function ($q) {
                $q->where('status', 'menginap');
            })
            ->whereHas('diklat', function ($q) use ($today) {
                $q->whereDate('tanggal_selesai', '>=', $today);
            })
            ->orderBy('nama_peserta')
            ->get();

        // Kamar yang masih memiliki kapasitas kosong (terisi_count < kapasitas) dan tidak rusak
        $gedungs = Gedung::with(['kamars' => function ($q) {
            $q->where('status', '!=', 'rusak')
              ->withCount(['activeTransaksi as terisi_count'])
              ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") < kamars.kapasitas')
              ->orderBy('nomor_kamar');
        }])->get();

        $selectedPesertaId = $request->query('peserta_id');
        if ($selectedPesertaId && ! $pesertas->contains('id', (int) $selectedPesertaId)) {
            $selectedPesertaId = null;
        }

        $selectedKamarId = $request->query('kamar_id');

        return view('resepsionis.checkin.create', compact('pesertas', 'gedungs', 'selectedPesertaId', 'selectedKamarId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'kamar_id' => 'required|exists:kamars,id',
            'tanggal_masuk' => 'required|date',
        ], [
            'peserta_id.required' => 'Pilih peserta yang akan melakukan check-in.',
            'kamar_id.required' => 'Pilih kamar yang akan ditempati.',
            'tanggal_masuk.required' => 'Tanggal & waktu check-in wajib ditentukan.',
        ]);

        $kamar = Kamar::findOrFail($validated['kamar_id']);
        $peserta = Peserta::with('diklat')->findOrFail($validated['peserta_id']);

        if ($kamar->status === 'rusak') {
            return back()->with('error', "Kamar {$kamar->nomor_kamar} sedang berstatus rusak dan tidak dapat digunakan.");
        }

        // Pastikan jadwal kegiatan diklat belum selesai
        if ($peserta->diklat && $peserta->diklat->tanggal_selesai < now()->toDateString()) {
            $tanggalSelesaiFormatted = \Carbon\Carbon::parse($peserta->diklat->tanggal_selesai)->translatedFormat('d F Y');
            return back()->with('error', "Kegiatan diklat {$peserta->diklat->nama_diklat} telah selesai pada {$tanggalSelesaiFormatted}. Peserta tidak dapat melakukan check-in.")->withInput();
        }

        $activeOccupants = $kamar->activeTransaksi()->count();

        // Pastikan kamar belum penuh
        if ($activeOccupants >= $kamar->kapasitas) {
            return back()->with('error', "Kamar {$kamar->nomor_kamar} sudah penuh (kapasitas maksimal {$kamar->kapasitas} orang). Silakan pilih kamar lain.");
        }

        // Pastikan peserta belum aktif menginap
        if ($peserta->transaksi()->where('status', 'menginap')->exists()) {
            return back()->with('error', 'Peserta tersebut sudah tercatat aktif menginap di kamar lain.');
        }

        DB::transaction(function () use ($validated, $kamar) {
            // Buat transaksi menginap
            TransaksiAsrama::create([
                'peserta_id' => $validated['peserta_id'],
                'kamar_id' => $validated['kamar_id'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'status' => 'menginap',
            ]);

            // Update status kamar jadi terisi
            $kamar->update(['status' => 'terisi']);
        });

        return redirect()->route('resepsionis.penghuni.index')
            ->with('success', "Proses Check-in untuk peserta {$peserta->nama_peserta} di kamar {$kamar->nomor_kamar} berhasil disimpan.");
    }
}
