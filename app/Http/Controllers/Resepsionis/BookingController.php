<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Diklat;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $gedungs = Gedung::with(['kamars' => function ($q) {
            $q->orderBy('nomor_kamar', 'asc');
        }])->withCount('kamars')->orderBy('nama_gedung', 'asc')->get();

        $user = auth()->user();
        $assignedGedungId = ($user && $user->role === 'resepsionis') ? $user->assignedGedungId() : null;

        // Validasi akses jika resepsionis memiliki gedung penugasan
        if ($assignedGedungId) {
            if (!$request->filled('gedung_id')) {
                return redirect()->route('resepsionis.booking.index', [
                    'gedung_id' => $assignedGedungId,
                    'tanggal' => $request->get('tanggal', now()->toDateString()),
                ]);
            }

            if ($request->gedung_id != $assignedGedungId) {
                $assignedGedung = $gedungs->firstWhere('id', $assignedGedungId);
                $namaGedung = $assignedGedung->nama_gedung ?? 'asrama penugasan Anda';
                return redirect()->route('resepsionis.booking.index', [
                    'gedung_id' => $assignedGedungId,
                    'tanggal' => $request->get('tanggal', now()->toDateString()),
                ])->with('warning', "Akses dibatasi. Anda bertugas di {$namaGedung}.");
            }
        }

        // Jika tidak ada gedung_id atau gedung_id tidak valid, arahkan ke gedung pertama
        if (!$request->filled('gedung_id') && $gedungs->isNotEmpty()) {
            return redirect()->route('resepsionis.booking.index', [
                'gedung_id' => $gedungs->first()->id,
                'tanggal' => $request->get('tanggal', now()->toDateString()),
            ]);
        }

        $selectedGedung = $gedungs->firstWhere('id', $request->gedung_id) ?? $gedungs->first();

        // Tanggal yang dipilih (Default: Hari Ini)
        $tanggalInput = $request->get('tanggal', now()->toDateString());
        try {
            $selectedDate = Carbon::parse($tanggalInput)->toDateString();
        } catch (\Exception $e) {
            $selectedDate = now()->toDateString();
        }

        // Ambil seluruh kamar pada gedung yang dipilih (tanpa pagination agar tampil utuh layaknya denah bioskop)
        $kamarsRaw = Kamar::where('gedung_id', $selectedGedung ? $selectedGedung->id : 0)
            ->with([
                'transaksi' => function ($q) use ($selectedDate) {
                    $q->where(function ($q2) use ($selectedDate) {
                        // Sedang menginap pada tanggal tersebut
                        $q2->where('status', 'menginap')
                           ->whereDate('tanggal_masuk', '<=', $selectedDate);
                    })->orWhere(function ($q2) use ($selectedDate) {
                        // Selesai (sudah checkout) pada tanggal tersebut
                        $q2->where('status', 'selesai')
                           ->where(function ($q3) use ($selectedDate) {
                               $q3->whereDate('tanggal_keluar', $selectedDate)
                                  ->orWhere(function ($q4) use ($selectedDate) {
                                      $q4->whereDate('tanggal_masuk', '<=', $selectedDate)
                                         ->whereDate('tanggal_keluar', '>=', $selectedDate);
                                  });
                           });
                    })->with('peserta.diklat');
                },
                'activeBookings' => function ($q) use ($selectedDate) {
                    $q->whereDate('tanggal_mulai', '<=', $selectedDate)
                      ->whereDate('tanggal_selesai', '>=', $selectedDate)
                      ->with(['peserta.diklat', 'diklat']);
                }
            ])
            ->orderBy('nomor_kamar', 'asc')
            ->get();

        // Hitung status visual bioskop untuk masing-masing kamar pada tanggal yang dipilih
        $kamarsWithStatus = $kamarsRaw->map(function ($kamar) use ($selectedDate) {
            // 1. Cek Rusak
            if ($kamar->status === 'rusak') {
                return [
                    'id' => $kamar->id,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'kapasitas' => $kamar->kapasitas,
                    'status_kategori' => 'rusak',
                    'color_name' => 'gray',
                    'label' => 'Rusak (Perbaikan)',
                    'badge_class' => 'bg-slate-100 text-slate-600 border-slate-300',
                    'card_class' => 'border-slate-300 bg-slate-100/90 text-slate-500 cursor-not-allowed',
                    'penghuni' => null,
                    'booking' => null,
                    'keterangan' => 'Kamar ini sedang dalam pemeliharaan/perbaikan fasilitas.',
                ];
            }

            // 2. Cek Transaksi (Terisi Menginap atau Selesai Check-out pada tanggal tersebut)
            $transaksiList = $kamar->transaksi ?? collect();
            $activeOccupants = [];
            $completedOccupants = [];
            $occupantList = [];

            if ($transaksiList->isNotEmpty()) {
                foreach ($transaksiList as $tr) {
                    $masukDate = Carbon::parse($tr->tanggal_masuk)->toDateString();
                    $keluarDate = $tr->tanggal_keluar 
                        ? Carbon::parse($tr->tanggal_keluar)->toDateString() 
                        : ($tr->peserta && $tr->peserta->diklat ? Carbon::parse($tr->peserta->diklat->tanggal_selesai)->toDateString() : null);

                    $isRelevant = false;
                    if ($tr->status === 'menginap') {
                        if ($masukDate <= $selectedDate && (!$keluarDate || $keluarDate >= $selectedDate)) {
                            $isRelevant = true;
                        }
                    } elseif ($tr->status === 'selesai') {
                        if ($keluarDate === $selectedDate || ($masukDate <= $selectedDate && $keluarDate >= $selectedDate)) {
                            $isRelevant = true;
                        }
                    }

                    if ($isRelevant) {
                        $occupantData = [
                            'nama' => $tr->peserta->nama_peserta ?? 'Tamu',
                            'nip' => $tr->peserta->nip_nik ?? '',
                            'instansi' => $tr->peserta->instansi ?? 'Umum',
                            'diklat' => $tr->peserta->diklat->nama_diklat ?? 'Program Diklat',
                            'tanggal_masuk' => Carbon::parse($tr->tanggal_masuk)->translatedFormat('d M Y H:i'),
                            'tanggal_keluar' => $tr->tanggal_keluar ? Carbon::parse($tr->tanggal_keluar)->translatedFormat('d M Y H:i') : null,
                            'status' => $tr->status, // 'menginap' atau 'selesai'
                        ];

                        $occupantList[] = $occupantData;
                        if ($tr->status === 'menginap') {
                            $activeOccupants[] = $occupantData;
                        } else {
                            $completedOccupants[] = $occupantData;
                        }
                    }
                }
            }

            // Jika ada yang sedang menginap -> Status Terisi (Merah)
            if (count($activeOccupants) > 0) {
                return [
                    'id' => $kamar->id,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'kapasitas' => $kamar->kapasitas,
                    'status_kategori' => 'terisi',
                    'color_name' => 'red',
                    'label' => 'Terisi (' . count($activeOccupants) . '/' . $kamar->kapasitas . ')',
                    'badge_class' => 'bg-rose-100 text-rose-700 border-rose-300',
                    'card_class' => 'border-rose-400 bg-rose-500 text-white shadow-rose-200/50 hover:bg-rose-600',
                    'penghuni' => $occupantList,
                    'booking' => null,
                    'keterangan' => 'Kamar sedang dihuni oleh ' . count($activeOccupants) . ' orang.',
                ];
            }

            // Jika tidak ada yang menginap, namun ada yang sudah selesai checkout pada tanggal tersebut -> Status Selesai (Tetap Merah)
            if (count($completedOccupants) > 0) {
                return [
                    'id' => $kamar->id,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'kapasitas' => $kamar->kapasitas,
                    'status_kategori' => 'selesai',
                    'color_name' => 'red',
                    'label' => 'Selesai (' . count($completedOccupants) . ' Tamu)',
                    'badge_class' => 'bg-rose-100 text-rose-700 border-rose-300',
                    'card_class' => 'border-rose-400 bg-rose-500 text-white shadow-rose-200/50 hover:bg-rose-600',
                    'penghuni' => $occupantList,
                    'booking' => null,
                    'keterangan' => 'Kamar telah selesai digunakan oleh ' . count($completedOccupants) . ' orang pada tanggal ini.',
                ];
            }

            // 3. Cek Booked (Pemesanan pada rentang tanggal tersebut)
            $activeBooking = $kamar->activeBookings->first();
            if ($activeBooking) {
                return [
                    'id' => $kamar->id,
                    'nomor_kamar' => $kamar->nomor_kamar,
                    'kapasitas' => $kamar->kapasitas,
                    'status_kategori' => 'booked',
                    'color_name' => 'violet',
                    'label' => 'Booked (Dipesan)',
                    'badge_class' => 'bg-purple-100 text-purple-700 border-purple-300',
                    'card_class' => 'border-purple-400 bg-purple-600 text-white shadow-purple-200/50 hover:bg-purple-700',
                    'penghuni' => null,
                    'booking' => [
                        'id' => $activeBooking->id,
                        'nama_pemesan' => $activeBooking->nama_pemesan,
                        'instansi' => $activeBooking->instansi ?? '-',
                        'no_telepon' => $activeBooking->no_telepon ?? '-',
                        'diklat' => $activeBooking->diklat->nama_diklat ?? ($activeBooking->peserta->diklat->nama_diklat ?? '-'),
                        'peserta_id' => $activeBooking->peserta_id,
                        'peserta_nama' => $activeBooking->peserta->nama_peserta ?? null,
                        'tanggal_mulai' => Carbon::parse($activeBooking->tanggal_mulai)->translatedFormat('d M Y'),
                        'tanggal_selesai' => Carbon::parse($activeBooking->tanggal_selesai)->translatedFormat('d M Y'),
                        'keterangan' => $activeBooking->keterangan ?? '-',
                    ],
                    'keterangan' => 'Dipesan oleh ' . $activeBooking->nama_pemesan . ' (' . Carbon::parse($activeBooking->tanggal_mulai)->format('d/m') . ' - ' . Carbon::parse($activeBooking->tanggal_selesai)->format('d/m/Y') . ')',
                ];
            }

            // 4. Kosong (Tersedia untuk dipesan)
            return [
                'id' => $kamar->id,
                'nomor_kamar' => $kamar->nomor_kamar,
                'kapasitas' => $kamar->kapasitas,
                'status_kategori' => 'kosong',
                'color_name' => 'green',
                'label' => 'Kosong (Tersedia)',
                'badge_class' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                'card_class' => 'border-emerald-500 bg-emerald-500 text-white shadow-emerald-200/50 hover:bg-emerald-600',
                'penghuni' => null,
                'booking' => null,
                'keterangan' => 'Kamar kosong dan siap dibooking atau ditempati.',
            ];
        });

        // Hitung ringkasan statistik untuk gedung terpilih
        $stats = [
            'total' => $kamarsWithStatus->count(),
            'kosong' => $kamarsWithStatus->where('status_kategori', 'kosong')->count(),
            'terisi' => $kamarsWithStatus->whereIn('status_kategori', ['terisi', 'selesai'])->count(),
            'booked' => $kamarsWithStatus->where('status_kategori', 'booked')->count(),
            'rusak' => $kamarsWithStatus->where('status_kategori', 'rusak')->count(),
        ];

        // Ambil data untuk tabel "Daftar Reservasi & Hunian"
        $kamarIds = $kamarsRaw->pluck('id');

        // 1. Ambil transaksi (baik yang sedang menginap / terisi maupun yang sudah checkout / selesai) pada tanggal yang dipilih
        $transaksis = TransaksiAsrama::whereIn('kamar_id', $kamarIds)
            ->with(['peserta.diklat', 'kamar'])
            ->where(function ($q) use ($selectedDate) {
                // Sedang menginap pada tanggal tersebut
                $q->where(function ($q2) use ($selectedDate) {
                    $q2->where('status', 'menginap')
                       ->whereDate('tanggal_masuk', '<=', $selectedDate);
                })
                // ATAU sudah checkout tepat pada tanggal tersebut
                ->orWhere(function ($q2) use ($selectedDate) {
                    $q2->where('status', 'selesai')
                       ->whereDate('tanggal_keluar', $selectedDate);
                })
                // ATAU sudah checkout, namun masa menginapnya mencakup tanggal tersebut
                ->orWhere(function ($q2) use ($selectedDate) {
                    $q2->where('status', 'selesai')
                       ->whereDate('tanggal_masuk', '<=', $selectedDate)
                       ->whereDate('tanggal_keluar', '>=', $selectedDate);
                });
            })
            ->get();

        // 2. Ambil pemesanan (booking) aktif pada tanggal tersebut
        $bookings = Booking::whereIn('kamar_id', $kamarIds)
            ->where('status', 'booked')
            ->whereDate('tanggal_mulai', '<=', $selectedDate)
            ->whereDate('tanggal_selesai', '>=', $selectedDate)
            ->with(['peserta.diklat', 'diklat', 'kamar'])
            ->get();

        $daftarHunianDanReservasi = collect();

        // Masukkan Transaksi (Terisi & Selesai)
        foreach ($transaksis as $tr) {
            $isSelesai = ($tr->status === 'selesai');
            $checkOutStr = $tr->tanggal_keluar 
                ? Carbon::parse($tr->tanggal_keluar)->translatedFormat('d M Y H:i') 
                : ($tr->peserta && $tr->peserta->diklat && $tr->peserta->diklat->tanggal_selesai 
                    ? Carbon::parse($tr->peserta->diklat->tanggal_selesai)->translatedFormat('d M Y') . ' (Estimasi)' 
                    : '-');

            $daftarHunianDanReservasi->push([
                'id' => $tr->id,
                'type' => 'transaksi',
                'nomor_kamar' => $tr->kamar->nomor_kamar ?? '-',
                'kamar_id' => $tr->kamar_id,
                'status_kategori' => $isSelesai ? 'selesai' : 'terisi',
                'status_label' => $isSelesai ? 'Selesai' : 'Terisi',
                'nama' => $tr->peserta->nama_peserta ?? 'Tamu',
                'sub_nama' => $tr->peserta && $tr->peserta->nip_nik ? 'NIP. ' . $tr->peserta->nip_nik : null,
                'instansi' => $tr->peserta->instansi ?? 'Umum',
                'diklat' => $tr->peserta->diklat->nama_diklat ?? null,
                'check_in' => Carbon::parse($tr->tanggal_masuk)->translatedFormat('d M Y H:i'),
                'check_out' => $checkOutStr,
            ]);
        }

        // Masukkan Booking Aktif
        foreach ($bookings as $b) {
            $daftarHunianDanReservasi->push([
                'id' => $b->id,
                'type' => 'booking',
                'nomor_kamar' => $b->kamar->nomor_kamar ?? '-',
                'kamar_id' => $b->kamar_id,
                'status_kategori' => 'booked',
                'status_label' => 'Booked',
                'nama' => $b->nama_pemesan,
                'sub_nama' => $b->no_telepon ? 'HP. ' . $b->no_telepon : null,
                'instansi' => $b->instansi ?? ($b->peserta->instansi ?? 'Umum'),
                'diklat' => $b->diklat->nama_diklat ?? ($b->peserta->diklat->nama_diklat ?? null),
                'check_in' => Carbon::parse($b->tanggal_mulai)->translatedFormat('d M Y'),
                'check_out' => Carbon::parse($b->tanggal_selesai)->translatedFormat('d M Y'),
            ]);
        }

        // Urutkan berdasarkan nomor kamar secara natural
        $daftarHunianDanReservasi = $daftarHunianDanReservasi->sortBy('nomor_kamar', SORT_NATURAL)->values();

        // Daftar peserta yang belum menginap untuk modal form pemesanan
        $availablePesertas = Peserta::with('diklat')
            ->whereDoesntHave('transaksi', function ($q) {
                $q->where('status', 'menginap');
            })
            ->orderBy('nama_peserta')
            ->get();

        // Daftar diklat yang akan datang (tanggal mulai belum lewat) beserta peserta yang belum menginap untuk modal form
        $today = now()->toDateString();
        $diklats = Diklat::whereDate('tanggal_mulai', '>=', $today)
            ->with(['pesertas' => function ($q) {
                $q->whereDoesntHave('transaksi', function ($q2) {
                    $q2->where('status', 'menginap');
                });
            }])
            ->withCount('pesertas')
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('nama_diklat', 'asc')
            ->get();

        $diklatsJson = $diklats->map(function ($d) {
            return [
                'id' => $d->id,
                'nama_diklat' => $d->nama_diklat,
                'tanggal_mulai' => Carbon::parse($d->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::parse($d->tanggal_selesai)->format('Y-m-d'),
                'tanggal_mulai_formatted' => Carbon::parse($d->tanggal_mulai)->translatedFormat('d M Y'),
                'tanggal_selesai_formatted' => Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y'),
                'pesertas_count' => $d->pesertas_count,
                'pesertas' => $d->pesertas->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama_peserta' => $p->nama_peserta,
                        'nip_nik' => $p->nip_nik ?? '',
                        'instansi' => $p->instansi ?? '',
                    ];
                })->values(),
            ];
        })->values();

        // Filter gedung yang tersedia untuk modal booking sesuai hak akses penugasan resepsionis
        $gedungsForBooking = $gedungs;
        if ($assignedGedungId) {
            $gedungsForBooking = $gedungs->where('id', $assignedGedungId)->values();
        }

        $gedungsJson = $gedungsForBooking->map(function ($g) {
            return [
                'id' => $g->id,
                'nama_gedung' => $g->nama_gedung,
                'kamars' => $g->kamars->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'nomor_kamar' => $k->nomor_kamar,
                        'kapasitas' => $k->kapasitas,
                        'is_rusak' => ($k->status === 'rusak'),
                    ];
                })->values(),
            ];
        })->values();

        return view('resepsionis.booking.index', compact(
            'gedungs',
            'selectedGedung',
            'selectedDate',
            'kamarsWithStatus',
            'stats',
            'daftarHunianDanReservasi',
            'availablePesertas',
            'diklats',
            'diklatsJson',
            'gedungsJson'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kamar_id' => 'required|exists:kamars,id',
            'nama_pemesan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'peserta_id' => 'nullable|exists:pesertas,id',
            'diklat_id' => 'nullable|exists:diklats,id',
            'instansi' => 'nullable|string|max:255',
            'no_telepon' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
        ], [
            'kamar_id.required' => 'Pilih kamar yang akan dibooking.',
            'nama_pemesan.required' => 'Nama pemesan atau perwakilan wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai booking wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai booking wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $kamar = Kamar::findOrFail($validated['kamar_id']);

        // Validasi hak akses gedung untuk resepsionis
        $assignedGedungId = auth()->user()->assignedGedungId();
        if ($assignedGedungId && $kamar->gedung_id != $assignedGedungId) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk membooking kamar di asrama ini.');
        }

        // Validasi kondisi rusak
        if ($kamar->status === 'rusak') {
            return back()->with('error', "Kamar {$kamar->nomor_kamar} sedang rusak dan tidak dapat dibooking.");
        }

        $startDate = $validated['tanggal_mulai'];
        $endDate = $validated['tanggal_selesai'];

        // Cek konflik dengan booking lain yang masih aktif pada rentang tanggal tersebut
        $conflictBooking = Booking::where('kamar_id', $kamar->id)
            ->where('status', 'booked')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereDate('tanggal_mulai', '<=', $endDate)
                  ->whereDate('tanggal_selesai', '>=', $startDate);
            })
            ->first();

        if ($conflictBooking) {
            $jadwalConflict = Carbon::parse($conflictBooking->tanggal_mulai)->format('d/m/Y') . ' s/d ' . Carbon::parse($conflictBooking->tanggal_selesai)->format('d/m/Y');
            return back()->with('error', "Kamar {$kamar->nomor_kamar} sudah dibooking oleh {$conflictBooking->nama_pemesan} pada periode {$jadwalConflict}.");
        }

        // Jika peserta_id dipilih, lengkapi instansi & diklat jika kosong
        if (!empty($validated['peserta_id'])) {
            $peserta = Peserta::with('diklat')->find($validated['peserta_id']);
            if ($peserta) {
                if (empty($validated['instansi']) && $peserta->instansi) {
                    $validated['instansi'] = $peserta->instansi;
                }
                if (empty($validated['diklat_id']) && $peserta->diklat_id) {
                    $validated['diklat_id'] = $peserta->diklat_id;
                }
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'booked';

        Booking::create($validated);

        return redirect()->route('resepsionis.booking.index', [
            'gedung_id' => $kamar->gedung_id,
            'tanggal' => $startDate,
        ])->with('success', "Booking Kamar {$kamar->nomor_kamar} untuk {$validated['nama_pemesan']} berhasil dibuat!");
    }

    public function storeBatch(Request $request)
    {
        $validated = $request->validate([
            'diklat_id' => 'required|exists:diklats,id',
            'gedung_id' => 'required|exists:gedungs,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nama_pemesan' => 'nullable|string|max:255',
            'mode_kamar' => 'required|in:semua,pilih',
            'kamar_ids' => 'nullable|array',
            'kamar_ids.*' => 'exists:kamars,id',
            'keterangan' => 'nullable|string',
        ], [
            'diklat_id.required' => 'Pilih kegiatan atau diklat yang akan dibooking.',
            'gedung_id.required' => 'Pilih asrama yang akan dibooking.',
            'tanggal_mulai.required' => 'Tanggal mulai booking wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai booking wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        $diklat = Diklat::findOrFail($validated['diklat_id']);
        $gedung = Gedung::findOrFail($validated['gedung_id']);

        // Validasi hak akses gedung untuk resepsionis
        $assignedGedungId = auth()->user()->assignedGedungId();
        if ($assignedGedungId && $gedung->id != $assignedGedungId) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk membooking kamar di asrama ini.');
        }

        if ($validated['mode_kamar'] === 'semua') {
            $kamars = Kamar::where('gedung_id', $gedung->id)
                ->where('status', '!=', 'rusak')
                ->get();
        } else {
            $kamarIds = $validated['kamar_ids'] ?? [];
            if (empty($kamarIds)) {
                return back()->with('error', 'Pilih minimal satu kamar untuk dibooking.');
            }
            $kamars = Kamar::where('gedung_id', $gedung->id)
                ->where('status', '!=', 'rusak')
                ->whereIn('id', $kamarIds)
                ->get();
        }

        if ($kamars->isEmpty()) {
            return back()->with('error', 'Tidak ada kamar aktif yang dapat dibooking di asrama ini.');
        }

        $startDate = $validated['tanggal_mulai'];
        $endDate = $validated['tanggal_selesai'];
        $namaPemesan = !empty($validated['nama_pemesan']) ? $validated['nama_pemesan'] : $diklat->nama_diklat;

        $bookedCount = 0;
        $conflictCount = 0;

        DB::transaction(function () use ($kamars, $startDate, $endDate, $diklat, $namaPemesan, $validated, &$bookedCount, &$conflictCount) {
            foreach ($kamars as $kamar) {
                // Cek konflik dengan booking yang berstatus 'booked' pada rentang tanggal tersebut
                $conflict = Booking::where('kamar_id', $kamar->id)
                    ->where('status', 'booked')
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereDate('tanggal_mulai', '<=', $endDate)
                          ->whereDate('tanggal_selesai', '>=', $startDate);
                    })
                    ->exists();

                if ($conflict) {
                    $conflictCount++;
                    continue;
                }

                Booking::create([
                    'kamar_id' => $kamar->id,
                    'diklat_id' => $diklat->id,
                    'nama_pemesan' => $namaPemesan,
                    'instansi' => 'Peserta ' . $diklat->nama_diklat,
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                    'keterangan' => $validated['keterangan'] ?? ('Booking Diklat ' . $diklat->nama_diklat),
                    'status' => 'booked',
                    'user_id' => auth()->id(),
                ]);

                $bookedCount++;
            }
        });

        if ($bookedCount === 0 && $conflictCount > 0) {
            return back()->with('error', "Seluruh kamar yang dipilih sudah memiliki jadwal booking lain pada periode tersebut.");
        }

        $msg = "Berhasil mem-booking {$bookedCount} kamar di {$gedung->nama_gedung} untuk {$diklat->nama_diklat} (Periode: " . Carbon::parse($startDate)->format('d/m/Y') . " s/d " . Carbon::parse($endDate)->format('d/m/Y') . ").";
        if ($conflictCount > 0) {
            $msg .= " ({$conflictCount} kamar dilewati karena jadwal berbenturan).";
        }

        return redirect()->route('resepsionis.booking.index', [
            'gedung_id' => $gedung->id,
            'tanggal' => $startDate,
        ])->with('success', $msg);
    }

    public function checkIn(Request $request, Booking $booking)
    {
        if ($booking->status !== 'booked') {
            return back()->with('error', 'Status pemesanan ini sudah tidak dapat di-checkin.');
        }

        $kamar = $booking->kamar;
        if (!$kamar || $kamar->status === 'rusak') {
            return back()->with('error', 'Kamar tidak dapat digunakan atau sedang dalam perbaikan.');
        }

        // Cek kapasitas kamar
        $activeCount = $kamar->activeTransaksi()->count();
        if ($activeCount >= $kamar->kapasitas) {
            return back()->with('error', "Kamar {$kamar->nomor_kamar} sudah penuh (kapasitas {$kamar->kapasitas} orang).");
        }

        $pesertaId = $request->input('peserta_id', $booking->peserta_id);

        // Jika peserta_id ada (baik dari request maupun yang tersimpan di booking)
        if ($pesertaId) {
            $peserta = Peserta::find($pesertaId);
            if ($peserta && $peserta->transaksi()->where('status', 'menginap')->exists()) {
                return back()->with('error', "Peserta {$peserta->nama_peserta} sudah aktif menginap di kamar lain.");
            }

            DB::transaction(function () use ($booking, $kamar, $pesertaId) {
                TransaksiAsrama::create([
                    'peserta_id' => $pesertaId,
                    'kamar_id' => $booking->kamar_id,
                    'tanggal_masuk' => now(),
                    'status' => 'menginap',
                ]);

                $booking->update([
                    'status' => 'checkin',
                    'peserta_id' => $pesertaId,
                ]);
                $kamar->update(['status' => 'terisi']);
            });

            $namaTamu = $peserta ? $peserta->nama_peserta : $booking->nama_pemesan;
            return redirect()->route('resepsionis.booking.index', [
                'gedung_id' => $kamar->gedung_id,
                'tanggal' => now()->toDateString(),
            ])->with('success', "Check-in berhasil! Peserta {$namaTamu} resmi menginap di kamar {$kamar->nomor_kamar}.");
        }

        // Jika belum tertaut peserta langsung dan tidak ada peserta_id di request, alihkan ke form check-in dengan kamar terpilih
        return redirect()->route('resepsionis.checkin.create', [
            'kamar_id' => $kamar->id,
            'diklat_id' => $booking->diklat_id,
        ])->with('info', "Silakan pilih peserta yang akan di-checkin untuk pemesanan Kamar {$kamar->nomor_kamar} ({$booking->nama_pemesan}).");
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'booked') {
            return back()->with('error', 'Hanya booking berstatus aktif yang dapat dibatalkan.');
        }

        $kamarNomor = $booking->kamar->nomor_kamar ?? '';
        $namaPemesan = $booking->nama_pemesan;

        $booking->update(['status' => 'batal']);

        return back()->with('success', "Pemesanan Kamar {$kamarNomor} atas nama {$namaPemesan} telah berhasil dibatalkan.");
    }

    public function destroy(Booking $booking)
    {
        $kamar = $booking->kamar;

        // Validasi hak akses gedung untuk resepsionis
        $assignedGedungId = auth()->user()->assignedGedungId();
        if ($assignedGedungId && $kamar && $kamar->gedung_id != $assignedGedungId) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus reservasi di asrama ini.');
        }

        $nomorKamar = $kamar->nomor_kamar ?? '-';
        $namaPemesan = $booking->nama_pemesan;

        $booking->delete();

        return back()->with('success', "Data reservasi kamar {$nomorKamar} atas nama {$namaPemesan} berhasil dihapus.");
    }

    public function destroyTransaksi(TransaksiAsrama $transaksi)
    {
        $kamar = $transaksi->kamar;

        // Validasi hak akses gedung untuk resepsionis
        $assignedGedungId = auth()->user()->assignedGedungId();
        if ($assignedGedungId && $kamar && $kamar->gedung_id != $assignedGedungId) {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk menghapus data hunian di asrama ini.');
        }

        $nomorKamar = $kamar->nomor_kamar ?? '-';
        $namaPeserta = $transaksi->peserta->nama_peserta ?? 'Tamu';

        DB::transaction(function () use ($transaksi, $kamar) {
            $transaksi->delete();

            // Perbarui status kamar jika tidak ada lagi transaksi yang menginap
            if ($kamar) {
                $remainingOccupants = $kamar->transaksi()
                    ->where('status', 'menginap')
                    ->count();

                if ($remainingOccupants === 0 && $kamar->status !== 'rusak') {
                    $kamar->update(['status' => 'kosong']);
                }
            }
        });

        return back()->with('success', "Data hunian kamar {$nomorKamar} atas nama {$namaPeserta} berhasil dihapus.");
    }
}
