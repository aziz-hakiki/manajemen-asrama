<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use App\Models\Gedung;
use App\Models\TransaksiAsrama;
use Illuminate\Http\Request;

class PenghuniAktifController extends Controller
{
    public function index(Request $request)
    {
        $gedungs = Gedung::all();
        $diklats = Diklat::all();

        $query = TransaksiAsrama::with(['peserta.diklat', 'kamar.gedung'])
            ->where('status', 'menginap');

        if ($request->filled('gedung_id')) {
            $query->whereHas('kamar', function ($q) use ($request) {
                $q->where('gedung_id', $request->gedung_id);
            });
        }

        if ($request->filled('diklat_id')) {
            $query->whereHas('peserta', function ($q) use ($request) {
                $q->where('diklat_id', $request->diklat_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('peserta', function ($q) use ($search) {
                    $q->where('nama_peserta', 'like', "%{$search}%")
                      ->orWhere('nip_nik', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%");
                })->orWhereHas('kamar', function ($q) use ($search) {
                    $q->where('nomor_kamar', 'like', "%{$search}%");
                });
            });
        }

        $penghunis = $query->latest('tanggal_masuk')->paginate(15)->withQueryString();
        $totalPenghuni = TransaksiAsrama::where('status', 'menginap')->count();

        return view('resepsionis.penghuni.index', compact('penghunis', 'gedungs', 'diklats', 'totalPenghuni'));
    }
}
