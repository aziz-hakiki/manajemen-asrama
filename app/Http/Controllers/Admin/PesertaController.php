<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                  ->orWhere('instansi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
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
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'nip_nik' => 'bail|required|numeric|digits_between:1,30|unique:pesertas,nip_nik',
            'instansi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:50',
        ], [
            'diklat_id.required' => 'Pilih kegiatan terlebih dahulu.',
            'nama_peserta.required' => 'Nama peserta wajib diisi.',
            'nip_nik.required' => 'NIP / NIK wajib diisi.',
            'nip_nik.numeric' => 'NIP / NIK wajib berupa angka.',
            'nip_nik.digits_between' => 'NIP / NIK harus berupa angka (1-30 digit).',
            'nip_nik.unique' => 'NIP / NIK sudah terdaftar pada peserta lain. Silakan periksa kembali.',
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
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'nip_nik' => [
                'bail',
                'required',
                'numeric',
                'digits_between:1,30',
                Rule::unique('pesertas', 'nip_nik')->ignore($peserta->id),
            ],
            'instansi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:50',
        ], [
            'diklat_id.required' => 'Pilih kegiatan terlebih dahulu.',
            'nama_peserta.required' => 'Nama peserta wajib diisi.',
            'nip_nik.required' => 'NIP / NIK wajib diisi.',
            'nip_nik.numeric' => 'NIP / NIK wajib berupa angka.',
            'nip_nik.digits_between' => 'NIP / NIK harus berupa angka (1-30 digit).',
            'nip_nik.unique' => 'NIP / NIK sudah terdaftar pada peserta lain. Silakan periksa kembali.',
        ]);

        $peserta->update($validated);

        return redirect()->route('admin.peserta.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function checkNip(Request $request)
    {
        $nip = trim($request->query('nip_nik', ''));
        $ignoreId = $request->query('ignore_id');

        if (!$nip) {
            return response()->json(['exists' => false]);
        }

        $query = Peserta::where('nip_nik', $nip);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $peserta = $query->with('diklat')->first();

        if ($peserta) {
            $diklatInfo = $peserta->diklat ? " ({$peserta->diklat->nama_diklat})" : "";
            return response()->json([
                'exists' => true,
                'message' => "NIP/NIK ini sudah terdaftar atas nama {$peserta->nama_peserta}{$diklatInfo}.",
            ]);
        }

        return response()->json(['exists' => false]);
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

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_peserta.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar rapi saat dibuka di Microsoft Excel
            fputs($file, "\xEF\xBB\xBF");
            // Baris Header
            fputcsv($file, ['Nama Peserta', 'Jenis Kelamin', 'NIP/NIK', 'Instansi', 'Keterangan']);
            // Contoh baris 1
            fputcsv($file, ['Ahmad Hidayat, S.Kom', 'Laki-laki', '198901012015011001', 'BPSDM Prov. Jabar', 'Peserta']);
            // Contoh baris 2
            fputcsv($file, ['Siti Rahmawati, S.STP', 'Perempuan', '199203152018022002', 'Dinas Perhubungan', 'Peserta']);
            // Contoh baris 3
            fputcsv($file, ['Dr. Bambang Suryono', 'Laki-laki', '197508201998031005', 'Badan Kepegawaian Daerah', 'Narasumber']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        $jkIndex = -1;
        $nipIndex = -1;
        $instansiIndex = -1;
        $keteranganIndex = -1;

        if ($rawHeader) {
            foreach ($rawHeader as $idx => $col) {
                $cleanedCol = strtolower(trim(str_replace(["\xEF\xBB\xBF", '"', "'"], '', $col)));
                if (str_contains($cleanedCol, 'nama')) {
                    $namaIndex = $idx;
                } elseif (str_contains($cleanedCol, 'kelamin') || str_contains($cleanedCol, 'gender') || $cleanedCol === 'jk' || str_contains($cleanedCol, 'sex')) {
                    $jkIndex = $idx;
                } elseif (str_contains($cleanedCol, 'nip') || str_contains($cleanedCol, 'nik')) {
                    $nipIndex = $idx;
                } elseif (str_contains($cleanedCol, 'instansi') || str_contains($cleanedCol, 'kantor') || str_contains($cleanedCol, 'asal') || str_contains($cleanedCol, 'unit') || str_contains($cleanedCol, 'lembaga')) {
                    $instansiIndex = $idx;
                } elseif (str_contains($cleanedCol, 'keterangan') || str_contains($cleanedCol, 'sebagai') || str_contains($cleanedCol, 'peran') || str_contains($cleanedCol, 'role') || str_contains($cleanedCol, 'status')) {
                    $keteranganIndex = $idx;
                }
            }
        }

        // Jika header tidak terdeteksi otomatis, gunakan urutan default berdasarkan jumlah kolom
        $colCount = count($rawHeader ?? []);
        if ($namaIndex === -1) $namaIndex = 0;
        if ($jkIndex === -1 && $colCount >= 5) $jkIndex = 1;
        if ($nipIndex === -1) $nipIndex = ($jkIndex === 1) ? 2 : 1;
        if ($instansiIndex === -1) $instansiIndex = ($jkIndex === 1) ? 3 : 2;
        if ($keteranganIndex === -1 && $colCount >= 5) $keteranganIndex = 4;

        $importedCount = 0;
        $updatedCount = 0;

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            $nama = isset($row[$namaIndex]) ? trim(str_replace("\xEF\xBB\xBF", '', $row[$namaIndex])) : '';

            // Lewati jika nama kosong atau baris header terulang
            if (empty($nama) || strtolower($nama) === 'nama peserta' || strtolower($nama) === 'nama') {
                continue;
            }

            // Parse Jenis Kelamin
            $jenisKelamin = null;
            if ($jkIndex !== -1 && isset($row[$jkIndex])) {
                $valJk = strtolower(trim($row[$jkIndex]));
                if (in_array($valJk, ['l', 'laki-laki', 'laki - laki', 'laki', 'pria', 'male', 'm'])) {
                    $jenisKelamin = 'Laki-laki';
                } elseif (in_array($valJk, ['p', 'perempuan', 'wanita', 'female', 'f'])) {
                    $jenisKelamin = 'Perempuan';
                }
            }

            // Parse NIP / NIK (hanya angka)
            $nipNik = null;
            if ($nipIndex !== -1 && isset($row[$nipIndex])) {
                $rawNip = trim($row[$nipIndex]);
                $cleanedNip = preg_replace('/[^0-9]/', '', $rawNip);
                $nipNik = !empty($cleanedNip) ? $cleanedNip : null;
            }

            // Parse Instansi
            $instansi = ($instansiIndex !== -1 && isset($row[$instansiIndex])) ? trim($row[$instansiIndex]) : null;

            // Parse Keterangan (Peserta / Narasumber)
            $keterangan = 'Peserta';
            if ($keteranganIndex !== -1 && isset($row[$keteranganIndex])) {
                $valKet = strtolower(trim($row[$keteranganIndex]));
                if (str_contains($valKet, 'narasumber') || str_contains($valKet, 'pemateri') || str_contains($valKet, 'tutor') || str_contains($valKet, 'pengajar')) {
                    $keterangan = 'Narasumber';
                }
            }

            // Cek jika NIP/NIK sudah ada di database: perbarui data agar tidak duplikat
            if (!empty($nipNik)) {
                $existing = Peserta::where('nip_nik', $nipNik)->first();
                if ($existing) {
                    $existing->update([
                        'diklat_id' => $diklatId,
                        'nama_peserta' => $nama,
                        'jenis_kelamin' => $jenisKelamin ?? $existing->jenis_kelamin,
                        'instansi' => $instansi ?? $existing->instansi,
                        'keterangan' => $keterangan ?? $existing->keterangan,
                    ]);
                    $updatedCount++;
                    continue;
                }
            }

            Peserta::create([
                'diklat_id' => $diklatId,
                'nama_peserta' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'nip_nik' => $nipNik,
                'instansi' => $instansi,
                'keterangan' => $keterangan,
            ]);
            $importedCount++;
        }
        fclose($handle);

        $message = "Berhasil memproses data peserta: {$importedCount} data baru ditambahkan";
        if ($updatedCount > 0) {
            $message .= ", {$updatedCount} data diperbarui (NIP/NIK sudah terdaftar sebelumnya)";
        }
        $message .= ".";

        return redirect()->route('admin.peserta.index', ['diklat_id' => $diklatId])
            ->with('success', $message);
    }
}
