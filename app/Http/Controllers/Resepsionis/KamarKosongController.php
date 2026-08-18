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

        $query = Kamar::with('gedung')->where('status', 'kosong');

        if ($request->filled('gedung_id')) {
            $query->where('gedung_id', $request->gedung_id);
        }

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->orderBy('nomor_kamar')->paginate(16)->withQueryString();
        $totalKosong = Kamar::where('status', 'kosong')->count();

        return view('resepsionis.kamar-kosong.index', compact('kamars', 'gedungs', 'totalKosong'));
    }
}
