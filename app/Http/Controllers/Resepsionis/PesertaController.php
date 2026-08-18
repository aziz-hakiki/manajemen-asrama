<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function create()
    {
        $diklats = Diklat::all();
        return view('resepsionis.peserta.create', compact('diklats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'diklat_id' => 'required|exists:diklats,id',
            'nama_peserta' => 'required|string|max:255',
            'nip_nik' => 'nullable|string|max:50',
            'instansi' => 'nullable|string|max:255',
        ], [
            'diklat_id.required' => 'Pilih kegiatan diklat terlebih dahulu.',
            'nama_peserta.required' => 'Nama peserta wajib diisi.',
        ]);

        $peserta = Peserta::create($validated);

        if ($request->has('checkin_now')) {
            return redirect()->route('resepsionis.checkin.create', ['peserta_id' => $peserta->id])
                ->with('success', 'Peserta berhasil ditambahkan. Silakan lanjutkan pemilihan kamar check-in.');
        }

        return redirect()->route('resepsionis.checkin.create')
            ->with('success', 'Data peserta baru berhasil ditambahkan.');
    }
}
