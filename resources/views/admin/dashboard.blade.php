<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Admin Dashboard</h1>
            <p class="text-xs text-slate-500 font-medium">Ringkasan statistik dan manajemen operasional Asrama PPSDMAP</p>
        </div>
    </x-slot>

    <!-- Welcome Banner Card -->
    <div class="relative overflow-hidden rounded-xl bg-indigo-600 p-6 sm:p-8 text-white shadow-sm">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Role Administrator</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Selamat datang, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-indigo-100 text-sm sm:text-base leading-relaxed">
                    Kelola data master gedung, kamar, agenda diklat, data peserta, hingga akun pengguna asrama dengan cepat dan terstruktur.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ asset('docs/panduan-pengguna-admin.pdf') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-white text-indigo-700 font-semibold text-sm hover:bg-indigo-50 shadow-sm transition-colors" title="Buka Panduan Pengguna Admin (PDF)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Panduan Pengguna</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div id="quick-stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Gedung -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Gedung</span>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalGedung ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Unit Gedung</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Siap Digunakan</span>
                <span class="font-semibold text-blue-600">Master Data</span>
            </div>
        </div>

        <!-- Card 2: Total Kamar -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Kamar</span>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalKamar ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Kamar</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-emerald-600 font-medium">Kosong: {{ $kamarKosong ?? 0 }}</span>
                <span class="text-rose-500 font-medium">Terisi: {{ $kamarTerisi ?? 0 }}</span>
            </div>
        </div>

        <!-- Card 3: Total Diklat -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Diklat</span>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalDiklat ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Kegiatan</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Agenda Diklat</span>
                <span class="font-semibold text-amber-600">Aktif & Selesai</span>
            </div>
        </div>

        <!-- Card 4: Total Peserta -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Peserta</span>
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalPeserta ?? 0 }}</span>
                <span class="text-xs text-slate-500 font-medium">Orang</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Penghuni Aktif:</span>
                <span class="font-semibold text-purple-600">{{ $penghuniAktif ?? 0 }} Orang</span>
            </div>
        </div>
    </div>

    <!-- Secondary Grid: Status Hunian per Gedung & Quick Action -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Status Kamar per Gedung -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Ketersediaan Kamar per Gedung</h3>
                    <p class="text-xs text-slate-500">Status kapasitas dan alokasi kamar saat ini</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                    {{ count($gedungList ?? []) }} Gedung Terdata
                </span>
            </div>

            @if(isset($gedungList) && count($gedungList) > 0)
                <div class="space-y-4">
                    @foreach($gedungList as $gedung)
                        @php
                            $totalKamarGedung = $gedung->kamars_count ?? 0;
                            $terisiCount = $gedung->kamars_terisi_count ?? 0;
                            $kosongCount = $gedung->kamars_kosong_count ?? 0;
                            $occupancyRate = $totalKamarGedung > 0 ? round(($terisiCount / $totalKamarGedung) * 100) : 0;
                        @endphp
                        <div class="p-4 rounded-lg bg-slate-50 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-md bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
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
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $occupancyRate }}%"></div>
                            </div>
                            <div class="flex justify-between items-center mt-1.5 text-[11px] text-slate-500">
                                <span>Tingkat Hunian: {{ $occupancyRate }}%</span>
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

        <!-- Right 1 Col: Quick Links / Pintasan Cepat -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Pintasan Cepat</h3>
                    <span class="text-xs text-indigo-600 font-semibold">Admin Actions</span>
                </div>
                <p class="text-xs text-slate-500 mb-5">Akses cepat menu pengelolaan master data asrama</p>

                <div class="space-y-3">
                    <a href="{{ route('admin.gedung.create') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Tambah Gedung</h4>
                                <p class="text-[11px] text-slate-400">Input unit asrama baru</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.kamar.create') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Tambah Kamar</h4>
                                <p class="text-[11px] text-slate-400">Atur nomor & kapasitas</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('admin.peserta.import') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Import Data Peserta</h4>
                                <p class="text-[11px] text-slate-400">Unggah dari file CSV</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Pengguna:</span>
                <span class="font-bold text-slate-700">{{ $totalUser ?? 0 }} Akun</span>
            </div>
        </div>
    </div>
</x-app-layout>
