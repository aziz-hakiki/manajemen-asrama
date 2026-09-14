<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('resepsionis.checkout.index') }}" class="hover:text-indigo-600 transition-colors">
                Check-out
            </a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Pindah Kamar Peserta</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="space-y-6">
        <!-- Page Title -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Perpindahan Kamar Peserta</h1>
                <p class="text-xs text-slate-500">Pindahkan peserta ke kamar lain yang masih memiliki ketersediaan kapasitas</p>
            </div>
            <a 
                href="{{ route('resepsionis.checkout.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-2xs transition-colors self-start sm:self-auto"
            >
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar Check-out</span>
            </a>
        </div>

        <!-- Responsive Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Sidebar Card: Info Tamu & Kamar Saat Ini -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Info Kamar Saat Ini -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 sm:p-6">
                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-700">Penghuni Aktif</span>
                            <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $transaksi->peserta->nama_peserta ?? '-' }}</h3>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <div>
                            <span class="text-slate-400 block mb-0.5 font-medium">NIP / NIK</span>
                            <span class="font-mono font-semibold text-slate-700">{{ $transaksi->peserta->nip_nik ?? '-' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block mb-0.5 font-medium">Instansi / Unit Kerja</span>
                            <span class="font-semibold text-slate-700">{{ $transaksi->peserta->instansi ?? '-' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block mb-0.5 font-medium">Program Diklat</span>
                            <span class="inline-flex px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-semibold text-[11px] border border-indigo-100">
                                {{ $transaksi->peserta->diklat->nama_diklat ?? '-' }}
                            </span>
                        </div>

                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-slate-400 block mb-1 font-medium">Kamar yang Ditempati Saat Ini:</span>
                            @if($transaksi->kamar)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-800 text-sm">Kamar {{ $transaksi->kamar->nomor_kamar }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800">
                                            {{ $transaksi->kamar->gedung->nama_gedung ?? 'Gedung' }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                                        <span>Kapasitas: {{ $transaksi->kamar->kapasitas }} orang</span>
                                        <span>Terisi: {{ $transaksi->kamar->terisi_count }} orang</span>
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </div>

                        <div>
                            <span class="text-slate-400 block mb-0.5 font-medium">Waktu Check-in</span>
                            <span class="text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Panduan -->
                <div class="bg-amber-50/60 rounded-2xl border border-amber-200/60 p-5 text-xs text-amber-900 space-y-2">
                    <div class="flex items-center gap-2 font-bold text-amber-800">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Ketentuan Pindah Kamar</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-[11px] text-amber-800/90 leading-relaxed">
                        <li>Hanya kamar dengan kapasitas kosong yang dapat dipilih.</li>
                        <li>Status kamar baru otomatis berubah menjadi <strong>Terisi</strong>.</li>
                        <li>Jika kamar lama tidak ada penghuni lain, kamar lama otomatis kembali <strong>Kosong</strong>.</li>
                    </ul>
                </div>
            </div>

            <!-- Right Main Card: Formulir Perpindahan Kamar -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Formulir Perpindahan Kamar</h2>
                            <p class="text-xs text-slate-500">Tentukan kamar tujuan baru dan sesuaikan data peserta</p>
                        </div>
                    </div>

                    <form action="{{ route('resepsionis.checkout.pindah-kamar', $transaksi) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Pilih Kamar Baru -->
                        <div class="space-y-2">
                            <label for="kamar_id" class="block text-sm font-bold text-slate-800">
                                Pilih Kamar Tujuan Baru <span class="text-rose-500">*</span>
                            </label>
                            
                            <select 
                                name="kamar_id" 
                                id="kamar_id" 
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm font-medium transition-all shadow-2xs"
                            >
                                <option value="" disabled {{ old('kamar_id') ? '' : 'selected' }}>-- Pilih Kamar Tujuan --</option>
                                @foreach($gedungs as $gedung)
                                    @if($gedung->kamars->isNotEmpty())
                                        <optgroup label="🏢 {{ $gedung->nama_gedung }}">
                                            @foreach($gedung->kamars as $kamar)
                                                @php
                                                    $isCurrentKamar = ($kamar->id == $transaksi->kamar_id);
                                                    $sisa = max(0, $kamar->kapasitas - $kamar->terisi_count);
                                                    $isFull = ($sisa <= 0 && !$isCurrentKamar);
                                                    $isDisabled = ($isCurrentKamar || $isFull);
                                                @endphp
                                                <option 
                                                    value="{{ $kamar->id }}" 
                                                    {{ $isDisabled ? 'disabled' : '' }}
                                                    {{ old('kamar_id') == $kamar->id ? 'selected' : '' }}
                                                >
                                                    Kamar {{ $kamar->nomor_kamar }} — Kapasitas: {{ $kamar->kapasitas }} Orang
                                                    @if($isCurrentKamar)
                                                        (Kamar Saat Ini - Sedang Ditempati)
                                                    @elseif($isFull)
                                                        (Penuh - 0 Slot Tersedia)
                                                    @else
                                                        (Tersedia: {{ $sisa }} Slot)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>

                            @error('kamar_id')
                                <p class="text-xs text-rose-500 font-medium mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-slate-400">
                                Kamar yang sedang ditempati atau yang sudah penuh otomatis dinonaktifkan dari pilihan.
                            </p>
                        </div>

                        <!-- Data Peserta: Read-only Inputs -->
                        <div class="space-y-4">
                            <!-- Nama Peserta -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                    Nama Lengkap Peserta
                                </label>
                                <input 
                                    type="text" 
                                    value="{{ $transaksi->peserta->nama_peserta ?? '-' }}" 
                                    readonly
                                    disabled
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/90 text-slate-600 font-semibold text-sm cursor-not-allowed select-none"
                                >
                            </div>

                            <!-- Sub-grid: NIP & Instansi -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        NIP / NIK
                                    </label>
                                    <input 
                                        type="text" 
                                        value="{{ $transaksi->peserta->nip_nik ?? '-' }}" 
                                        readonly
                                        disabled
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/90 text-slate-600 font-mono text-sm cursor-not-allowed select-none"
                                    >
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                                        Instansi / Unit Kerja
                                    </label>
                                    <input 
                                        type="text" 
                                        value="{{ $transaksi->peserta->instansi ?? '-' }}" 
                                        readonly
                                        disabled
                                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/90 text-slate-600 text-sm cursor-not-allowed select-none"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons: Responsive Flex -->
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-5 border-t border-slate-100">
                            <a 
                                href="{{ route('resepsionis.checkout.index') }}" 
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-2xs transition-colors text-center"
                            >
                                Batal
                            </a>
                            <button 
                                type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors text-center active:scale-95"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                <span>Simpan Perpindahan Kamar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
