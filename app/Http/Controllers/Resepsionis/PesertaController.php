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
        $diklats = Diklat::whereDate('tanggal_selesai', '>=', now()->toDateString())
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
        return view('resepsionis.peserta.create', compact('diklats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'diklat_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('diklats', 'id')->where(function ($query) {
                    $query->whereDate('tanggal_selesai', '>=', now()->toDateString());
                }),
            ],
            'nama_peserta' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'nip_nik' => 'bail|required|numeric|digits_between:1,30|unique:pesertas,nip_nik',
            'instansi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:50',
        ], [
            'diklat_id.required' => 'Pilih kegiatan diklat terlebih dahulu.',
            'diklat_id.exists' => 'Kegiatan diklat yang dipilih sudah selesai atau tidak valid.',
            'nama_peserta.required' => 'Nama peserta wajib diisi.',
            'nip_nik.required' => 'NIP / NIK wajib diisi.',
            'nip_nik.numeric' => 'NIP / NIK wajib berupa angka.',
            'nip_nik.digits_between' => 'NIP / NIK harus berupa angka (1-30 digit).',
            'nip_nik.unique' => 'NIP / NIK sudah terdaftar pada peserta lain. Silakan periksa kembali.',
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
