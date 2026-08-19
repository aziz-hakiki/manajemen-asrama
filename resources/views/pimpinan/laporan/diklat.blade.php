<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Laporan Asrama Per Diklat</h1>
                <p class="text-xs text-slate-500 font-medium">Rekapitulasi alokasi asrama berdasarkan kegiatan program diklat</p>
            </div>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak / Print</span>
            </button>
        </div>
    </x-slot>

    <x-alert />

    @php
        $isAdmin = auth()->user()->role === 'admin';
        $routeHunian = $isAdmin ? route('admin.laporan.index') : route('pimpinan.laporan.hunian');
        $routeGedung = $isAdmin ? route('admin.laporan.gedung') : route('pimpinan.laporan.gedung');
        $routeDiklat = $isAdmin ? route('admin.laporan.diklat') : route('pimpinan.laporan.diklat');
    @endphp

    <!-- Sub Navigation Tabs for Reports -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-1">
        <a href="{{ $routeHunian }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('*laporan.index') || request()->routeIs('*laporan.hunian') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <span>📊 Laporan Hunian</span>
        </a>
        <a href="{{ $routeGedung }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('*laporan.gedung') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <span>🏢 Laporan Per Gedung</span>
        </a>
        <a href="{{ $routeDiklat }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('*laporan.diklat') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
            <span>🎓 Laporan Per Diklat</span>
        </a>
    </div>

    <!-- Rekapitulasi Program Diklat Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">Daftar Program Diklat & Alokasi Hunian Asrama</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ringkasan kepesertaan dan fasilitas asrama per agenda kegiatan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Program Diklat</th>
                        <th class="px-6 py-4">Periode Pelaksanaan</th>
                        <th class="px-6 py-4 text-center">Total Peserta</th>
                        <th class="px-6 py-4 text-center">Sedang Menginap</th>
                        <th class="px-6 py-4 text-center">Selesai Menginap</th>
                        <th class="px-6 py-4 text-center">Belum Check-in</th>
                        <th class="px-6 py-4 text-right">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($diklats as $diklat)
                        @php
                            $total = $diklat->pesertas_count ?? 0;
                            $menginap = $diklat->pesertas_menginap_count ?? 0;
                            $selesai = $diklat->pesertas_selesai_count ?? 0;
                            $belum = max(0, $total - $menginap - $selesai);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ (request('diklat_id') == $diklat->id) ? 'bg-indigo-50/40' : '' }}">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $diklat->nama_diklat }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($diklat->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($diklat->tanggal_selesai)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-800">
                                {{ $total }} Orang
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                    {{ $menginap }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                    {{ $selesai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                    {{ $belum }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ $routeDiklat }}?diklat_id={{ $diklat->id }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    <span>Lihat Peserta</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada agenda diklat yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Rincian Peserta Diklat Terpilih -->
    @if($selectedDiklat)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Daftar Peserta & Kamar: {{ $selectedDiklat->nama_diklat }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Rincian status menginap masing-masing peserta diklat</p>
                </div>
                <a href="{{ $routeDiklat }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">
                    &times; Tutup Rincian
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Peserta</th>
                            <th class="px-6 py-4">NIP / NIK</th>
                            <th class="px-6 py-4">Instansi</th>
                            <th class="px-6 py-4">Alokasi Kamar</th>
                            <th class="px-6 py-4">Check-in</th>
                            <th class="px-6 py-4">Status Menginap</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pesertaList as $idx => $peserta)
                            @php
                                $activeTransaksi = $peserta->transaksi->first();
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-400">
                                    {{ $idx + 1 }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $peserta->nama_peserta }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-600">
                                    {{ $peserta->nip_nik ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    {{ $peserta->instansi ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($activeTransaksi && $activeTransaksi->kamar)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs">
                                            Kamar {{ $activeTransaksi->kamar->nomor_kamar }} ({{ $activeTransaksi->kamar->gedung->nama_gedung ?? '' }})
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    {{ ($activeTransaksi && $activeTransaksi->tanggal_masuk) ? \Carbon\Carbon::parse($activeTransaksi->tanggal_masuk)->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($activeTransaksi)
                                        @if($activeTransaksi->status === 'menginap')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                Sedang Menginap
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                                Selesai
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                            Belum Check-in
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada peserta yang terdaftar pada kegiatan diklat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
