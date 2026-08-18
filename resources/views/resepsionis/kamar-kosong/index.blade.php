<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Kamar Kosong (Siap Huni)</h1>
            <p class="text-xs text-slate-500 font-medium">Pemantauan unit kamar yang tersedia untuk dialokasikan ke peserta</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Top Stats & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Ketersediaan Kamar Asrama</h2>
            <p class="text-xs text-slate-500">Pilih kamar yang tersedia untuk langsung memproses check-in</p>
        </div>
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs border border-emerald-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Tersedia: {{ $totalKosong }} Kamar Kosong</span>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('resepsionis.kamar-kosong.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
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

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'gedung_id']))
                    <a href="{{ route('resepsionis.kamar-kosong.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Room Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($kamars as $kamar)
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-emerald-300 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            {{ $kamar->gedung->nama_gedung ?? 'Gedung' }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Kosong
                        </span>
                    </div>

                    <div class="my-2">
                        <span class="text-2xl font-extrabold text-slate-800 font-mono">
                            Kamar {{ $kamar->nomor_kamar }}
                        </span>
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Kapasitas: <strong>{{ $kamar->kapasitas }} Orang</strong></span>
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('resepsionis.checkin.create', ['kamar_id' => $kamar->id]) }}" class="w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Check-in ke Kamar Ini</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <p class="text-sm font-semibold">Tidak ada kamar kosong yang tersedia saat ini.</p>
                <p class="text-xs text-slate-400 mt-1">Semua kamar sedang terisi atau tidak sesuai filter.</p>
            </div>
        @endforelse
    </div>

    @if($kamars->hasPages())
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            {{ $kamars->links() }}
        </div>
    @endif
</x-app-layout>
