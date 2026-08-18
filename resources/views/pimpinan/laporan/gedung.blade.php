<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Laporan Okupansi Per Gedung</h1>
                <p class="text-xs text-slate-500 font-medium">Analisis kapasitas dan utilisasi unit gedung asrama PPSDMAP</p>
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

    <!-- Rekapitulasi Seluruh Gedung Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">Rekapitulasi Kapasitas & Hunian per Gedung</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ringkasan utilisasi seluruh fasilitas gedung asrama</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Gedung</th>
                        <th class="px-6 py-4 text-center">Total Kamar</th>
                        <th class="px-6 py-4 text-center">Kamar Terisi</th>
                        <th class="px-6 py-4 text-center">Kamar Kosong</th>
                        <th class="px-6 py-4 text-center">Total Kapasitas</th>
                        <th class="px-6 py-4">Tingkat Okupansi</th>
                        <th class="px-6 py-4 text-right">Rincian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($gedungs as $gedung)
                        @php
                            $totalKamar = $gedung->kamars_count ?? 0;
                            $terisi = $gedung->kamars_terisi_count ?? 0;
                            $kosong = $gedung->kamars_kosong_count ?? 0;
                            $rate = $totalKamar > 0 ? round(($terisi / $totalKamar) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ (request('gedung_id') == $gedung->id) ? 'bg-indigo-50/40' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">
                                        {{ substr($gedung->nama_gedung, -1) }}
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $gedung->nama_gedung }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-semibold text-slate-800">
                                {{ $totalKamar }} Kamar
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700">
                                    {{ $terisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                    {{ $kosong }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-slate-700">
                                {{ $gedung->total_kapasitas ?? 0 }} Orang
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[140px] flex items-center gap-2">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $rate }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $rate }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('pimpinan.laporan.gedung', ['gedung_id' => $gedung->id]) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    <span>Lihat Kamar</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data gedung.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Kamar Per Gedung Terpilih -->
    @if($selectedGedung)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Detail Status Kamar: {{ $selectedGedung->nama_gedung }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar kamar beserta nama tamu yang sedang menempati saat ini</p>
                </div>
                <a href="{{ route('pimpinan.laporan.gedung') }}" class="text-xs text-slate-500 hover:text-slate-800 font-semibold">
                    &times; Tutup Rincian
                </a>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($kamarsGedung as $kamar)
                        @php
                            $activeStay = $kamar->transaksi->first();
                        @endphp
                        <div class="p-4 rounded-xl border {{ $kamar->status === 'terisi' ? 'border-rose-200 bg-rose-50/30' : 'border-emerald-200 bg-emerald-50/30' }} flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-extrabold text-base text-slate-800 font-mono">
                                        Kamar {{ $kamar->nomor_kamar }}
                                    </span>
                                    @if($kamar->status === 'terisi')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">
                                            Terisi
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                            Kosong
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mb-2">Kapasitas: {{ $kamar->kapasitas }} Orang</p>

                                @if($activeStay && $activeStay->peserta)
                                    <div class="mt-2 pt-2 border-t border-rose-200/60 text-xs">
                                        <p class="text-[10px] font-bold text-rose-800 uppercase tracking-wider">Penghuni:</p>
                                        <p class="font-bold text-slate-800">{{ $activeStay->peserta->nama_peserta }}</p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $activeStay->peserta->diklat->nama_diklat ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">Masuk: {{ \Carbon\Carbon::parse($activeStay->tanggal_masuk)->translatedFormat('d M Y, H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                            Tidak ada kamar terdaftar di gedung ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
