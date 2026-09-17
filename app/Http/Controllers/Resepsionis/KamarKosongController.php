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
        $gedungs = Gedung::orderBy('nama_gedung', 'asc')->get();

        $user = auth()->user();
        $assignedGedungId = ($user && $user->role === 'resepsionis') ? $user->assignedGedungId() : null;

        // Jika resepsionis memiliki gedung penugasan
        if ($request->routeIs('resepsionis.*') && $assignedGedungId) {
            if (!$request->filled('gedung_id')) {
                return redirect()->route('resepsionis.kamar-kosong.index', [
                    'gedung_id' => $assignedGedungId,
                ]);
            }

            // Jika resepsionis mencoba mengakses asrama lain via URL
            if ($request->gedung_id != $assignedGedungId) {
                $assignedGedung = $gedungs->firstWhere('id', $assignedGedungId);
                $namaGedung = $assignedGedung->nama_gedung ?? 'asrama penugasan Anda';
                return redirect()->route('resepsionis.kamar-kosong.index', [
                    'gedung_id' => $assignedGedungId,
                ])->with('warning', "Akses dibatasi. Anda bertugas di {$namaGedung} dan tidak memiliki hak akses untuk membuka kamar di asrama lain.");
            }
        }

        // Jika tidak ada gedung_id atau gedung_id tidak valid, arahkan ke gedung pertama (misal: Asrama A)
        if (!$request->filled('gedung_id') && $gedungs->isNotEmpty()) {
            if ($request->routeIs('admin.*')) {
                $routeName = 'admin.kamar-kosong.index';
            } elseif ($request->routeIs('pimpinan.*')) {
                $routeName = 'pimpinan.kamar-kosong.index';
            } else {
                $routeName = 'resepsionis.kamar-kosong.index';
            }
            return redirect()->route($routeName, [
                'gedung_id' => $gedungs->first()->id,
            ]);
        }

        $selectedGedung = $gedungs->firstWhere('id', $request->gedung_id) ?? $gedungs->first();

        $query = Kamar::with(['gedung', 'activeTransaksi.peserta.diklat'])
            ->withCount(['activeTransaksi as terisi_count']);

        if ($selectedGedung) {
            $query->where('gedung_id', $selectedGedung->id);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'rusak') {
                $query->where('status', 'rusak');
            } elseif ($status === 'kosong') {
                $query->where('status', '!=', 'rusak')
                      ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0');
            } elseif ($status === 'sebagian' || $status === '1_2_terisi') {
                $query->where('status', '!=', 'rusak')
                      ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") BETWEEN 1 AND (kamars.kapasitas - 1)');
            } elseif ($status === 'penuh' || $status === '3_terisi') {
                $query->where('status', '!=', 'rusak')
                      ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") >= kamars.kapasitas');
            } elseif ($status === 'tersedia') {
                $query->where('status', '!=', 'rusak')
                      ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") < kamars.kapasitas');
            }
        }

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('nomor_kamar')->paginate(16)->withQueryString();

        // Statistik ketersediaan untuk gedung yang sedang dipilih
        $statsQuery = Kamar::query();
        if ($selectedGedung) {
            $statsQuery->where('gedung_id', $selectedGedung->id);
        }

        $totalKamar = (clone $statsQuery)->count();
        $totalRusak = (clone $statsQuery)->where('status', 'rusak')->count();
        $totalKosong = (clone $statsQuery)->where('status', '!=', 'rusak')->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0')->count();
        $totalSebagian = (clone $statsQuery)->where('status', '!=', 'rusak')->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") BETWEEN 1 AND (kamars.kapasitas - 1)')->count();
        $totalPenuh = (clone $statsQuery)->where('status', '!=', 'rusak')->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") >= kamars.kapasitas')->count();

        return view('resepsionis.kamar-kosong.index', compact(
            'kamars',
            'gedungs',
            'selectedGedung',
            'totalKamar',
            'totalRusak',
            'totalKosong',
            'totalSebagian',
            'totalPenuh'
        ));
    }
}
