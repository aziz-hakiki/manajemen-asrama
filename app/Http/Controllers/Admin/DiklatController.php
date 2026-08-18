<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use Illuminate\Http\Request;

class DiklatController extends Controller
{
    public function index(Request $request)
    {
        $query = Diklat::withCount('pesertas');

        if ($request->filled('search')) {
            $query->where('nama_diklat', 'like', '%' . $request->search . '%');
        }

        $diklats = $query->latest()->paginate(10)->withQueryString();

        return view('admin.diklat.index', compact('diklats'));
    }

    public function create()
    {
        return view('admin.diklat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_diklat' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'nama_diklat.required' => 'Nama diklat wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib ditentukan.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib ditentukan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        Diklat::create($validated);

        return redirect()->route('admin.diklat.index')->with('success', 'Program diklat berhasil ditambahkan.');
    }

    public function edit(Diklat $diklat)
    {
        return view('admin.diklat.edit', compact('diklat'));
    }

    public function update(Request $request, Diklat $diklat)
    {
        $validated = $request->validate([
            'nama_diklat' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'nama_diklat.required' => 'Nama diklat wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib ditentukan.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib ditentukan.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $diklat->update($validated);

        return redirect()->route('admin.diklat.index')->with('success', 'Data program diklat berhasil diperbarui.');
    }

    public function destroy(Diklat $diklat)
    {
        $diklat->delete();

        return redirect()->route('admin.diklat.index')->with('success', 'Program diklat berhasil dihapus.');
    }
}
