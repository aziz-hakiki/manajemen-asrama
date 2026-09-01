<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarKosongController extends Controller
{
    public function index(Request $request)
    {
        $gedungs = Gedung::all();

        $query = Kamar::with(['gedung', 'activeTransaksi.peserta.diklat'])
            ->withCount(['activeTransaksi as terisi_count']);

        if ($request->filled('gedung_id')) {
            $query->where('gedung_id', $request->gedung_id);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'kosong') {
                $query->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0');
            } elseif ($status === 'sebagian' || $status === '1_2_terisi') {
                $query->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") BETWEEN 1 AND (kamars.kapasitas - 1)');
            } elseif ($status === 'penuh' || $status === '3_terisi') {
                $query->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") >= kamars.kapasitas');
            } elseif ($status === 'tersedia') {
                $query->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") < kamars.kapasitas');
            }
        }

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('nomor_kamar')->paginate(16)->withQueryString();

        // Statistik keseluruhan ketersediaan
        $totalKamar = Kamar::count();
        $totalKosong = Kamar::whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0')->count();
        $totalSebagian = Kamar::whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") BETWEEN 1 AND (kamars.kapasitas - 1)')->count();
        $totalPenuh = Kamar::whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") >= kamars.kapasitas')->count();

        return view('resepsionis.kamar-kosong.index', compact(
            'kamars',
            'gedungs',
            'totalKamar',
            'totalKosong',
            'totalSebagian',
            'totalPenuh'
        ));
    }
}
