<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
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

        return view('resepsionis.checkout.index', compact('penghunis'));
    }

    public function process(Request $request, TransaksiAsrama $transaksi)
    {
        if ($transaksi->status !== 'menginap') {
            return back()->with('error', 'Transaksi ini sudah selesai atau tidak aktif.');
        }

        $namaPeserta = $transaksi->peserta->nama_peserta ?? 'Peserta';
        $nomorKamar = $transaksi->kamar->nomor_kamar ?? '';

        DB::transaction(function () use ($transaksi) {
            // Update transaksi menginap menjadi selesai
            $transaksi->update([
                'tanggal_keluar' => now(),
                'status' => 'selesai',
            ]);

            // Kembalikan status kamar menjadi kosong
            if ($transaksi->kamar) {
                $transaksi->kamar->update(['status' => 'kosong']);
            }
        });

        return redirect()->route('resepsionis.checkout.index')
            ->with('success', "Check-out untuk {$namaPeserta} dari kamar {$nomorKamar} berhasil diproses. Kamar kini telah kosong kembali.");
    }
}
