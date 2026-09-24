<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-col">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">Booking Kamar Asrama</h1>
                </div>
                <p class="text-xs text-slate-500 font-medium">Denah pemilihan kamar interaktif per asrama berdasarkan tanggal reservasi</p>
            </div>

            <!-- Date Selector in Header -->
            <form method="GET" action="{{ route('resepsionis.booking.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="gedung_id" value="{{ $selectedGedung->id ?? '' }}">
                <div class="flex items-center bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs">
                    <span class="text-xs text-slate-400 mr-2 font-medium">📅 Tanggal:</span>
                    <input 
                        type="date" 
                        name="tanggal" 
                        value="{{ $selectedDate }}" 
                        onchange="this.form.submit()" 
                        class="text-xs font-semibold text-slate-700 border-none p-0 focus:ring-0 cursor-pointer"
                    >
                </div>
            </form>
        </div>
    </x-slot>

    <x-alert />

    @php
        $userAssignedGedungId = (auth()->user()->role === 'resepsionis') ? auth()->user()->assignedGedungId() : null;
        $carbonDate = \Carbon\Carbon::parse($selectedDate);
    @endphp

    <div x-data="cinemaBookingManager()" class="space-y-6">

        <!-- Top Navigation: Gedung Selection Tabs & Quick Date Bar -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Asrama Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                @foreach($gedungs as $gedungItem)
                    @php
                        $isAllowed = is_null($userAssignedGedungId) || ($userAssignedGedungId == $gedungItem->id);
                        $isActiveGedung = ($selectedGedung && $selectedGedung->id == $gedungItem->id);
                    @endphp
                    @if($isAllowed)
                        <a 
                            href="{{ route('resepsionis.booking.index', ['gedung_id' => $gedungItem->id, 'tanggal' => $selectedDate]) }}" 
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all shrink-0 {{ $isActiveGedung ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white text-slate-600 hover:text-indigo-600 hover:bg-slate-50 border border-slate-200' }}"
                        >
                            <span class="w-2 h-2 rounded-full {{ $isActiveGedung ? 'bg-white' : 'bg-indigo-500' }}"></span>
                            <span>{{ $gedungItem->nama_gedung }}</span>
                            <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $isActiveGedung ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-500' }}">
                                {{ $gedungItem->kamars_count }} Kamar
                            </span>
                        </a>
                    @else
                        <div 
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200/60 cursor-not-allowed opacity-60 shrink-0" 
                            title="Akses dinonaktifkan (Anda tidak ditugaskan di {{ $gedungItem->nama_gedung }})"
                        >
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                            <span>{{ $gedungItem->nama_gedung }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="flex items-center gap-2">
                <!-- Quick Date Filter Buttons -->
                <div class="flex items-center gap-1.5 shrink-0 bg-white p-1 rounded-xl border border-slate-200">
                    <a 
                        href="{{ route('resepsionis.booking.index', ['gedung_id' => $selectedGedung->id ?? '', 'tanggal' => now()->toDateString()]) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $selectedDate === now()->toDateString() ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}"
                    >
                        Hari Ini
                    </a>
                    <a 
                        href="{{ route('resepsionis.booking.index', ['gedung_id' => $selectedGedung->id ?? '', 'tanggal' => now()->addDay()->toDateString()]) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $selectedDate === now()->addDay()->toDateString() ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}"
                    >
                        Besok
                    </a>
                    <a 
                        href="{{ route('resepsionis.booking.index', ['gedung_id' => $selectedGedung->id ?? '', 'tanggal' => now()->addDays(2)->toDateString()]) }}" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $selectedDate === now()->addDays(2)->toDateString() ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50' }}"
                    >
                        Lusa
                    </a>
                </div>

                <!-- Tombol Booking Diklat / Booked Sekaligus -->
                <button 
                    type="button" 
                    @click="openBatchBookingModal()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-purple-200 transition-all shrink-0 cursor-pointer"
                    title="Booking asrama sekaligus berdasarkan kegiatan/diklat"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Booking Kamar (Booked)</span>
                </button>
            </div>
        </div>

        <!-- Cinema Legend & Stats Summary Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold text-slate-800">
                        Denah Kamar: {{ $selectedGedung->nama_gedung ?? 'Asrama' }}
                    </h2>
                    <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                        {{ $carbonDate->translatedFormat('l, d F Y') }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5">Gunakan tombol <strong class="text-purple-600">Booking Diklat (Booked)</strong> untuk memesan kamar, atau klik kotak ungu/merah untuk melihat rincian.</p>
            </div>

            <!-- Seat Legend Colors requested by user: Hijau = Kosong, Merah = Terisi, Violet = Booked, Abu-abu = Rusak -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 text-xs">
                <!-- Hijau (Kosong) -->
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
                    <span class="w-3.5 h-3.5 rounded-md bg-emerald-500 shadow-2xs inline-block border border-emerald-600"></span>
                    <span class="font-medium text-slate-700">Kosong:</span>
                    <span class="font-bold text-emerald-600">{{ $stats['kosong'] }}</span>
                </div>

                <!-- Merah (Terisi) -->
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
                    <span class="w-3.5 h-3.5 rounded-md bg-rose-500 shadow-2xs inline-block border border-rose-600"></span>
                    <span class="font-medium text-slate-700">Terisi:</span>
                    <span class="font-bold text-rose-600">{{ $stats['terisi'] }}</span>
                </div>

                <!-- Violet (Booked) -->
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
                    <span class="w-3.5 h-3.5 rounded-md bg-purple-600 shadow-2xs inline-block border border-purple-700"></span>
                    <span class="font-medium text-slate-700">Booked:</span>
                    <span class="font-bold text-purple-600">{{ $stats['booked'] }}</span>
                </div>

                <!-- Abu-abu (Rusak) -->
                <div class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200/80">
                    <span class="w-3.5 h-3.5 rounded-md bg-slate-300 shadow-2xs inline-block border border-slate-400"></span>
                    <span class="font-medium text-slate-700">Rusak:</span>
                    <span class="font-bold text-slate-500">{{ $stats['rusak'] }}</span>
                </div>

                <!-- Total -->
                <div class="px-2 py-1.5 text-slate-500 font-semibold text-xs shrink-0">
                    <span>Total: {{ $stats['total'] }} Kamar</span>
                </div>
            </div>
        </div>

        <!-- Cinema Theater Hall Card (Seluruh Kamar Gedung dalam 1 Selection Card) -->
        <div class="bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-4 sm:p-6 md:p-8 shadow-xl border border-slate-700/60 relative overflow-hidden text-white">
            
            <!-- Cinema Ambient Lighting Decor -->
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-3/4 h-32 bg-indigo-500/20 blur-3xl rounded-full pointer-events-none"></div>

         

            <!-- Empty State if building has no rooms -->
            @if($kamarsWithStatus->isEmpty())
                <div class="text-center py-12 sm:py-16">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-2xl bg-slate-800 flex items-center justify-center text-slate-500">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-200">Belum ada kamar di gedung ini</h3>
                    <p class="text-xs text-slate-400 mt-1">Silakan tambahkan data kamar pada menu Master Kamar.</p>
                </div>
            @else
                <!-- Denah Kamar Grid Kotak (Model Denah Lantai / Kursi Bioskop) -->
                <div class="max-w-2xl mx-auto">
                    <div 
                        class="p-4 sm:p-6 rounded-2xl bg-slate-900/80 border border-slate-700/60 shadow-inner"
                        style="display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; align-items: center;"
                    >
                        @foreach($kamarsWithStatus as $kamarItem)
                            @php
                                $kategori = $kamarItem['status_kategori'];
                                $kamarJson = json_encode($kamarItem);

                                // Logika Warna: Hijau = Kosong, Merah = Terisi & Selesai, Ungu = Booked, Abu-abu = Rusak
                                if ($kategori === 'kosong') {
                                    $bgStyle = 'background-color: #10b981; border-color: #34d399; color: #ffffff; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);';
                                    $cursorStyle = 'cursor: default;';
                                } elseif ($kategori === 'terisi' || $kategori === 'selesai') {
                                    $bgStyle = 'background-color: #ef4444; border-color: #f87171; color: #ffffff; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.35);';
                                    $cursorStyle = 'cursor: pointer;';
                                } elseif ($kategori === 'booked') {
                                    $bgStyle = 'background-color: #8b5cf6; border-color: #a78bfa; color: #ffffff; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.35);';
                                    $cursorStyle = 'cursor: pointer;';
                                } else {
                                    $bgStyle = 'background-color: #64748b; border-color: #94a3b8; color: #cbd5e1; opacity: 0.65;';
                                    $cursorStyle = 'cursor: not-allowed;';
                                }
                            @endphp

                            <!-- Kotak Persegi Kamar (Contoh: 62px x 62px) -->
                            <button 
                                type="button"
                                @click="selectRoom({{ $kamarJson }})"
                                class="group relative transition-all duration-150 transform hover:scale-105 active:scale-95 focus:outline-none"
                                style="width: 62px; height: 62px; min-width: 62px; min-height: 62px; flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; border-width: 2px; border-style: solid; {{ $bgStyle }} {{ $cursorStyle }}"
                                :class="activeRoom && activeRoom.id === {{ $kamarItem['id'] }} ? 'ring-4 ring-amber-400 ring-offset-2 ring-offset-slate-900 scale-105' : ''"
                                title="Kamar {{ $kamarItem['nomor_kamar'] }} - {{ $kamarItem['label'] }}"
                            >
                                <!-- Nomor Kamar di Tengah-tengah Kotak -->
                                <span style="font-size: 15px; font-weight: 800; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; line-height: 1; text-align: center; letter-spacing: -0.02em;">
                                    {{ $kamarItem['nomor_kamar'] }}
                                </span>

                                <!-- Tooltip Hover Popover -->
                                <div class="absolute bottom-full mb-2 hidden group-hover:block z-30 pointer-events-none w-36 p-2 rounded-xl bg-slate-950/95 text-white text-[11px] text-center shadow-xl border border-slate-700 leading-tight backdrop-blur-xs">
                                    <p class="font-extrabold text-indigo-300">Kamar {{ $kamarItem['nomor_kamar'] }}</p>
                                    <p class="text-[10px] text-slate-300 mt-0.5">{{ $kamarItem['label'] }}</p>
                                    @if($kategori !== 'kosong')
                                        <span class="text-[9px] text-amber-400 block mt-1 font-semibold">Klik untuk detail</span>
                                    @else
                                        <span class="text-[9px] text-emerald-400 block mt-1 font-semibold">Kosong (Tersedia)</span>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Bottom Cinema Tips -->
                    <div class="mt-5 sm:mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-[11px] sm:text-xs text-slate-400 px-1">
                        <div class="flex items-center gap-2">
                            <span>🟢 Kotak hijau: Kamar kosong (tersedia). Pemesanan dilakukan via tombol "Booking Diklat (Booked)".</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>🟣 Klik kamar ungu untuk konfirmasi Check-in tamu atau Batalkan reservasi.</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Section: Tabel Rekap Reservasi Aktif untuk Tanggal Terpilih -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Daftar Reservasi & Hunian</h3>
                    <p class="text-xs text-slate-500">Rincian tamu yang sedang menginap, riwayat selesai, dan pemesanan kamar pada {{ $carbonDate->translatedFormat('d F Y') }}</p>
                </div>
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full">
                    {{ $daftarHunianDanReservasi->count() }} Data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200 text-[11px] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">No. Kamar</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Nama Tamu / Pemesan</th>
                            <th class="px-6 py-3">Instansi / Diklat</th>
                            <th class="px-6 py-3">Check-in</th>
                            <th class="px-6 py-3">Check-out</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($daftarHunianDanReservasi as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-3.5 font-bold text-slate-900 font-mono text-sm text-center">
                                    {{ $item['nomor_kamar'] }}
                                </td>
                                <td class="px-6 py-3.5">
                                    @if($item['status_kategori'] === 'terisi')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Terisi
                                        </span>
                                    @elseif($item['status_kategori'] === 'selesai')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Booked
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 font-medium text-slate-800">
                                    <div>{{ $item['nama'] }}</div>
                                    @if(!empty($item['sub_nama']))
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $item['sub_nama'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="text-slate-700">{{ $item['instansi'] }}</div>
                                    @if(!empty($item['diklat']))
                                        <div class="text-indigo-600 text-[11px] font-medium">{{ $item['diklat'] }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-col gap-0.5 text-xs">
                                        <div class="text-slate-600">
                                            <span class="text-slate-800">{{ $item['check_in'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-col gap-0.5 text-xs">
                                        <div class="text-slate-600">
                                            <span class="{{ $item['status_kategori'] === 'selesai' ? 'text-emerald-700 font-semibold' : 'text-slate-800' }}">
                                                {{ $item['check_out'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <div class="flex items-center justify-center">
                                        @if($item['type'] === 'booking')
                                            <form action="{{ route('resepsionis.booking.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data reservasi untuk {{ addslashes($item['nama']) }} (Kamar {{ $item['nomor_kamar'] }})?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200/60 hover:border-rose-300 transition-colors font-semibold text-xs shadow-2xs" title="Hapus Data Reservasi">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @elseif($item['type'] === 'transaksi')
                                            <form action="{{ route('resepsionis.booking.transaksi.destroy', $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data hunian untuk {{ addslashes($item['nama']) }} (Kamar {{ $item['nomor_kamar'] }})?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200/60 hover:border-rose-300 transition-colors font-semibold text-xs shadow-2xs" title="Hapus Data Hunian">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada reservasi atau riwayat hunian pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 0: FORM BOOKING DIKLAT / BATCH (TIDAK SATU-SATU KLIK)    -->
        <!-- ============================================================== -->
        <div 
            x-show="showBatchBookingModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="showBatchBookingModal = false"
                class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-2xl w-full p-6 sm:p-8 animate-fadeIn max-h-[90vh] overflow-y-auto"
            >
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Booking Asrama Kegiatan / Diklat</h2>
                            <p class="text-xs text-slate-500">Booking kamar sekaligus untuk rombongan diklat pada periode tertentu</p>
                        </div>
                    </div>
                    <button @click="showBatchBookingModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('resepsionis.booking.batch') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- 1. Pilihan Diklat / Kegiatan -->
                    <div>
                        <label for="batch_diklat_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Pilih Kegiatan / Diklat <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="diklat_id" 
                            id="batch_diklat_id" 
                            x-model="batchForm.diklat_id" 
                            @change="onBatchDiklatChange()"
                            required 
                            class="w-full text-sm rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 py-2.5 px-3.5 bg-white shadow-2xs font-medium"
                        >
                            <option value="">-- Pilih Kegiatan atau Diklat --</option>
                            <template x-for="d in diklatsData" :key="d.id">
                                <option :value="d.id" x-text="d.nama_diklat + ' (' + d.tanggal_mulai_formatted + ' s/d ' + d.tanggal_selesai_formatted + ')'"></option>
                            </template>
                        </select>
                        <template x-if="diklatsData.length === 0">
                            <p class="text-xs text-amber-600 mt-1.5 font-medium flex items-center gap-1">
                                <span>⚠️</span>
                                <span>Tidak ada kegiatan/diklat mendatang yang tersedia untuk dibooking.</span>
                            </p>
                        </template>
                    </div>

                    <!-- Diklat Selected Preview Card -->
                    <template x-if="selectedDiklat">
                        <div class="p-3.5 rounded-xl bg-purple-50 border border-purple-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                                <span class="font-bold text-purple-900" x-text="selectedDiklat.nama_diklat"></span>
                            </div>
                            <div class="flex items-center gap-3 text-purple-700 font-medium">
                                <span>📅 <span x-text="selectedDiklat.tanggal_mulai_formatted + ' s/d ' + selectedDiklat.tanggal_selesai_formatted"></span></span>
                                <span>👥 <span x-text="selectedDiklat.pesertas_count"></span> Peserta Terdaftar</span>
                            </div>
                        </div>
                    </template>

                    <!-- 2. Asrama / Gedung -->
                    <div>
                        <label for="batch_gedung_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Pilih Asrama / Gedung <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="gedung_id" 
                            id="batch_gedung_id" 
                            x-model="batchForm.gedung_id" 
                            @change="onBatchGedungChange()"
                            required 
                            class="w-full text-sm rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 py-2.5 px-3.5 bg-white shadow-2xs font-medium"
                        >
                            <template x-for="g in gedungsData" :key="g.id">
                                <option :value="g.id" x-text="g.nama_gedung + ' (' + g.kamars.length + ' Kamar Total)'"></option>
                            </template>
                        </select>
                        @if($userAssignedGedungId)
                            <p class="text-[11px] text-slate-500 mt-1.5 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Pilihan asrama dibatasi sesuai penugasan Anda.</span>
                            </p>
                        @endif
                    </div>

                    <!-- 3. Periode Tanggal Booking -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="batch_tanggal_mulai" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Tanggal Mulai Booking <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_mulai" 
                                id="batch_tanggal_mulai" 
                                x-model="batchForm.tanggal_mulai"
                                required 
                                class="w-full text-sm rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 py-2.5 px-3.5 shadow-2xs font-medium"
                            >
                        </div>
                        <div>
                            <label for="batch_tanggal_selesai" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Tanggal Selesai Booking <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="tanggal_selesai" 
                                id="batch_tanggal_selesai" 
                                x-model="batchForm.tanggal_selesai"
                                required 
                                class="w-full text-sm rounded-xl border border-slate-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 py-2.5 px-3.5 shadow-2xs font-medium"
                            >
                        </div>
                    </div>

                    <!-- 4. Pilihan Kamar yang di-booking -->
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <label class="block text-sm font-semibold text-slate-700">
                            Cakupan Kamar yang Dibooking:
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all" :class="batchForm.mode_kamar === 'semua' ? 'border-purple-500 bg-purple-50/40 text-purple-900 font-bold' : 'border-slate-200 text-slate-700'">
                                <input type="radio" name="mode_kamar" value="semua" x-model="batchForm.mode_kamar" class="mt-0.5 text-purple-600 focus:ring-purple-500">
                                <div>
                                    <div class="text-xs font-bold">Booking Seluruh Kamar</div>
                                    <div class="text-[11px] text-slate-500 font-normal">Semua kamar aktif di asrama terpilih akan berstatus Booked.</div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all" :class="batchForm.mode_kamar === 'pilih' ? 'border-purple-500 bg-purple-50/40 text-purple-900 font-bold' : 'border-slate-200 text-slate-700'">
                                <input type="radio" name="mode_kamar" value="pilih" x-model="batchForm.mode_kamar" class="mt-0.5 text-purple-600 focus:ring-purple-500">
                                <div>
                                    <div class="text-xs font-bold">Pilih Kamar Tertentu</div>
                                    <div class="text-[11px] text-slate-500 font-normal">Pilih hanya kamar-kamar spesifik yang dibutuhkan.</div>
                                </div>
                            </label>
                        </div>

                        <!-- Checkbox Kamar jika pilih manual -->
                        <div x-show="batchForm.mode_kamar === 'pilih'" class="mt-3 p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                            <div class="flex items-center justify-between text-xs mb-2">
                                <span class="font-bold text-slate-700">Daftar Kamar di Asrama:</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="selectAllKamars()" class="text-purple-600 hover:underline font-semibold cursor-pointer">Pilih Semua</button>
                                    <span>&bull;</span>
                                    <button type="button" @click="deselectAllKamars()" class="text-slate-500 hover:underline cursor-pointer">Batal Semua</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 max-h-48 overflow-y-auto p-1">
                                <template x-for="k in getActiveGedungKamars()" :key="k.id">
                                    <label class="flex items-center gap-1.5 p-2 rounded-lg border text-xs cursor-pointer select-none transition-all" :class="k.is_rusak ? 'opacity-40 bg-slate-100 border-slate-200 cursor-not-allowed' : (batchForm.kamar_ids.includes(k.id) ? 'border-purple-400 bg-purple-100/70 text-purple-800 font-bold' : 'bg-white border-slate-200 text-slate-700')">
                                        <input type="checkbox" name="kamar_ids[]" :value="k.id" x-model="batchForm.kamar_ids" :disabled="k.is_rusak" class="rounded text-purple-600 focus:ring-purple-500 text-xs">
                                        <span x-text="k.nomor_kamar"></span>
                                        <template x-if="k.is_rusak"><span class="text-[9px] text-rose-500 font-bold">(R)</span></template>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="showBatchBookingModal = false" 
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors cursor-pointer"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold shadow-md shadow-purple-200 transition-all flex items-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan & Terapkan Status Booked</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 2: DETAIL & PEMBATALAN BOOKING (KETIKA KLIK KAMAR VIOLET / BOOKED) -->
        <!-- ============================================================== -->
        <div 
            x-show="showDetailModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="showDetailModal = false"
                class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 sm:p-8 animate-fadeIn max-h-[90vh] overflow-y-auto"
            >
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">
                                Detail & Pembatalan Booking Kamar <span class="text-purple-600 font-mono" x-text="activeRoom?.nomor_kamar"></span>
                            </h2>
                            <p class="text-xs text-slate-500">{{ $selectedGedung->nama_gedung ?? 'Asrama' }} &bull; Status Terbooking (Booked)</p>
                        </div>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <template x-if="activeRoom && activeRoom.booking">
                    <div class="space-y-5">
                        <!-- Informasi Detail Booking -->
                        <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-100 space-y-3.5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 mb-0.5">Nama Pemesan / Tamu</span>
                                    <span class="text-sm font-bold text-slate-800" x-text="activeRoom.booking.nama_pemesan"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 mb-0.5">Instansi / Asal Kantor</span>
                                    <span class="text-sm font-medium text-slate-700" x-text="activeRoom.booking.instansi || '-'"></span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-slate-400 mb-0.5">Program Kegiatan / Diklat</span>
                                    <span class="text-sm font-semibold text-indigo-600" x-text="activeRoom.booking.diklat || '-'"></span>
                                </div>
                                <template x-if="activeRoom.booking.no_telepon && activeRoom.booking.no_telepon !== '-'">
                                    <div>
                                        <span class="block text-xs font-semibold text-slate-400 mb-0.5">No. WhatsApp / HP</span>
                                        <span class="text-sm font-mono text-slate-700" x-text="activeRoom.booking.no_telepon"></span>
                                    </div>
                                </template>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-slate-400 mb-0.5">Periode Booking</span>
                                    <span class="text-sm font-bold text-slate-800" x-text="activeRoom.booking.tanggal_mulai + ' s/d ' + activeRoom.booking.tanggal_selesai"></span>
                                </div>
                            </div>
                            <template x-if="activeRoom.booking.keterangan && activeRoom.booking.keterangan !== '-'">
                                <div class="pt-3 border-t border-purple-100 text-xs">
                                    <span class="font-bold text-slate-700">Catatan Khusus:</span>
                                    <p class="mt-0.5 text-slate-600" x-text="activeRoom.booking.keterangan"></p>
                                </div>
                            </template>
                        </div>

                        <!-- Card & Form Pembatalan Booking -->
                        <div class="p-4 rounded-xl bg-rose-50/80 border border-rose-200 space-y-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-rose-950">Batalkan Booking Kamar Ini</h4>
                                    <p class="text-[11px] text-rose-700">Jika booking dibatalkan, status kamar ini akan langsung kembali menjadi <strong>Kosong (Hijau)</strong>.</p>
                                </div>
                            </div>

                            <form :action="'{{ url('/resepsionis/booking') }}/' + activeRoom.booking.id + '/cancel'" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan status booked untuk Kamar ' + (activeRoom ? activeRoom.nomor_kamar : '') + '? Status kamar akan kembali Kosong (Tersedia).');" class="pt-1">
                                @csrf
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 bg-white p-3 rounded-xl border border-rose-200">
                                    <div class="text-xs text-slate-600">
                                        Pemesan: <strong class="text-slate-800" x-text="activeRoom.booking.nama_pemesan"></strong>
                                    </div>
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition-colors flex items-center justify-center gap-1.5 cursor-pointer shrink-0"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Konfirmasi Batalkan Booking</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Direct Check-in Section inside Modal 2 -->
                        <div class="p-4 rounded-xl bg-emerald-50/80 border border-emerald-200 space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-xs font-bold">
                                    ✓
                                </span>
                                <div>
                                    <h4 class="text-xs font-bold text-emerald-950">Check-in Tamu / Peserta ke Kamar Ini</h4>
                                    <p class="text-[11px] text-emerald-700">Pilih peserta yang hadir untuk langsung menempati kamar dan mengubah status denah menjadi Merah (Terisi).</p>
                                </div>
                            </div>

                            <form :action="'{{ url('/resepsionis/booking') }}/' + activeRoom.booking.id + '/checkin'" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                                        Pilih Peserta Diklat yang Hadir <span class="text-rose-500">*</span>:
                                    </label>
                                    <select 
                                        name="peserta_id" 
                                        required 
                                        class="w-full text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 py-2.5 px-3 bg-white"
                                    >
                                        <option value="">-- Pilih Peserta yang Check-in --</option>
                                        <template x-for="p in getAvailablePesertas(activeRoom.booking.diklat_id)" :key="p.id">
                                            <option :value="p.id" x-text="p.nama_peserta + (p.nip_nik ? ' (NIP: ' + p.nip_nik + ')' : '') + (p.instansi ? ' - ' + p.instansi : '')"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <a :href="'{{ route('resepsionis.checkin.create') }}?kamar_id=' + activeRoom.id" class="text-xs font-medium text-emerald-700 hover:underline">
                                        Formulir Check-in Lengkap &rarr;
                                    </a>
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-colors flex items-center gap-1.5 cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Proses Check-in Kamar Ini</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                            <button 
                                type="button" 
                                @click="showDetailModal = false"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-colors cursor-pointer"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- MODAL 3: DETAIL KAMAR TERISI (KETIKA KLIK KAMAR MERAH)        -->
        <!-- ============================================================== -->
        <div 
            x-show="showOccupiedModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="showOccupiedModal = false"
                class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 sm:p-8 animate-fadeIn"
            >
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">
                                Penghuni Kamar <span class="font-mono text-rose-600" x-text="activeRoom?.nomor_kamar"></span>
                            </h2>
                            <p class="text-xs text-slate-500">
                                {{ $selectedGedung->nama_gedung ?? 'Asrama' }} &bull; 
                                <span x-text="activeRoom?.status_kategori === 'selesai' ? 'Status Selesai (Sudah Check-out)' : 'Status Terisi (Menginap)'"></span>
                            </p>
                        </div>
                    </div>
                    <button @click="showOccupiedModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <template x-if="activeRoom && activeRoom.penghuni">
                    <div class="space-y-4">
                        <template x-for="(p, idx) in activeRoom.penghuni" :key="idx">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-800" x-text="p.nama"></span>
                                    <span 
                                        class="text-xs px-2.5 py-0.5 rounded-full font-semibold border"
                                        :class="p.status === 'selesai' ? 'bg-slate-100 text-slate-700 border-slate-300' : 'bg-rose-100 text-rose-700 border-rose-200'"
                                        x-text="p.status === 'selesai' ? 'Selesai' : 'Menginap'"
                                    ></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600">
                                    <div>
                                        <span class="text-slate-400">Instansi:</span>
                                        <span class="font-medium text-slate-700" x-text="p.instansi"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">Kegiatan:</span>
                                        <span class="font-semibold text-indigo-600" x-text="p.diklat"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400">Check-in:</span>
                                        <span class="text-slate-700 font-medium" x-text="p.tanggal_masuk"></span>
                                    </div>
                                    <template x-if="p.tanggal_keluar">
                                        <div>
                                            <span class="text-slate-400">Check-out:</span>
                                            <span class="text-emerald-700 font-semibold" x-text="p.tanggal_keluar"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <template x-if="activeRoom.status_kategori === 'terisi'">
                                <a href="{{ route('resepsionis.checkout.index') }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1">
                                    <span>Ke Menu Check-out</span>
                                    <span>&rarr;</span>
                                </a>
                            </template>
                            <template x-if="activeRoom.status_kategori !== 'terisi'">
                                <div></div>
                            </template>
                            <button 
                                type="button" 
                                @click="showOccupiedModal = false" 
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors"
                            >
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </div>

    <!-- Alpine.js Cinema Controller State Script -->
    <script>
        function cinemaBookingManager() {
            return {
                activeRoom: null,
                showDetailModal: false,
                showOccupiedModal: false,
                showBatchBookingModal: false,
                currentDate: '{{ $selectedDate }}',

                diklatsData: {{ Js::from($diklatsJson ?? []) }},
                gedungsData: {{ Js::from($gedungsJson ?? []) }},
                allAvailablePesertas: {{ Js::from($availablePesertas->map(fn($p) => ['id' => $p->id, 'nama_peserta' => $p->nama_peserta, 'nip_nik' => $p->nip_nik ?? '', 'instansi' => $p->instansi ?? ''])) }},

                batchForm: {
                    diklat_id: '',
                    gedung_id: '{{ $selectedGedung->id ?? '' }}',
                    tanggal_mulai: '{{ $selectedDate }}',
                    tanggal_selesai: '{{ $selectedDate }}',
                    nama_pemesan: '',
                    mode_kamar: 'semua',
                    kamar_ids: [],
                    keterangan: '',
                },

                get selectedDiklat() {
                    return this.diklatsData.find(d => d.id == this.batchForm.diklat_id) || null;
                },

                openBatchBookingModal() {
                    this.batchForm.diklat_id = '';
                    const defaultGedungId = '{{ $selectedGedung->id ?? '' }}';
                    const hasDefault = this.gedungsData.some(g => g.id == defaultGedungId);
                    this.batchForm.gedung_id = hasDefault ? defaultGedungId : (this.gedungsData[0]?.id || '');
                    this.batchForm.tanggal_mulai = '{{ $selectedDate }}';
                    this.batchForm.tanggal_selesai = '{{ $selectedDate }}';
                    this.batchForm.nama_pemesan = '';
                    this.batchForm.mode_kamar = 'semua';
                    this.batchForm.kamar_ids = [];
                    this.batchForm.keterangan = '';
                    this.onBatchGedungChange();
                    this.showBatchBookingModal = true;
                },

                onBatchDiklatChange() {
                    const selected = this.selectedDiklat;
                    if (selected) {
                        this.batchForm.tanggal_mulai = selected.tanggal_mulai;
                        this.batchForm.tanggal_selesai = selected.tanggal_selesai;
                        this.batchForm.nama_pemesan = selected.nama_diklat;
                        this.batchForm.keterangan = 'Booking Diklat ' + selected.nama_diklat;
                    }
                },

                onBatchGedungChange() {
                    const foundGedung = this.gedungsData.find(g => g.id == this.batchForm.gedung_id);
                    if (foundGedung) {
                        this.batchForm.kamar_ids = foundGedung.kamars.filter(k => !k.is_rusak).map(k => k.id);
                    } else {
                        this.batchForm.kamar_ids = [];
                    }
                },

                getActiveGedungKamars() {
                    const foundGedung = this.gedungsData.find(g => g.id == this.batchForm.gedung_id);
                    return foundGedung ? foundGedung.kamars : [];
                },

                selectAllKamars() {
                    const kamars = this.getActiveGedungKamars();
                    this.batchForm.kamar_ids = kamars.filter(k => !k.is_rusak).map(k => k.id);
                },

                deselectAllKamars() {
                    this.batchForm.kamar_ids = [];
                },

                getAvailablePesertas(diklatId) {
                    if (diklatId) {
                        const found = this.diklatsData.find(d => d.id == diklatId);
                        if (found && found.pesertas && found.pesertas.length > 0) {
                            return found.pesertas;
                        }
                    }
                    return this.allAvailablePesertas;
                },

                selectRoom(room) {
                    this.activeRoom = room;

                    if (room.status_kategori === 'kosong') {
                        // Kamar kosong tidak membuka modal form individual lagi
                        // Booking kamar dilakukan melalui tombol "Booking Diklat (Booked)"
                        return;
                    } else if (room.status_kategori === 'booked') {
                        this.showDetailModal = true;
                    } else if (room.status_kategori === 'terisi' || room.status_kategori === 'selesai') {
                        this.showOccupiedModal = true;
                    } else if (room.status_kategori === 'rusak') {
                        alert('Kamar ' + room.nomor_kamar + ' saat ini sedang dalam pemeliharaan/perbaikan fasilitas.');
                    }
                }
            };
        }
    </script>
</x-app-layout>
