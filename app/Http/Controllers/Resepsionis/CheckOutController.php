<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\TransaksiAsrama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckOutController extends Controller
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

        $penghunis = $query->latest('tanggal_masuk')->paginate(15)->withQueryString();

        // Ambil data gedung beserta kamar untuk pilihan perpindahan kamar
        $gedungs = Gedung::with(['kamars' => function ($q) {
            $q->withCount(['activeTransaksi as terisi_count'])
              ->orderBy('nomor_kamar');
        }])->orderBy('nama_gedung')->get();

        return view('resepsionis.checkout.index', compact('penghunis', 'gedungs'));
    }

    public function process(Request $request, TransaksiAsrama $transaksi)
    {
        if ($transaksi->status !== 'menginap') {
            return back()->with('error', 'Transaksi ini sudah selesai atau tidak aktif.');
        }

        $namaPeserta = $transaksi->peserta->nama_peserta ?? 'Peserta';
        $kamar = $transaksi->kamar;
        $nomorKamar = $kamar->nomor_kamar ?? '-';
        $remainingOccupants = 0;

        DB::transaction(function () use ($transaksi, $kamar, &$remainingOccupants) {
            // Update transaksi menginap menjadi selesai
            $transaksi->update([
                'tanggal_keluar' => now(),
                'status' => 'selesai',
            ]);

            // Perbarui status kamar berdasarkan sisa penghuni aktif
            if ($kamar) {
                $remainingOccupants = $kamar->transaksi()
                    ->where('status', 'menginap')
                    ->where('id', '!=', $transaksi->id)
                    ->count();

                $kamar->update([
                    'status' => ($remainingOccupants > 0) ? 'terisi' : 'kosong'
                ]);
            }
        });

        $infoSisa = $remainingOccupants > 0 
            ? "Kamar {$nomorKamar} masih terisi {$remainingOccupants} orang." 
            : "Kamar {$nomorKamar} kini kosong kembali.";

        return redirect()->route('resepsionis.checkout.index')
            ->with('success', "Check-out untuk {$namaPeserta} dari kamar {$nomorKamar} berhasil diproses. {$infoSisa}");
    }

    public function editPindahKamar(TransaksiAsrama $transaksi)
    {
        if ($transaksi->status !== 'menginap') {
            return redirect()->route('resepsionis.checkout.index')
                ->with('error', 'Transaksi peserta ini sudah selesai atau tidak aktif.');
        }

        $transaksi->load(['peserta.diklat', 'kamar.gedung']);

        $gedungs = Gedung::with(['kamars' => function ($q) {
            $q->withCount(['activeTransaksi as terisi_count'])
              ->orderBy('nomor_kamar');
        }])->orderBy('nama_gedung')->get();

        return view('resepsionis.checkout.pindah-kamar', compact('transaksi', 'gedungs'));
    }

    public function pindahKamar(Request $request, TransaksiAsrama $transaksi)
    {
        if ($transaksi->status !== 'menginap') {
            return back()->with('error', 'Transaksi peserta ini sudah selesai atau tidak aktif.');
        }

        $validated = $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
        ], [
            'kamar_id.required' => 'Pilih kamar tujuan perpindahan.',
            'kamar_id.exists' => 'Kamar yang dipilih tidak valid.',
        ]);

        if ((int)$transaksi->kamar_id === (int)$validated['kamar_id']) {
            return back()->with('error', 'Kamar tujuan sama dengan kamar yang sedang ditempati saat ini.');
        }

        $kamarLama = $transaksi->kamar;
        $kamarBaru = Kamar::with('gedung')->findOrFail($validated['kamar_id']);

        if ($kamarBaru->status === 'rusak') {
            return back()->with('error', "Kamar {$kamarBaru->nomor_kamar} sedang berstatus rusak dan tidak dapat ditempati.");
        }

        $activeInNew = $kamarBaru->activeTransaksi()->count();

        if ($activeInNew >= $kamarBaru->kapasitas) {
            return back()->with('error', "Kamar {$kamarBaru->nomor_kamar} sudah penuh (kapasitas {$kamarBaru->kapasitas} orang). Silakan pilih kamar lain.");
        }

        DB::transaction(function () use ($transaksi, $kamarLama, $kamarBaru) {
            // Update kamar pada transaksi peserta
            $transaksi->update([
                'kamar_id' => $kamarBaru->id,
            ]);

            // Update kamar baru menjadi terisi
            $kamarBaru->update(['status' => 'terisi']);

            // Perbarui status kamar lama berdasarkan sisa penghuni
            if ($kamarLama) {
                $remainingInOld = $kamarLama->transaksi()
                    ->where('status', 'menginap')
                    ->where('id', '!=', $transaksi->id)
                    ->count();

                $kamarLama->update([
                    'status' => ($remainingInOld > 0) ? 'terisi' : 'kosong',
                ]);
            }
        });

        $namaPeserta = $transaksi->peserta->nama_peserta ?? 'Peserta';
        $nomorLama = $kamarLama ? $kamarLama->nomor_kamar : '-';
        $nomorBaru = $kamarBaru->nomor_kamar;
        $namaGedungBaru = $kamarBaru->gedung->nama_gedung ?? '';

        return redirect()->route('resepsionis.checkout.index')
            ->with('success', "Peserta {$namaPeserta} berhasil dipindahkan dari Kamar {$nomorLama} ke Kamar {$nomorBaru} ({$namaGedungBaru}).");
    }
}
