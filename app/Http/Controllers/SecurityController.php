<?php

namespace App\Http\Controllers;

use App\Models\Diklat;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $search = $request->get('search');
        $tab = $request->get('tab', 'berjalan'); // 'berjalan', 'mendatang', 'selesai', 'semua'

        // Query Diklat
        $query = Diklat::withCount('pesertas')
            ->with(['pesertas' => function ($q) {
                $q->with(['transaksi' => function ($t) {
                    $t->latest('tanggal_masuk')->with('kamar.gedung');
                }]);
            }]);

        // Filter status tab
        if ($tab === 'berjalan') {
            $query->whereDate('tanggal_mulai', '<=', $today)
                  ->whereDate('tanggal_selesai', '>=', $today);
        } elseif ($tab === 'mendatang') {
            $query->whereDate('tanggal_mulai', '>', $today);
        } elseif ($tab === 'selesai') {
            $query->whereDate('tanggal_selesai', '<', $today);
        }

        // Pencarian nama diklat, peserta, NIP, atau instansi
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_diklat', 'like', "%{$search}%")
                  ->orWhereHas('pesertas', function ($sub) use ($search) {
                      $sub->where('nama_peserta', 'like', "%{$search}%")
                          ->orWhere('nip_nik', 'like', "%{$search}%")
                          ->orWhere('instansi', 'like', "%{$search}%");
                  });
            });
        }

        $diklats = $query->orderBy('tanggal_mulai', 'desc')->get();

        // Statistik ringkas untuk Pos Keamanan
        $diklatBerjalanCount = Diklat::whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->count();

        $pesertaBerjalanCount = Peserta::whereHas('diklat', function ($q) use ($today) {
            $q->whereDate('tanggal_mulai', '<=', $today)
              ->whereDate('tanggal_selesai', '>=', $today);
        })->count();

        $penghuniMenginapCount = TransaksiAsrama::where('status', 'menginap')->count();

        // Hitung juga jumlah peserta per diklat yang sedang menginap di asrama
        foreach ($diklats as $diklat) {
            $diklat->pesertas_menginap_count = $diklat->pesertas->filter(function ($p) {
                return $p->transaksi->contains(fn ($t) => $t->status === 'menginap');
            })->count();
        }

        return view('security.index', compact(
            'diklats',
            'diklatBerjalanCount',
            'pesertaBerjalanCount',
            'penghuniMenginapCount',
            'today',
            'tab',
            'search'
        ));
    }
}
