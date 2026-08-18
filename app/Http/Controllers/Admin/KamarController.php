<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $gedungs = Gedung::all();

        $query = Kamar::with('gedung');

        if ($request->filled('gedung_id')) {
            $query->where('gedung_id', $request->gedung_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }

        $kamars = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kamar.index', compact('kamars', 'gedungs'));
    }

    public function create()
    {
        $gedungs = Gedung::all();
        return view('admin.kamar.create', compact('gedungs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gedung_id' => 'required|exists:gedungs,id',
            'nomor_kamar' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kamars')->where(fn ($query) => $query->where('gedung_id', $request->gedung_id))
            ],
            'kapasitas' => 'required|integer|min:1|max:10',
        ], [
            'gedung_id.required' => 'Pilih gedung terlebih dahulu.',
            'nomor_kamar.required' => 'Nomor kamar wajib diisi.',
            'nomor_kamar.unique' => 'Nomor kamar sudah ada di gedung yang dipilih.',
            'kapasitas.required' => 'Kapasitas kamar wajib diisi.',
        ]);

        Kamar::create([
            'gedung_id' => $validated['gedung_id'],
            'nomor_kamar' => $validated['nomor_kamar'],
            'kapasitas' => $validated['kapasitas'],
            'status' => 'kosong',
        ]);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        $gedungs = Gedung::all();
        return view('admin.kamar.edit', compact('kamar', 'gedungs'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $validated = $request->validate([
            'gedung_id' => 'required|exists:gedungs,id',
            'nomor_kamar' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kamars')
                    ->where(fn ($query) => $query->where('gedung_id', $request->gedung_id))
                    ->ignore($kamar->id)
            ],
            'kapasitas' => 'required|integer|min:1|max:10',
            'status' => 'required|in:kosong,terisi',
        ], [
            'gedung_id.required' => 'Pilih gedung terlebih dahulu.',
            'nomor_kamar.required' => 'Nomor kamar wajib diisi.',
            'nomor_kamar.unique' => 'Nomor kamar sudah terdaftar di gedung ini.',
        ]);

        $kamar->update($validated);

        return redirect()->route('admin.kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        if ($kamar->status === 'terisi') {
            return back()->with('error', 'Kamar tidak dapat dihapus karena saat ini berstatus terisi.');
        }

        $kamar->delete();

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
