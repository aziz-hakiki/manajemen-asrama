<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Pos Keamanan & Satpam | Asrama PPSDMAP</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite & CDN Fallback for robust kiosk display) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }
            .print-clean {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                background-color: #ffffff !important;
                color: #0f172a !important;
            }
            .accordion-content {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-full flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Top Security Header Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 backdrop-blur-md shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <!-- Left: Logo & Station Title -->
            <div class="flex items-center gap-3.5">
                <div class="p-2 rounded-xl bg-slate-50 border border-slate-200 shadow-xs flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PPSDMAP" class="h-8 w-auto object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Pos Keamanan Aktif
                        </span>
                        <span class="text-xs text-slate-500 font-medium hidden sm:inline">• Portal Resmi Satpam</span>
                    </div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        MONITORING DIKLAT & ASRAMA
                    </h1>
                </div>
            </div>

            <!-- Right: Realtime Live Clock & Actions -->
            <div class="flex items-center justify-between md:justify-end gap-3 shrink-0">
                <div class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-1.5 text-right">
                    <div class="text-[11px] text-slate-500 font-medium">Waktu Pos Jaga</div>
                    <div id="live-clock" class="text-xs sm:text-sm font-bold text-indigo-700 font-mono tracking-wide">
                        Memuat waktu...
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

        <!-- Welcome & KPI Summary Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <!-- KPI 1: Diklat Berjalan Hari Ini -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden group">
                <div class="absolute right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-indigo-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Diklat Berjalan Hari Ini</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ $diklatBerjalanCount }}</span>
                    <span class="text-xs text-slate-500 font-medium">Program Diklat</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Status: Aktif</span>
                    <span class="text-indigo-600 font-semibold">{{ $today->translatedFormat('d M Y') }}</span>
                </div>
            </div>
            <!-- KPI 2: Total Peserta Diklat Aktif -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden group">
                <div class="absolute right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Peserta Diklat</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ $pesertaBerjalanCount }}</span>
                    <span class="text-xs text-slate-500 font-medium">Orang Terdaftar</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Peserta Diklat Aktif</span>
                    <span class="text-blue-600 font-semibold">Tercatat di Sistem</span>
                </div>
            </div>

            <!-- KPI 3: Peserta Menginap di Asrama -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs relative overflow-hidden group">
                <div class="absolute right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Menginap di Asrama</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-3xl sm:text-4xl font-extrabold text-emerald-600">{{ $penghuniMenginapCount }}</span>
                    <span class="text-xs text-slate-500 font-medium">Tamu / Penghuni</span>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-[11px] text-slate-500 flex items-center justify-between">
                    <span>Penghuni Aktif Saat Ini</span>
                    <span class="text-emerald-600 font-semibold">Check-in Aktif</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs no-print space-y-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Tab Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <a 
                        href="{{ route('security.index', ['tab' => 'berjalan', 'search' => $search]) }}" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $tab === 'berjalan' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200 border border-slate-200/60' }}"
                    >
                        <span class="w-2 h-2 rounded-full {{ $tab === 'berjalan' ? 'bg-emerald-300 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span>Sedang Berjalan Hari Ini</span>
                        <span class="px-1.5 py-0.5 rounded-md {{ $tab === 'berjalan' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }} text-[10px]">{{ $diklatBerjalanCount }}</span>
                    </a>

                    <a 
                        href="{{ route('security.index', ['tab' => 'semua', 'search' => $search]) }}" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'semua' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200 border border-slate-200/60' }}"
                    >
                        <span>Semua Diklat</span>
                    </a>

                    <a 
                        href="{{ route('security.index', ['tab' => 'mendatang', 'search' => $search]) }}" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'mendatang' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200 border border-slate-200/60' }}"
                    >
                        <span>Akan Datang</span>
                    </a>

                    <a 
                        href="{{ route('security.index', ['tab' => 'selesai', 'search' => $search]) }}" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $tab === 'selesai' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200 border border-slate-200/60' }}"
                    >
                        <span>Telah Selesai</span>
                    </a>
                </div>

                <!-- Search Input Form -->
                <form method="GET" action="{{ route('security.index') }}" class="flex items-center gap-2 max-w-md w-full">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search }}" 
                            placeholder="Cari nama peserta, NIP, instansi, atau diklat..." 
                            class="w-full pl-10 pr-4 py-2 text-xs bg-slate-50 text-slate-900 placeholder-slate-400 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:bg-white focus:border-indigo-500 outline-none transition-all shadow-inner"
                        >
                    </div>
                    @if($search)
                        <a 
                            href="{{ route('security.index', ['tab' => $tab]) }}" 
                            title="Reset Pencarian" 
                            class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 border border-slate-200 text-xs shrink-0"
                        >
                            ✕
                        </a>
                    @endif
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors shrink-0"
                    >
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Notification Bar if Searching -->
        @if($search)
            <div class="bg-indigo-50 border border-indigo-200 p-3.5 rounded-xl text-xs flex items-center justify-between text-indigo-900">
                <div class="flex items-center gap-2">
                    <span class="text-indigo-700 font-bold">Hasil Pencarian:</span>
                    <span class="text-slate-800">"{{ $search }}"</span>
                    <span class="text-slate-500">({{ $diklats->count() }} diklat ditemukan)</span>
                </div>
                <a href="{{ route('security.index', ['tab' => $tab]) }}" class="text-indigo-600 hover:underline font-semibold">Hapus Pencarian</a>
            </div>
        @endif

        <!-- List of Diklat Cards -->
        <div class="space-y-5">
            @forelse($diklats as $index => $diklat)
                @php
                    $mulai = $diklat->tanggal_mulai ? \Carbon\Carbon::parse($diklat->tanggal_mulai) : null;
                    $selesai = $diklat->tanggal_selesai ? \Carbon\Carbon::parse($diklat->tanggal_selesai) : null;
                    $isOngoing = $mulai && $selesai && $mulai->lte($today) && $selesai->gte($today);
                    $isUpcoming = $mulai && $mulai->gt($today);
                    $isPast = $selesai && $selesai->lt($today);
                    $durasiHari = ($mulai && $selesai) ? $mulai->diffInDays($selesai) + 1 : 0;
                @endphp

                <div class="bg-white rounded-2xl border {{ $isOngoing ? 'border-indigo-300 ring-2 ring-indigo-500/10 shadow-sm' : 'border-slate-200 shadow-xs' }} overflow-hidden transition-all">
                    <!-- Diklat Card Header -->
                    <div class="p-5 sm:p-6 {{ $isOngoing ? 'bg-gradient-to-r from-indigo-50/40 via-white to-slate-50/60' : 'bg-white' }}">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($isOngoing)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Sedang Berjalan Hari Ini
                                        </span>
                                    @elseif($isUpcoming)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Akan Datang (Mulai {{ $mulai ? $mulai->translatedFormat('d M Y') : '-' }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            Telah Selesai
                                        </span>
                                    @endif

                                    <span class="text-xs text-slate-400 font-mono">ID: #{{ $diklat->id }}</span>
                                </div>

                                <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                                    {{ $diklat->nama_diklat }}
                                </h2>

                                <!-- Date details -->
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $mulai ? $mulai->translatedFormat('d F Y') : '-' }} s/d {{ $selesai ? $selesai->translatedFormat('d F Y') : '-' }}
                                    </span>

                                    <span class="text-slate-300">•</span>
                                    <span>Durasi: <strong class="text-slate-800">{{ $durasiHari }} Hari</strong></span>
                                </div>
                            </div>

                            <!-- Right Stats & Toggle Button -->
                            <div class="flex flex-wrap items-center gap-3">
                                <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-200 text-center">
                                    <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Total Peserta</div>
                                    <div class="text-base sm:text-lg font-black text-slate-900">{{ $diklat->pesertas_count }} <span class="text-xs font-medium text-slate-500">Orang</span></div>
                                </div>

                                <div class="bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-200 text-center">
                                    <div class="text-[10px] text-emerald-800 font-bold uppercase tracking-wider">Menginap</div>
                                    <div class="text-base sm:text-lg font-black text-emerald-600">{{ $diklat->pesertas_menginap_count ?? 0 }} <span class="text-xs font-medium text-emerald-700">Orang</span></div>
                                </div>

                                <!-- Accordion Toggle Button -->
                                <button 
                                    type="button" 
                                    onclick="toggleAccordion('diklat-detail-{{ $diklat->id }}', this)" 
                                    class="no-print px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs flex items-center gap-2 transition-all shadow-xs"
                                >
                                    <span>Rincian Peserta</span>
                                    <svg class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Accordion Collapsible Detail Table (Open by default if ongoing, or collapsible) -->
                    <div id="diklat-detail-{{ $diklat->id }}" class="accordion-content border-t border-slate-200 bg-slate-50/60 {{ $isOngoing ? 'block' : 'hidden' }}">
                        <div class="p-4 sm:p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <span>Daftar Peserta Diklat ({{ $diklat->pesertas->count() }} Terdaftar)</span>
                                </h3>
                                <span class="text-[11px] text-slate-500">Gunakan untuk verifikasi tamu di pos jaga satpam</span>
                            </div>

                            @if($diklat->pesertas->count() > 0)
                                <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-xs">
                                    <table class="w-full text-left text-xs text-slate-700">
                                        <thead class="bg-slate-100/90 text-[11px] font-bold text-slate-600 uppercase tracking-wider border-b border-slate-200">
                                            <tr>
                                                <th class="px-3.5 py-3 w-12 text-center">No</th>
                                                <th class="px-4 py-3">Nama Lengkap</th>
                                                <th class="px-4 py-3">NIP / NIK</th>
                                                <th class="px-4 py-3">Instansi / Unit</th>
                                                <th class="px-4 py-3">Keterangan</th>
                                                <th class="px-4 py-3">Lokasi Kamar Asrama</th>
                                                <th class="px-4 py-3 text-center">Status Asrama</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 font-medium">
                                            @foreach($diklat->pesertas as $pIndex => $peserta)
                                                @php
                                                    $activeTx = $peserta->transaksi->firstWhere('status', 'menginap');
                                                    $latestTx = $peserta->transaksi->first();
                                                @endphp
                                                <tr class="hover:bg-slate-50/80 transition-colors">
                                                    <td class="px-3.5 py-3 text-center text-slate-400 font-mono">{{ $pIndex + 1 }}</td>
                                                    <td class="px-4 py-3 font-bold text-slate-900">
                                                        <div class="flex items-center gap-2">
                                                            <span>{{ $peserta->nama_peserta }}</span>
                                                            @if($peserta->jenis_kelamin)
                                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200/60 font-normal">
                                                                    {{ $peserta->jenis_kelamin }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 font-mono text-slate-500">{{ $peserta->nip_nik ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-slate-700">{{ $peserta->instansi ?? '-' }}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/60">
                                                            {{ $peserta->keterangan ?? 'Peserta' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        @if($activeTx && $activeTx->kamar)
                                                            <div class="flex items-center gap-1.5 text-emerald-700 font-bold">
                                                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                                </svg>
                                                                <span>{{ $activeTx->kamar->gedung->nama_gedung ?? 'Gedung' }} - Kamar {{ $activeTx->kamar->nomor_kamar }}</span>
                                                            </div>
                                                        @elseif($latestTx && $latestTx->kamar)
                                                            <div class="text-slate-500 text-[11px]">
                                                                Pernah di {{ $latestTx->kamar->gedung->nama_gedung ?? '' }} (Kamar {{ $latestTx->kamar->nomor_kamar }})
                                                            </div>
                                                        @else
                                                            <span class="text-slate-400 text-[11px] italic">Tidak Menginap</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        @if($activeTx)
                                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                Menginap
                                                            </span>
                                                        @elseif($latestTx && $latestTx->status === 'selesai')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                                Check-out
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-50 text-slate-400 border border-slate-200/50">
                                                                Belum Masuk
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-6 text-center text-slate-500 text-xs bg-white rounded-xl border border-slate-200">
                                    Belum ada data peserta yang terdaftar pada diklat ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-4 shadow-xs">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900">Tidak Ada Agenda Diklat yang Ditemukan</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">
                            @if($tab === 'berjalan')
                                Saat ini tidak ada program diklat yang sedang berlangsung pada tanggal {{ $today->translatedFormat('d F Y') }}.
                            @else
                                Tidak ada data yang sesuai dengan kriteria pencarian atau filter yang dipilih.
                            @endif
                        </p>
                    </div>
                    <div class="pt-2">
                        <a 
                            href="{{ route('security.index', ['tab' => 'semua']) }}" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition-colors shadow-xs"
                        >
                            Lihat Semua Diklat &rarr;
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Portal Resmi Monitoring Pos Keamanan & Satpam — PPSDM Aparatur Perhubungan</span>
            </div>
        </div>
    </footer>

    <!-- Scripts: Digital Clock & Collapsible Script -->
    <script>
        // Live Clock Script
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            if (!clockEl) return;

            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayName = days[now.getDay()];
            const date = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            clockEl.textContent = `${dayName}, ${date} ${monthName} ${year} • ${hours}:${minutes}:${seconds} WIB`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        // Accordion Toggle
        function toggleAccordion(id, btn) {
            const panel = document.getElementById(id);
            if (!panel) return;

            const isHidden = panel.classList.contains('hidden');
            const icon = btn.querySelector('svg');

            if (isHidden) {
                panel.classList.remove('hidden');
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                panel.classList.add('hidden');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }

        // Auto-refresh page every 2 minutes (120 seconds) for real-time security monitor
        setTimeout(function () {
            // Only auto refresh if user is not actively typing in search
            const activeInput = document.activeElement;
            if (!activeInput || activeInput.tagName !== 'INPUT') {
                window.location.reload();
            }
        }, 120000);
    </script>
</body>
</html>
