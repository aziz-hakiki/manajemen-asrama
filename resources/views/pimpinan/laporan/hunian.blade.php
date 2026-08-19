<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Laporan Tingkat Hunian</h1>
                <p class="text-xs text-slate-500 font-medium">Rekapitulasi riwayat dan transaksi hunian kamar asrama PPSDMAP</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak / Print</span>
                </button>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Export CSV</span>
                </a>
            </div>
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

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
        <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 items-end">
            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Pencarian</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Nama, NIP, Kamar, Diklat..." 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Dari Tanggal</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Sampai Tanggal</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Menginap</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all">
                    <option value="">Semua Status</option>
                    <option value="menginap" {{ request('status') === 'menginap' ? 'selected' : '' }}>Sedang Menginap</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Sudah Selesai</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-colors">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['search', 'start_date', 'end_date', 'status']))
                    <a href="{{ url()->current() }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm">Riwayat & Status Transaksi Hunian</h3>
            <span class="text-xs text-slate-500">Total Ditemukan: <strong>{{ $transaksis->total() }} Data</strong></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">Instansi</th>
                        <th class="px-6 py-4">Program Diklat</th>
                        <th class="px-6 py-4">Kamar & Gedung</th>
                        <th class="px-6 py-4">Check-in</th>
                        <th class="px-6 py-4">Check-out</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksis as $index => $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-400">
                                {{ $transaksis->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $row->peserta->nama_peserta ?? '-' }}</span>
                                    <span class="text-xs font-mono text-slate-400">{{ $row->peserta->nip_nik ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $row->peserta->instansi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $row->peserta->diklat->nama_diklat ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($row->kamar)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs">
                                        <span>Kamar {{ $row->kamar->nomor_kamar }}</span>
                                        <span class="text-indigo-400 font-normal">({{ $row->kamar->gedung->nama_gedung ?? '' }})</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $row->tanggal_masuk ? \Carbon\Carbon::parse($row->tanggal_masuk)->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $row->tanggal_keluar ? \Carbon\Carbon::parse($row->tanggal_keluar)->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($row->status === 'menginap')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Sedang Menginap
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data transaksi hunian yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transaksis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $transaksis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
