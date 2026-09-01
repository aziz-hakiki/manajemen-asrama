<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Data Kamar</h1>
            <p class="text-xs text-slate-500 font-medium">Manajemen unit kamar, kapasitas, dan status ketersediaan</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Top Action & Filter Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Kamar</h2>
            <p class="text-xs text-slate-500">Kelola dan pantau seluruh kamar asrama per gedung</p>
        </div>
        <a href="{{ route('admin.kamar.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Kamar</span>
        </a>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('admin.kamar.index') }}" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-3">
            <!-- Search -->
            <div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nomor kamar..." 
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

            <!-- Filter Status -->
            <div>
                <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all">
                    <option value="">Semua Status</option>
                    <option value="kosong" {{ request('status') == 'kosong' ? 'selected' : '' }}>Kosong (0 Terisi)</option>
                    <option value="1_terisi" {{ request('status') == '1_terisi' ? 'selected' : '' }}>1 Terisi</option>
                    <option value="2_terisi" {{ request('status') == '2_terisi' ? 'selected' : '' }}>2 Terisi</option>
                    <option value="3_terisi" {{ request('status') == '3_terisi' ? 'selected' : '' }}>3 Terisi (Penuh)</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'gedung_id', 'status']))
                    <a href="{{ route('admin.kamar.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
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
                        <th class="px-6 py-4">Gedung</th>
                        <th class="px-6 py-4">Nomor Kamar</th>
                        <th class="px-6 py-4">Kapasitas</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kamars as $index => $kamar)
                        @php
                            $terisiCount = $kamar->terisi_count;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-400">
                                {{ $kamars->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $kamar->gedung->nama_gedung ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-mono text-xs font-bold">
                                    {{ $kamar->nomor_kamar }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $kamar->kapasitas }} Orang
                            </td>
                            <td class="px-6 py-4">
                                @if($terisiCount === 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kosong
                                    </span>
                                @elseif($terisiCount === 1)
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            1 Terisi
                                        </span>
                                        @if($kamar->activeTransaksi->isNotEmpty())
                                            <span class="text-[11px] text-slate-400 truncate max-w-[160px]" title="{{ $kamar->activeTransaksi->first()->peserta->nama_peserta ?? '' }}">
                                                👤 {{ $kamar->activeTransaksi->first()->peserta->nama_peserta ?? '1 Peserta' }}
                                            </span>
                                        @endif
                                    </div>
                                @elseif($terisiCount === 2)
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            2 Terisi
                                        </span>
                                        <span class="text-[11px] text-slate-400">
                                            👥 {{ $kamar->activeTransaksi->pluck('peserta.nama_peserta')->filter()->join(', ') }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            3 Terisi
                                        </span>
                                        <span class="text-[11px] text-rose-600/80 font-medium">
                                            Penuh (3/3)
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.kamar.edit', $kamar) }}" class="p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.kamar.destroy', $kamar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data kamar yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kamars->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $kamars->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
