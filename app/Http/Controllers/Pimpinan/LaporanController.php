<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\Diklat;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalKamar = Kamar::count();
        $kamarKosong = Kamar::where('status', '!=', 'rusak')->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0')->count();
        $kamarTerisi = Kamar::whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") > 0')->count();
        $penghuniAktif = TransaksiAsrama::where('status', 'menginap')->count();
        $totalDiklat = Diklat::count();
        $totalPeserta = Peserta::count();

        $tingkatHunian = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;

        $gedungs = Gedung::withCount(['kamars', 'kamars as kamars_kosong_count' => function ($q) {
            $q->where('status', '!=', 'rusak')
              ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0');
        }, 'kamars as kamars_terisi_count' => function ($q) {
            $q->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") > 0');
        }])->get();

        $transaksiTerbaru = TransaksiAsrama::with(['peserta.diklat', 'kamar.gedung'])
            ->latest('tanggal_masuk')
            ->take(5)
            ->get();

        // Periode Tahun untuk Diagram Kombinasi 12 Bulan (Januari - Desember)
        $availableYears = range(2026, 2030);
        $currentYear = now()->year;
        $defaultYear = in_array($currentYear, $availableYears) ? $currentYear : 2026;
        $selectedPeriode = $request->get('periode', (string) $defaultYear);
        if (!in_array((int) $selectedPeriode, $availableYears)) {
            $selectedPeriode = (string) $defaultYear;
        }
        $year = (int) $selectedPeriode;

        $chartLabels = [];
        $chartFullLabels = [];
        $chartKamarTerpakai = [];
        $chartPersentase = [];
        $chartTransaksi = [];

        for ($month = 1; $month <= 12; $month++) {
            $dt = Carbon::createFromDate($year, $month, 1);
            $startOfMonth = $dt->copy()->startOfMonth();
            $endOfMonth = $dt->copy()->endOfMonth();

            $terpakai = TransaksiAsrama::where('tanggal_masuk', '<=', $endOfMonth)
                ->where(function ($q) use ($startOfMonth) {
                    $q->whereNull('tanggal_keluar')->orWhere('tanggal_keluar', '>=', $startOfMonth);
                })
                ->distinct('kamar_id')
                ->count('kamar_id');

            $transaksiCount = TransaksiAsrama::where('tanggal_masuk', '<=', $endOfMonth)
                ->where(function ($q) use ($startOfMonth) {
                    $q->whereNull('tanggal_keluar')->orWhere('tanggal_keluar', '>=', $startOfMonth);
                })
                ->count();

            $rate = $totalKamar > 0 ? round(($terpakai / $totalKamar) * 100, 1) : 0;

            $chartLabels[] = $dt->translatedFormat('F');
            $chartFullLabels[] = $dt->translatedFormat('F Y');
            $chartKamarTerpakai[] = $terpakai;
            $chartPersentase[] = $rate;
            $chartTransaksi[] = $transaksiCount;
        }

        $monthsToConsider = ($year === $currentYear) ? min(12, max(1, (int) now()->month)) : 12;
        $terpakaiSlice = array_slice($chartKamarTerpakai, 0, $monthsToConsider);
        $rataRataTerpakai = count($terpakaiSlice) > 0 ? round(array_sum($terpakaiSlice) / count($terpakaiSlice), 1) : 0;

        $maxTerpakai = count($chartKamarTerpakai) > 0 ? max($chartKamarTerpakai) : 0;
        $maxIndex = array_search($maxTerpakai, $chartKamarTerpakai);
        $bulanPuncak = ($maxTerpakai > 0 && $maxIndex !== false) ? $chartFullLabels[$maxIndex] : '-';

        return view('pimpinan.dashboard', compact(
            'totalKamar',
            'kamarKosong',
            'kamarTerisi',
            'penghuniAktif',
            'totalDiklat',
            'totalPeserta',
            'tingkatHunian',
            'gedungs',
            'transaksiTerbaru',
            'chartLabels',
            'chartFullLabels',
            'chartKamarTerpakai',
            'chartPersentase',
            'chartTransaksi',
            'selectedPeriode',
            'availableYears',
            'rataRataTerpakai',
            'bulanPuncak'
        ));
    }

    public function laporanHunian(Request $request)
    {
        $query = TransaksiAsrama::with(['peserta.diklat', 'kamar.gedung']);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_masuk', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_masuk', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('peserta', function ($q) use ($search) {
                    $q->where('nama_peserta', 'like', "%{$search}%")
                      ->orWhere('nip_nik', 'like', "%{$search}%")
                      ->orWhere('instansi', 'like', "%{$search}%")
                      ->orWhereHas('diklat', function ($d) use ($search) {
                          $d->where('nama_diklat', 'like', "%{$search}%");
                      });
                })->orWhereHas('kamar', function ($q) use ($search) {
                    $q->where('nomor_kamar', 'like', "%{$search}%")
                      ->orWhereHas('gedung', function ($g) use ($search) {
                          $g->where('nama_gedung', 'like', "%{$search}%");
                      });
                });
            });
        }

        // Export CSV jika diminta
        if ($request->get('export') === 'csv') {
            return $this->exportHunianCsv($query->latest('tanggal_masuk')->get());
        }

        $transaksis = $query->latest('tanggal_masuk')->paginate(15)->withQueryString();

        $totalKamar = Kamar::count();
        $kamarTerisi = Kamar::whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") > 0')->count();
        $penghuniAktif = TransaksiAsrama::where('status', 'menginap')->count();
        $totalSelesai = TransaksiAsrama::where('status', 'selesai')->count();

        return view('pimpinan.laporan.hunian', compact(
            'transaksis',
            'totalKamar',
            'kamarTerisi',
            'penghuniAktif',
            'totalSelesai'
        ));
    }

    public function laporanPerGedung(Request $request)
    {
        $gedungs = Gedung::withCount(['kamars', 'kamars as kamars_kosong_count' => function ($q) {
            $q->where('status', '!=', 'rusak')
              ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") = 0');
        }, 'kamars as kamars_terisi_count' => function ($q) {
            $q->where('status', '!=', 'rusak')
              ->whereRaw('(SELECT COUNT(*) FROM transaksi_asramas WHERE transaksi_asramas.kamar_id = kamars.id AND transaksi_asramas.status = "menginap") > 0');
        }, 'kamars as kamars_rusak_count' => function ($q) {
            $q->where('status', 'rusak');
        }])->withSum('kamars as total_kapasitas', 'kapasitas')->get();

        $selectedGedung = null;
        $kamarsGedung = collect();

        if ($request->filled('gedung_id')) {
            $selectedGedung = Gedung::find($request->gedung_id);
            if ($selectedGedung) {
                $kamarsGedung = Kamar::where('gedung_id', $selectedGedung->id)
                    ->with(['transaksi' => function ($q) {
                        $q->where('status', 'menginap')->with('peserta.diklat');
                    }])
                    ->orderBy('nomor_kamar')
                    ->get();
            }
        }

        return view('pimpinan.laporan.gedung', compact('gedungs', 'selectedGedung', 'kamarsGedung'));
    }

    public function laporanPerDiklat(Request $request)
    {
        $diklats = Diklat::withCount([
            'pesertas',
            'pesertas as pesertas_menginap_count' => function ($q) {
                $q->whereHas('transaksi', fn ($t) => $t->where('status', 'menginap'));
            },
            'pesertas as pesertas_selesai_count' => function ($q) {
                $q->whereHas('transaksi', fn ($t) => $t->where('status', 'selesai'))
                  ->whereDoesntHave('transaksi', fn ($t) => $t->where('status', 'menginap'));
            },
        ])->latest()->get();

        $selectedDiklat = null;
        $pesertaList = collect();

        if ($request->filled('diklat_id')) {
            $selectedDiklat = Diklat::find($request->diklat_id);
            if ($selectedDiklat) {
                $pesertaList = Peserta::where('diklat_id', $selectedDiklat->id)
                    ->with(['transaksi' => function ($q) {
                        $q->with('kamar.gedung')->latest('tanggal_masuk');
                    }])
                    ->orderBy('nama_peserta')
                    ->get();
            }
        }

        return view('pimpinan.laporan.diklat', compact('diklats', 'selectedDiklat', 'pesertaList'));
    }

    private function exportHunianCsv($data)
    {
        $filename = 'laporan-hunian-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Nama Peserta', 'NIP/NIK', 'Instansi', 'Program Diklat', 'Gedung', 'Nomor Kamar', 'Tanggal Masuk', 'Tanggal Keluar', 'Status']);

            foreach ($data as $index => $row) {
                fputcsv($file, [
                    $index + 1,
                    $row->peserta->nama_peserta ?? '-',
                    $row->peserta->nip_nik ?? '-',
                    $row->peserta->instansi ?? '-',
                    $row->peserta->diklat->nama_diklat ?? '-',
                    $row->kamar->gedung->nama_gedung ?? '-',
                    $row->kamar->nomor_kamar ?? '-',
                    $row->tanggal_masuk ? date('Y-m-d H:i', strtotime($row->tanggal_masuk)) : '-',
                    $row->tanggal_keluar ? date('Y-m-d H:i', strtotime($row->tanggal_keluar)) : '-',
                    ucfirst($row->status),
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
