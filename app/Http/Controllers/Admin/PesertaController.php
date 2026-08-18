<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use App\Models\Peserta;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $diklats = Diklat::all();
        $query = Peserta::with(['diklat', 'transaksi' => function ($q) {
            $q->where('status', 'menginap')->with('kamar.gedung');
        }]);

        if ($request->filled('diklat_id')) {
            $query->where('diklat_id', $request->diklat_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peserta', 'like', "%{$search}%")
                  ->orWhere('nip_nik', 'like', "%{$search}%")
                  ->orWhere('instansi', 'like', "%{$search}%");
            });
        }

        $pesertas = $query->latest()->paginate(15)->withQueryString();

        return view('admin.peserta.index', compact('pesertas', 'diklats'));
    }

    public function create()
    {
        $diklats = Diklat::all();
        return view('admin.peserta.create', compact('diklats'));
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

        Peserta::create($validated);

        return redirect()->route('admin.peserta.index')->with('success', 'Data peserta berhasil ditambahkan.');
    }

    public function edit(Peserta $peserta)
    {
        $diklats = Diklat::all();
        return view('admin.peserta.edit', compact('peserta', 'diklats'));
    }

    public function update(Request $request, Peserta $peserta)
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

        $peserta->update($validated);

        return redirect()->route('admin.peserta.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy(Peserta $peserta)
    {
        if ($peserta->transaksi()->where('status', 'menginap')->exists()) {
            return back()->with('error', 'Peserta tidak dapat dihapus karena masih aktif menginap di asrama.');
        }

        $peserta->delete();

        return redirect()->route('admin.peserta.index')->with('success', 'Data peserta berhasil dihapus.');
    }

    public function importForm()
    {
        $diklats = Diklat::all();
        return view('admin.peserta.import', compact('diklats'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'diklat_id' => 'required|exists:diklats,id',
            'file_peserta' => 'required|file|max:5120',
        ], [
            'diklat_id.required' => 'Pilih kegiatan diklat yang sesuai.',
            'file_peserta.required' => 'Silakan pilih file CSV daftar peserta.',
        ]);

        $file = $request->file('file_peserta');
        $diklatId = $request->diklat_id;

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Gagal membaca file yang diunggah.');
        }

        // Baca baris pertama untuk mendeteksi delimiter (, atau ; atau \t atau |)
        $firstLine = fgets($handle);
        $firstLine = str_replace("\xEF\xBB\xBF", '', $firstLine); // Hapus UTF-8 BOM

        $delimiters = [',', ';', "\t", '|'];
        $delimiterCounts = [];
        foreach ($delimiters as $d) {
            $delimiterCounts[$d] = substr_count($firstLine, $d);
        }
        arsort($delimiterCounts);
        $delimiter = key($delimiterCounts);
        if ($delimiterCounts[$delimiter] === 0) {
            $delimiter = ',';
        }

        // Kembalikan kursor file ke awal
        rewind($handle);

        // Baca baris header
        $rawHeader = fgetcsv($handle, 2000, $delimiter);
        
        $namaIndex = -1;
        $nipIndex = -1;
        $instansiIndex = -1;

        if ($rawHeader) {
            foreach ($rawHeader as $idx => $col) {
                $cleanedCol = strtolower(trim(str_replace(["\xEF\xBB\xBF", '"', "'"], '', $col)));
                if (str_contains($cleanedCol, 'nama')) {
                    $namaIndex = $idx;
                } elseif (str_contains($cleanedCol, 'nip') || str_contains($cleanedCol, 'nik')) {
                    $nipIndex = $idx;
                } elseif (str_contains($cleanedCol, 'instansi') || str_contains($cleanedCol, 'kantor') || str_contains($cleanedCol, 'asal') || str_contains($cleanedCol, 'unit')) {
                    $instansiIndex = $idx;
                }
            }
        }

        // Jika header tidak terdeteksi otomatis, gunakan urutan default 0, 1, 2
        if ($namaIndex === -1) $namaIndex = 0;
        if ($nipIndex === -1) $nipIndex = 1;
        if ($instansiIndex === -1) $instansiIndex = 2;

        $importedCount = 0;
        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $nama = isset($row[$namaIndex]) ? trim(str_replace("\xEF\xBB\xBF", '', $row[$namaIndex])) : '';
            $nipNik = isset($row[$nipIndex]) ? trim($row[$nipIndex]) : null;
            $instansi = isset($row[$instansiIndex]) ? trim($row[$instansiIndex]) : null;

            // Lewati jika nama kosong atau baris header terulang
            if (!empty($nama) && strtolower($nama) !== 'nama peserta' && strtolower($nama) !== 'nama') {
                Peserta::create([
                    'diklat_id' => $diklatId,
                    'nama_peserta' => $nama,
                    'nip_nik' => $nipNik,
                    'instansi' => $instansi,
                ]);
                $importedCount++;
            }
        }
        fclose($handle);

        return redirect()->route('admin.peserta.index', ['diklat_id' => $diklatId])
            ->with('success', "Berhasil mengimpor {$importedCount} data peserta.");
    }
}
