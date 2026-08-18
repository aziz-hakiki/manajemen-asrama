<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Gedung;
use App\Models\Kamar;
use App\Models\Diklat;
use App\Models\Peserta;
use App\Models\TransaksiAsrama;
use App\Models\User;

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
});

// Resepsionis Routes
Route::middleware(['auth', 'role:resepsionis'])->prefix('resepsionis')->name('resepsionis.')->group(function () {
    Route::get('/dashboard', function () {
        return view('resepsionis.dashboard');
    })->name('dashboard');
});

// Pimpinan Routes
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pimpinan.dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
