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
        // Peserta yang belum check-in (tidak memiliki transaksi berstatus 'menginap')
        $pesertas = Peserta::with('diklat')
            ->whereDoesntHave('transaksi', function ($q) {
                $q->where('status', 'menginap');
            })
            ->orderBy('nama_peserta')
            ->get();

        // Kamar yang masih berstatus kosong
        $gedungs = Gedung::with(['kamars' => function ($q) {
            $q->where('status', 'kosong')->orderBy('nomor_kamar');
        }])->get();

        $selectedPesertaId = $request->query('peserta_id');
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
        $peserta = Peserta::findOrFail($validated['peserta_id']);

        // Pastikan kamar masih kosong
        if ($kamar->status !== 'kosong') {
            return back()->with('error', 'Kamar yang dipilih saat ini sudah terisi. Silakan pilih kamar lain.');
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
