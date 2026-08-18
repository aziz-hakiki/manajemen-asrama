<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Penghuni Aktif</h1>
            <p class="text-xs text-slate-500 font-medium">Monitoring peserta diklat yang sedang menginap di asrama saat ini</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Top Action & Filter Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Penghuni Aktif Asrama</h2>
            <p class="text-xs text-slate-500">Seluruh peserta yang tercatat sedang menempati kamar asrama</p>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-purple-50 text-purple-700 font-semibold text-xs border border-purple-200">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                <span>Total Penghuni: {{ $totalPenghuni }} Orang</span>
            </div>
            <a href="{{ route('resepsionis.checkin.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>+ Check-in Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('resepsionis.penghuni.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <!-- Search -->
            <div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, NIP, kamar..." 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>

            <!-- Filter Gedung -->
            <div>
                <select name="gedung_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all">
                    <option value="">Semua Gedung</option>
                    @foreach($gedungs as $g)
                        <option value="{{ $g->id }}" {{ request('gedung_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama_gedung }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Diklat -->
            <div>
                <select name="diklat_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all">
                    <option value="">Semua Diklat</option>
                    @foreach($diklats as $d)
                        <option value="{{ $d->id }}" {{ request('diklat_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_diklat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'gedung_id', 'diklat_id']))
                    <a href="{{ route('resepsionis.penghuni.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">Instansi</th>
                        <th class="px-6 py-4">Program Diklat</th>
                        <th class="px-6 py-4">Alokasi Kamar</th>
                        <th class="px-6 py-4">Waktu Check-in</th>
                        <th class="px-6 py-4">Lama Menginap</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penghunis as $index => $transaksi)
                        @php
                            $tglMasuk = \Carbon\Carbon::parse($transaksi->tanggal_masuk);
                            $durasi = $tglMasuk->diffForHumans(now(), [
                                'parts' => 2,
                                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE
                            ]);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-400">
                                {{ $penghunis->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $transaksi->peserta->nama_peserta ?? '-' }}</span>
                                    <span class="text-xs font-mono text-slate-400">{{ $transaksi->peserta->nip_nik ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $transaksi->peserta->instansi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $transaksi->peserta->diklat->nama_diklat ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($transaksi->kamar)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200/60">
                                        <span>Kamar {{ $transaksi->kamar->nomor_kamar }}</span>
                                        <span class="text-emerald-500 font-normal">({{ $transaksi->kamar->gedung->nama_gedung ?? '' }})</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $tglMasuk->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $durasi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('resepsionis.checkout.process', $transaksi) }}" method="POST" onsubmit="return confirm('Proses Check-out untuk {{ $transaksi->peserta->nama_peserta ?? 'peserta ini' }}?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white font-semibold text-xs transition-colors border border-rose-200" title="Check-out Tamu">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Check-out</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                Belum ada peserta yang aktif menginap saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penghunis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $penghunis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
