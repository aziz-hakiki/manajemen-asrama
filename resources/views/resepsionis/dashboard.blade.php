<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Resepsionis Dashboard</h1>
            <p class="text-xs text-slate-500 font-medium">Layanan operasional Check-in, Check-out, dan Penghuni Asrama PPSDMAP</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Welcome Banner Card -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 p-6 sm:p-8 text-white shadow-sm">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-amber-300"></span>
                    <span>Role Resepsionis</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Selamat bertugas, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-emerald-100 text-sm sm:text-base leading-relaxed">
                    Siap melayani kedatangan peserta diklat baru, kepulangan peserta, dan pemantauan kamar kosong siap huni.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ asset('docs/panduan-pengguna-resepsionis.pdf') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-white text-emerald-800 font-semibold text-sm hover:bg-emerald-50 shadow-sm transition-colors" title="Buka Panduan Pengguna Resepsionis (PDF)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Panduan Pengguna</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Check-in Hari Ini -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Check-in Hari Ini</span>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $checkinHariIni ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Orang</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Kedatangan Hari Ini</span>
                <span class="font-semibold text-emerald-600">Masuk</span>
            </div>
        </div>

        <!-- Card 2: Check-out Hari Ini -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Check-out Hari Ini</span>
                <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $checkoutHariIni ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Orang</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Kepulangan Hari Ini</span>
                <span class="font-semibold text-rose-600">Selesai</span>
            </div>
        </div>

        <!-- Card 3: Kamar Kosong -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kamar Kosong</span>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalKosong ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Kamar</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Siap Ditempati</span>
                <a href="{{ route('resepsionis.kamar-kosong.index') }}" class="font-semibold text-blue-600 hover:underline">Lihat Kamar &rarr;</a>
            </div>
        </div>

        <!-- Card 4: Penghuni Aktif -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Penghuni Aktif</span>
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $penghuniAktif ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Orang</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Sedang Menginap</span>
                <a href="{{ route('resepsionis.penghuni.index') }}" class="font-semibold text-purple-600 hover:underline">Lihat Daftar &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Secondary Grid: Status Kamar per Gedung & Pintasan Resepsionis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Status Kamar per Gedung -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Ketersediaan Kamar per Gedung</h3>
                    <p class="text-xs text-slate-500">Status ketersediaan kamar yang siap dialokasikan</p>
                </div>
                <a href="{{ route('resepsionis.kamar-kosong.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Daftar Lengkap &rarr;
                </a>
            </div>

            @if(isset($gedungs) && count($gedungs) > 0)
                <div class="space-y-4">
                    @foreach($gedungs as $gedung)
                        @php
                            $totalKamarGedung = $gedung->kamars_count ?? 0;
                            $terisiCount = $gedung->kamars_terisi_count ?? 0;
                            $kosongCount = $gedung->kamars_kosong_count ?? 0;
                            $occupancyRate = $totalKamarGedung > 0 ? round(($terisiCount / $totalKamarGedung) * 100) : 0;
                        @endphp
                        <div class="p-4 rounded-lg bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-md bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-xs">
                                        {{ substr($gedung->nama_gedung, -1) }}
                                    </div>
                                    <span class="font-bold text-slate-800 text-sm">{{ $gedung->nama_gedung }}</span>
                                </div>
                                <div class="text-xs font-medium text-slate-500">
                                    <span class="text-emerald-600 font-semibold">{{ $kosongCount }} Kosong</span>
                                    <span class="mx-1">•</span>
                                    <span class="text-rose-500 font-semibold">{{ $terisiCount }} Terisi</span>
                                    <span class="mx-1">•</span>
                                    <span>Total {{ $totalKamarGedung }} Kamar</span>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden flex">
                                <div class="bg-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $occupancyRate }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-1.5 text-[11px] text-slate-500">
                                <span>Tingkat Keterisian: {{ $occupancyRate }}%</span>
                                <span>{{ $kosongCount }} Kamar Tersedia</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-slate-400 text-sm">
                    Belum ada data gedung yang dibuat.
                </div>
            @endif
        </div>

        <!-- Right 1 Col: Quick Actions -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Pintasan Layanan</h3>
                    <span class="text-xs text-emerald-600 font-semibold">Resepsionis</span>
                </div>
                <p class="text-xs text-slate-500 mb-5">Akses cepat menu operasional meja resepsionis</p>

                <div class="space-y-3">
                    <a href="{{ route('resepsionis.checkin.create') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-emerald-700">Check-in Peserta</h4>
                                <p class="text-[11px] text-slate-400">Pilih peserta & kamar masuk</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('resepsionis.checkout.index') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-rose-300 hover:bg-rose-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-rose-700">Proses Check-out</h4>
                                <p class="text-[11px] text-slate-400">Pilih peserta yang keluar</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('resepsionis.peserta.create') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Tambah Peserta Baru</h4>
                                <p class="text-[11px] text-slate-400">Pendaftaran mandiri di lokasi</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Status Layanan:</span>
                <span class="font-bold text-emerald-600">Aktif Beroperasi</span>
            </div>
        </div>
    </div>
</x-app-layout>
