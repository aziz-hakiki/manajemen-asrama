<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Ketersediaan Kamar Asrama</h1>
            <p class="text-xs text-slate-500 font-medium">Pemantauan kapasitas, hunian, dan alokasi kamar siap huni</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Top Stats & Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Status Ketersediaan Kamar</h2>
            <p class="text-xs text-slate-500">Pilih kamar yang masih tersedia untuk langsung memproses check-in peserta</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Kosong -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-xs border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Kosong: {{ $totalKosong }}</span>
            </div>
            <!-- Terisi 1-2 Orang -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 font-semibold text-xs border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Terisi 1-2: {{ $totalSebagian }}</span>
            </div>
            <!-- Penuh -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 font-semibold text-xs border border-rose-200">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>Penuh (3/3): {{ $totalPenuh }}</span>
            </div>
            <!-- Total -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200">
                <span>Total: {{ $totalKamar }} Kamar</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('resepsionis.kamar-kosong.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
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

            <!-- Filter Status Ketersediaan -->
            <div>
                <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all">
                    <option value="">Semua Status</option>
                    <option value="kosong" {{ request('status') == 'kosong' ? 'selected' : '' }}>Kosong (0 Terisi)</option>
                    <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Tersedia Sebagian (1-2 Terisi)</option>
                    <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Semua yang Tersedia (&lt; 3)</option>
                    <option value="penuh" {{ request('status') == 'penuh' ? 'selected' : '' }}>Kamar Penuh (3 Terisi)</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'gedung_id', 'status']))
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
            @php
                $terisi = $kamar->terisi_count;
                $kapasitas = $kamar->kapasitas;
                $sisa = max(0, $kapasitas - $terisi);
                $isFull = $terisi >= $kapasitas;
                $isKosong = $terisi === 0;
            @endphp

            <div class="bg-white rounded-2xl border p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between" 
                 style="{{ $isKosong ? 'border-color: #a7f3d0;' : ($isFull ? 'border-color: #fecdd3; background-color: rgba(255, 241, 242, 0.2);' : 'border-color: #fdba74;') }}">
                <div>
                    <!-- Header Card: Gedung & Status Badge -->
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                            {{ $kamar->gedung->nama_gedung ?? 'Gedung' }}
                        </span>
                        
                        @if($isKosong)
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full"
                                  style="background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0;">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Kosong
                            </span>
                        @elseif(!$isFull)
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full"
                                  style="background-color: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                {{ $terisi }} Terisi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-0.5 rounded-full"
                                  style="background-color: #fff1f2; color: #be123c; border: 1px solid #fecdd3;">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                3 Terisi (Penuh)
                            </span>
                        @endif
                    </div>

                    <!-- Nomor Kamar & Kapasitas -->
                    <div class="my-2">
                        <span class="text-2xl font-extrabold text-slate-800 font-mono">
                            Kamar {{ $kamar->nomor_kamar }}
                        </span>
                        
                        <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Kapasitas: <strong>{{ $kapasitas }} Orang</strong></span>
                            </span>
                            <span class="font-semibold" style="{{ $isKosong ? 'color: #059669;' : ($isFull ? 'color: #e11d48;' : 'color: #ea580c;') }}">
                                {{ $terisi }}/{{ $kapasitas }} Terisi
                            </span>
                        </div>

                        <!-- Occupancy Bed Pills (Visual Slot Indicator) -->
                        <div class="mt-2.5 flex items-center gap-1.5">
                            @for($i = 1; $i <= $kapasitas; $i++)
                                @if($i <= $terisi)
                                    <div class="flex-1 h-1.5 rounded-full" 
                                         style="{{ $isFull ? 'background-color: #f43f5e;' : 'background-color: #f97316;' }}" 
                                         title="Slot {{ $i }}: Terisi"></div>
                                @else
                                    <div class="flex-1 h-1.5 rounded-full bg-slate-200" title="Slot {{ $i }}: Kosong"></div>
                                @endif
                            @endfor
                        </div>

                        <!-- Occupant list preview if occupied -->
                        @if($kamar->activeTransaksi->isNotEmpty())
                            <div class="mt-3 pt-3 border-t border-slate-100 space-y-1">
                                <span class="text-[10px] font-bold uppercase text-slate-400 tracking-wider block">Penghuni Aktif:</span>
                                @foreach($kamar->activeTransaksi as $tr)
                                    <div class="text-[11px] text-slate-600 truncate flex items-center gap-1" title="{{ $tr->peserta->nama_peserta ?? '' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
                                        <span class="font-medium text-slate-700 truncate">{{ $tr->peserta->nama_peserta ?? '-' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Action Button -->
                <div class="mt-4 pt-4 border-t border-slate-100">
                    @if($isKosong)
                        <!-- 0 Terisi: Hijau -->
                        <a href="{{ route('resepsionis.checkin.create', ['kamar_id' => $kamar->id]) }}" 
                           style="background-color: #059669; color: #ffffff;"
                           class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl hover:opacity-90 font-semibold text-xs shadow-xs transition-opacity">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Check-in ke Kamar Ini</span>
                        </a>
                    @elseif(!$isFull)
                        <!-- 1 atau 2 Terisi: Orange -->
                        <a href="{{ route('resepsionis.checkin.create', ['kamar_id' => $kamar->id]) }}" 
                           style="background-color: #f97316; color: #ffffff;"
                           class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl hover:opacity-90 font-semibold text-xs shadow-xs transition-opacity">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span>Check-in ke Kamar Ini</span>
                        </a>
                    @else
                        <!-- 3 Terisi: Merah Disabled -->
                        <button type="button" disabled 
                                style="background-color: #e11d48; color: #ffffff;"
                                class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl font-semibold text-xs shadow-xs opacity-75 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            <span>Kamar Penuh</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <p class="text-sm font-semibold">Tidak ada kamar yang sesuai dengan kriteria filter.</p>
                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan pilihan gedung atau status ketersediaan.</p>
            </div>
        @endforelse
    </div>

    @if($kamars->hasPages())
        <div class="bg-white rounded-2xl border border-slate-200 p-4">
            {{ $kamars->links() }}
        </div>
    @endif
</x-app-layout>
