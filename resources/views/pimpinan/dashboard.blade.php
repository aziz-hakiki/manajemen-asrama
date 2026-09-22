<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Pimpinan Dashboard</h1>
            <p class="text-xs text-slate-500 font-medium">Monitoring & Laporan Eksekutif Tingkat Hunian Asrama PPSDMAP</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Welcome Banner Card -->
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-blue-700 via-indigo-700 to-indigo-900 p-6 sm:p-8 text-white shadow-sm">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Role Pimpinan / Manajemen</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Selamat datang, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-indigo-100 text-sm sm:text-base leading-relaxed">
                    Pantau kinerja okupansi kamar, rekapitulasi data per gedung, dan laporan peserta diklat yang menggunakan fasilitas asrama secara menyeluruh.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ asset('docs/panduan-pengguna-pimpinan.pdf') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-white text-indigo-900 font-semibold text-sm hover:bg-indigo-50 shadow-sm transition-colors" title="Buka Panduan Pengguna Pimpinan (PDF)">
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
        <!-- Card 1: Tingkat Okupansi Keseluruhan -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat Okupansi</span>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $tingkatHunian }}%</span>
                <span class="text-xs text-slate-500 font-medium">Terisi</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>{{ $kamarTerisi }} dari {{ $totalKamar }} Kamar</span>
                <span class="font-semibold text-indigo-600">Okupansi</span>
            </div>
        </div>

        <!-- Card 2: Penghuni Aktif -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Penghuni Aktif</span>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $penghuniAktif }}</span>
                <span class="text-xs text-slate-500 font-medium">Orang</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Kamar Terisi: {{ $kamarTerisi }}</span>
                <span class="font-semibold text-emerald-600">Saat Ini</span>
            </div>
        </div>

        <!-- Card 3: Kamar Kosong -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kamar Kosong</span>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $kamarKosong }}</span>
                <span class="text-xs text-slate-500 font-medium">Kamar</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Kapasitas Tersedia</span>
                <a href="{{ route('pimpinan.kamar-kosong.index') }}" class="font-semibold text-blue-600 hover:underline">Lihat Kamar &rarr;</a>
            </div>
        </div>

        <!-- Card 4: Agenda Diklat -->
        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Agenda Diklat</span>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalDiklat }}</span>
                <span class="text-xs text-slate-500 font-medium">Kegiatan</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total {{ $totalPeserta }} Peserta</span>
                <a href="{{ route('pimpinan.laporan.diklat') }}" class="font-semibold text-amber-600 hover:underline">Detail &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Combo Chart: Tren Okupansi Kamar 12 Bulan -->
    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Tren Okupansi Kamar Asrama (Januari - Desember) {{ $selectedPeriode }} </h3>
                        <p class="text-xs text-slate-500">Diagram perbandingan 12 bulan: Batang (kamar terpakai per asrama) dan Garis (tingkat okupansi total)</p>
                    </div>
                </div>
            </div>

            <!-- Filter Periode & KPI Mini Badges -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 text-xs">
                    <span class="text-slate-500 font-medium">Rata-rata:</span>
                    <span class="font-bold text-indigo-600">{{ $rataRataTerpakai }} Kamar/bln</span>
                </div>
                <div class="hidden sm:flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 text-xs">
                    <span class="text-slate-500 font-medium">Bulan Puncak:</span>
                    <span class="font-bold text-emerald-600">{{ $bulanPuncak }}</span>
                </div>

                <!-- Dropdown Periode Filter -->
                <form method="GET" action="{{ route('pimpinan.dashboard') }}" class="flex items-center">
                    <label for="periode-select" class="sr-only">Pilih Tahun</label>
                    <select 
                        id="periode-select"
                        name="periode" 
                        onchange="this.form.submit()" 
                        class="text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-300 rounded-lg py-2 pl-3 pr-8 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 cursor-pointer shadow-2xs transition-colors"
                    >
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedPeriode == (string)$year ? 'selected' : '' }}> Jan - Des {{ $year }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    
        <!-- Legend & Indicators -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-4 pb-2 text-xs text-slate-600">
            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                @if(isset($chartGedungDatasets) && count($chartGedungDatasets) > 0)
                    @foreach($chartGedungDatasets as $ds)
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-xs inline-block shadow-2xs" style="background-color: {{ $ds['backgroundColor'] }}; border: 1px solid {{ $ds['borderColor'] }};"></span>
                            <span class="font-semibold text-slate-700">{{ $ds['label'] }}</span>
                            <span class="text-[10px] text-slate-400">({{ $ds['total_kamar'] }} kmr)</span>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center gap-2">
                        <span class="w-3.5 h-3.5 rounded-xs bg-indigo-600 inline-block shadow-2xs"></span>
                        <span class="font-semibold text-slate-700">Batang:</span>
                        <span class="text-slate-600">Kamar Terpakai (Unit)</span>
                    </div>
                @endif
                <div class="flex items-center gap-1.5 border-l border-slate-200 pl-3">
                    <span class="w-3.5 h-1 rounded-full bg-emerald-500 inline-block"></span>
                    <span class="font-semibold text-slate-700">Garis:</span>
                    <span class="text-slate-600">Okupansi Total (%)</span>
                </div>
            </div>
            <div class="text-[11px] text-slate-500">
                Kapasitas Keseluruhan: <strong class="text-slate-800 font-bold">{{ $totalKamar }} Kamar</strong>
            </div>
        </div>

        <!-- Chart Canvas Container -->
        <div class="relative w-full h-72 sm:h-80 md:h-96 mt-2">
            <canvas id="occupancyComboChart"></canvas>
        </div>
    </div>

    <!-- Secondary Grid: Status Per Gedung & Menu Laporan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Tingkat Hunian per Gedung -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Tingkat Hunian per Unit Gedung</h3>
                    <p class="text-xs text-slate-500">Keterisian kamar asrama di masing-masing gedung</p>
                </div>
                <a href="{{ route('pimpinan.laporan.gedung') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                    Lihat Rincian Gedung &rarr;
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
                                <span>Tingkat Hunian: <strong>{{ $occupancyRate }}%</strong></span>
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

        <!-- Right 1 Col: Quick Links Laporan -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-800 tracking-tight">Menu Laporan</h3>
                    <span class="text-xs text-indigo-600 font-semibold">Pimpinan</span>
                </div>
                <p class="text-xs text-slate-500 mb-5">Pilihan laporan analitik dan rekapitulasi data asrama</p>

                <div class="space-y-3">
                    <a href="{{ route('pimpinan.laporan.hunian') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Laporan Hunian</h4>
                                <p class="text-[11px] text-slate-400">Rekapitulasi transaksi menginap & filter tanggal</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('pimpinan.laporan.gedung') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Laporan Per Gedung</h4>
                                <p class="text-[11px] text-slate-400">Detail utilisasi per unit gedung</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('pimpinan.laporan.diklat') }}" class="flex items-center justify-between p-3 rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 group transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 group-hover:text-indigo-700">Laporan Per Diklat</h4>
                                <p class="text-[11px] text-slate-400">Riwayat alokasi asrama per kegiatan</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Update Terakhir:</span>
                <span class="font-semibold text-slate-700">{{ now()->format('H:i:s') }} WIB</span>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN & Combo Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('occupancyComboChart');
            if (!ctx || typeof Chart === 'undefined') return;

            const chartLabels = {!! json_encode($chartLabels) !!};
            const chartFullLabels = {!! json_encode($chartFullLabels) !!};
            const chartGedungDatasets = {!! json_encode($chartGedungDatasets ?? []) !!};
            const dataPersentase = {!! json_encode($chartPersentase) !!};
            const dataTransaksi = {!! json_encode($chartTransaksi) !!};
            const totalKamar = {{ (int) $totalKamar }};
            const maxKamarPerGedung = {{ (int) ($maxKamarPerGedung ?? 5) }};

            // Dataset Line Okupansi Keseluruhan
            const datasets = [
                {
                    type: 'line',
                    label: 'Okupansi Total (%)',
                    data: dataPersentase,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#059669',
                    tension: 0.35,
                    borderWidth: 3,
                    yAxisID: 'y1',
                    order: 1
                }
            ];

            // Masukkan dataset bar per masing-masing asrama
            if (chartGedungDatasets && chartGedungDatasets.length > 0) {
                chartGedungDatasets.forEach((gedung, idx) => {
                    datasets.push({
                        type: 'bar',
                        label: gedung.label,
                        totalKamarGedung: gedung.total_kamar,
                        data: gedung.data,
                        backgroundColor: gedung.backgroundColor,
                        hoverBackgroundColor: gedung.hoverBackgroundColor,
                        borderColor: gedung.borderColor,
                        borderRadius: 4,
                        borderSkipped: false,
                        maxBarThickness: 28,
                        yAxisID: 'y',
                        order: 2 + idx
                    });
                });
            } else {
                const dataKamarTerpakai = {!! json_encode($chartKamarTerpakai ?? []) !!};
                datasets.push({
                    type: 'bar',
                    label: 'Kamar Terpakai (Unit)',
                    totalKamarGedung: totalKamar,
                    data: dataKamarTerpakai,
                    backgroundColor: 'rgba(79, 70, 229, 0.85)',
                    hoverBackgroundColor: 'rgba(67, 56, 202, 1)',
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 40,
                    yAxisID: 'y',
                    order: 2
                });
            }

            new Chart(ctx, {
                data: {
                    labels: chartLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    animation: {
                        duration: 800,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 10,
                                boxHeight: 10,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 12,
                                color: '#475569',
                                font: {
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#f8fafc',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            titleFont: {
                                size: 13,
                                weight: '700'
                            },
                            bodyFont: {
                                size: 12
                            },
                            footerFont: {
                                size: 11,
                                weight: '600'
                            },
                            footerColor: '#cbd5e1',
                            callbacks: {
                                title: function (tooltipItems) {
                                    if (tooltipItems.length > 0) {
                                        const index = tooltipItems[0].dataIndex;
                                        return chartFullLabels[index] || chartLabels[index];
                                    }
                                    return '';
                                },
                                label: function (context) {
                                    if (context.dataset.type === 'line') {
                                        return ` ${context.dataset.label}: ${context.parsed.y}%`;
                                    } else {
                                        const totalKamarG = context.dataset.totalKamarGedung;
                                        if (totalKamarG > 0) {
                                            const pct = Math.round((context.parsed.y / totalKamarG) * 100);
                                            return ` ${context.dataset.label}: ${context.parsed.y} / ${totalKamarG} Kamar (${pct}%)`;
                                        }
                                        return ` ${context.dataset.label}: ${context.parsed.y} Kamar Terpakai`;
                                    }
                                },
                                footer: function (tooltipItems) {
                                    if (tooltipItems.length > 0) {
                                        const index = tooltipItems[0].dataIndex;
                                        const transaksi = dataTransaksi[index] || 0;
                                        return `Total Check-in/Tamu: ${transaksi} Orang`;
                                    }
                                    return '';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    size: 11,
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Terpakai per Asrama (Unit)',
                                color: '#4f46e5',
                                font: {
                                    weight: '600',
                                    size: 11
                                }
                            },
                            min: 0,
                            suggestedMax: Math.max(maxKamarPerGedung + 1, 5),
                            ticks: {
                                stepSize: 1,
                                precision: 0,
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(241, 245, 249, 1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Okupansi Total (%)',
                                color: '#10b981',
                                font: {
                                    weight: '600',
                                    size: 11
                                }
                            },
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 20,
                                callback: function (value) {
                                    return value + '%';
                                },
                                color: '#64748b',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
