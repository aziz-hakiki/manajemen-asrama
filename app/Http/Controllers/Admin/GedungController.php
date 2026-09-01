<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    public function index()
    {
        $gedungs = Gedung::withCount(['kamars', 'kamars as kamars_kosong_count' => function ($q) {
            $q->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0');
        }, 'kamars as kamars_terisi_count' => function ($q) {
            $q->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") > 0');
        }])->latest()->paginate(10);

        return view('admin.gedung.index', compact('gedungs'));
    }

    public function create()
    {
        return view('admin.gedung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255|unique:gedungs,nama_gedung',
        ], [
            'nama_gedung.required' => 'Nama gedung wajib diisi.',
            'nama_gedung.unique' => 'Nama gedung sudah terdaftar.',
        ]);

        Gedung::create($validated);

        return redirect()->route('admin.gedung.index')->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function edit(Gedung $gedung)
    {
        return view('admin.gedung.edit', compact('gedung'));
    }

    public function update(Request $request, Gedung $gedung)
    {
        $validated = $request->validate([
            'nama_gedung' => 'required|string|max:255|unique:gedungs,nama_gedung,' . $gedung->id,
        ], [
            'nama_gedung.required' => 'Nama gedung wajib diisi.',
            'nama_gedung.unique' => 'Nama gedung sudah digunakan.',
        ]);

        $gedung->update($validated);

        return redirect()->route('admin.gedung.index')->with('success', 'Data gedung berhasil diperbarui.');
    }

    public function destroy(Gedung $gedung)
    {
        if ($gedung->kamars()->whereHas('activeTransaksi')->exists()) {
            return back()->with('error', 'Gedung tidak dapat dihapus karena masih memiliki kamar yang sedang dihuni peserta.');
        }

        $gedung->delete();

        return redirect()->route('admin.gedung.index')->with('success', 'Gedung berhasil dihapus.');
    }
}
