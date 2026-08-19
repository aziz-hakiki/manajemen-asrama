<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\Diklat;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use App\Models\User;

// Admin Controllers
use App\Http\Controllers\Admin\GedungController;
use App\Http\Controllers\Admin\KamarController;
use App\Http\Controllers\Admin\DiklatController;
use App\Http\Controllers\Admin\PesertaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Pimpinan\LaporanController;

// Resepsionis Controllers
use App\Http\Controllers\Resepsionis\CheckInController;
use App\Http\Controllers\Resepsionis\CheckOutController;
use App\Http\Controllers\Resepsionis\KamarKosongController;
use App\Http\Controllers\Resepsionis\PenghuniAktifController;
use App\Http\Controllers\Resepsionis\PesertaController as ResepsionisPesertaController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'resepsionis') return redirect()->route('resepsionis.dashboard');
    if ($role === 'pimpinan') return redirect()->route('pimpinan.dashboard');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalGedung = Gedung::count();
        $totalKamar = Kamar::count();
        $kamarKosong = Kamar::where('status', 'kosong')->count();
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $totalDiklat = Diklat::count();
        $totalPeserta = Peserta::count();
        $penghuniAktif = TransaksiAsrama::where('status', 'menginap')->count();
        $totalUser = User::count();

        $gedungList = Gedung::withCount(['kamars', 'kamars as kamars_kosong_count' => function ($q) {
            $q->where('status', 'kosong');
        }, 'kamars as kamars_terisi_count' => function ($q) {
            $q->where('status', 'terisi');
        }])->get();

        return view('admin.dashboard', compact(
            'totalGedung',
            'totalKamar',
            'kamarKosong',
            'kamarTerisi',
            'totalDiklat',
            'totalPeserta',
            'penghuniAktif',
            'totalUser',
            'gedungList'
        ));
    })->name('dashboard');

    // Master Data Resource Routes
    Route::resource('gedung', GedungController::class);
    Route::resource('kamar', KamarController::class);
    Route::resource('diklat', DiklatController::class);
    
    // Import Peserta Routes
    Route::get('peserta/import', [PesertaController::class, 'importForm'])->name('peserta.import');
    Route::post('peserta/import', [PesertaController::class, 'import'])->name('peserta.import.process');
    Route::resource('peserta', PesertaController::class)->parameters(['peserta' => 'peserta']);

    // User Management
    Route::resource('user', UserController::class);

    // Laporan untuk Admin
    Route::get('laporan', [LaporanController::class, 'laporanHunian'])->name('laporan.index');
    Route::get('laporan-gedung', [LaporanController::class, 'laporanPerGedung'])->name('laporan.gedung');
    Route::get('laporan-diklat', [LaporanController::class, 'laporanPerDiklat'])->name('laporan.diklat');
});

// Resepsionis Routes
Route::middleware(['auth', 'role:resepsionis'])->prefix('resepsionis')->name('resepsionis.')->group(function () {
    Route::get('/dashboard', function () {
        $checkinHariIni = TransaksiAsrama::whereDate('tanggal_masuk', today())->count();
        $checkoutHariIni = TransaksiAsrama::whereDate('tanggal_keluar', today())->count();
        $totalKosong = Kamar::where('status', 'kosong')->count();
        $penghuniAktif = TransaksiAsrama::where('status', 'menginap')->count();
        
        $gedungs = Gedung::withCount(['kamars', 'kamars as kamars_kosong_count' => function ($q) {
            $q->where('status', 'kosong');
        }, 'kamars as kamars_terisi_count' => function ($q) {
            $q->where('status', 'terisi');
        }])->get();

        $transaksiTerbaru = TransaksiAsrama::with(['peserta.diklat', 'kamar.gedung'])
            ->latest('tanggal_masuk')
            ->take(5)
            ->get();

        return view('resepsionis.dashboard', compact(
            'checkinHariIni',
            'checkoutHariIni',
            'totalKosong',
            'penghuniAktif',
            'gedungs',
            'transaksiTerbaru'
        ));
    })->name('dashboard');

    // Check-in
    Route::get('checkin', [CheckInController::class, 'create'])->name('checkin.create');
    Route::post('checkin', [CheckInController::class, 'store'])->name('checkin.store');

    // Check-out
    Route::get('checkout', [CheckOutController::class, 'index'])->name('checkout.index');
    Route::post('checkout/{transaksi}', [CheckOutController::class, 'process'])->name('checkout.process');

    // Kamar Kosong
    Route::get('kamar-kosong', [KamarKosongController::class, 'index'])->name('kamar-kosong.index');

    // Penghuni Aktif
    Route::get('penghuni-aktif', [PenghuniAktifController::class, 'index'])->name('penghuni.index');

    // Tambah Peserta Manual
    Route::get('tambah-peserta', [ResepsionisPesertaController::class, 'create'])->name('peserta.create');
    Route::post('tambah-peserta', [ResepsionisPesertaController::class, 'store'])->name('peserta.store');
});

// Pimpinan Routes
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', [LaporanController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan-hunian', [LaporanController::class, 'laporanHunian'])->name('laporan.hunian');
    Route::get('/laporan-gedung', [LaporanController::class, 'laporanPerGedung'])->name('laporan.gedung');
    Route::get('/laporan-diklat', [LaporanController::class, 'laporanPerDiklat'])->name('laporan.diklat');
});

require __DIR__.'/auth.php';
