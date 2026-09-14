<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Proses Check-out Asrama</h1>
            <p class="text-xs text-slate-500 font-medium">Layanan kepulangan peserta dan pelepasan status kamar asrama</p>
        </div>
    </x-slot>

    <div 
        x-data="{
            modalOpen: false,
            selected: null,
            kamarId: '',
            namaPeserta: '',
            nipNik: '',
            instansi: '',
            openModal(data) {
                this.selected = data;
                this.kamarId = '';
                this.namaPeserta = data.nama_peserta;
                this.nipNik = data.nip_nik;
                this.instansi = data.instansi;
                this.modalOpen = true;
            },
            closeModal() {
                this.modalOpen = false;
                this.selected = null;
            }
        }"
        @keydown.escape.window="closeModal()"
        class="space-y-6"
    >
        <x-alert />

        <!-- Top Info Card -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar Tamu Menginap (Siap Check-out / Pindah Kamar)</h2>
                <p class="text-xs text-slate-500">Kelola kepulangan tamu atau pindahkan peserta ke alokasi kamar baru</p>
            </div>
            <div class="text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                Total Penghuni: {{ $penghunis->total() }} Orang
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
            <form method="GET" action="{{ route('resepsionis.checkout.index') }}" class="flex items-center gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama peserta, NIP/NIK, atau nomor kamar..." 
                        class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                    >
                </div>
                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                    Cari
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('resepsionis.checkout.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4">No</th>
                            <th class="px-5 py-4">Nama Peserta</th>
                            <th class="px-5 py-4">Kamar & Gedung</th>
                            <th class="px-5 py-4">Program Diklat</th>
                            <th class="px-5 py-4">Tanggal Masuk</th>
                            <th class="px-5 py-4 text-center">Edit / Pindah Kamar</th>
                            <th class="px-5 py-4 text-center">Aksi Check-out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($penghunis as $index => $transaksi)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 font-medium text-slate-400">
                                    {{ $penghunis->firstItem() + $index }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800">{{ $transaksi->peserta->nama_peserta ?? '-' }}</span>
                                        <span class="text-xs text-slate-400">NIP/NIK: {{ $transaksi->peserta->nip_nik ?? '-' }}</span>
                                        <span class="text-xs text-slate-400">{{ $transaksi->peserta->instansi ?? 'Umum' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if($transaksi->kamar)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-100">
                                            <span>Kamar {{ $transaksi->kamar->nomor_kamar }}</span>
                                            <span class="text-indigo-400 font-normal">({{ $transaksi->kamar->gedung->nama_gedung ?? '' }})</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs font-medium text-slate-700">
                                    {{ $transaksi->peserta->diklat->nama_diklat ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button 
                                        type="button" 
                                        @click="openModal({
                                            id: {{ $transaksi->id }},
                                            action: '{{ route('resepsionis.checkout.pindah-kamar', $transaksi) }}',
                                            nama_peserta: '{{ addslashes($transaksi->peserta->nama_peserta ?? '') }}',
                                            nip_nik: '{{ addslashes($transaksi->peserta->nip_nik ?? '') }}',
                                            instansi: '{{ addslashes($transaksi->peserta->instansi ?? '') }}',
                                            diklat: '{{ addslashes($transaksi->peserta->diklat->nama_diklat ?? '-') }}',
                                            kamar_id: {{ $transaksi->kamar_id ?? 0 }},
                                            nomor_kamar: '{{ addslashes($transaksi->kamar->nomor_kamar ?? '-') }}',
                                            gedung: '{{ addslashes($transaksi->kamar->gedung->nama_gedung ?? '-') }}'
                                        })"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 font-semibold text-xs shadow-2xs transition-all hover:border-amber-300"
                                        title="Edit Data / Pindah Kamar"
                                    >
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                        </svg>
                                        <span>Pindah Kamar</span>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <form action="{{ route('resepsionis.checkout.process', $transaksi) }}" method="POST" onsubmit="return confirm('Proses Check-out untuk {{ $transaksi->peserta->nama_peserta ?? 'peserta ini' }}? Kamar akan otomatis kosong kembali.')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition-colors">
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
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    Tidak ada peserta yang sedang menginap saat ini.
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

        <!-- Modal Edit & Pindah Kamar -->
        <div 
            x-show="modalOpen" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
            style="display: none;"
        >
            <!-- Backdrop Overlay -->
            <div 
                x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="closeModal()" 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
            ></div>

            <!-- Modal Dialog Content -->
            <div 
                x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white rounded-3xl overflow-hidden shadow-2xl border border-slate-100 w-full max-w-lg mx-auto z-10"
            >
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800">Edit & Pindah Kamar</h3>
                            <p class="text-xs text-slate-500">Pindahkan peserta ke kamar lain atau perbarui data peserta</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        @click="closeModal()" 
                        class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-1.5 rounded-lg transition-colors"
                        title="Tutup Modal"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <form :action="selected ? selected.action : '#'" method="POST">
                    @csrf
                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        
                        <!-- Participant Summary Card -->
                        <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-200/60">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="text-[11px] font-semibold uppercase tracking-wider text-amber-700">Peserta Terpilih</span>
                                    <h4 class="text-sm font-bold text-slate-800" x-text="selected ? selected.nama_peserta : '-'"></h4>
                                    <p class="text-xs text-slate-500" x-text="selected ? selected.diklat : '-'"></p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-[10px] font-medium text-slate-400 block">Kamar Saat Ini:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-xs border border-indigo-200/60">
                                        <span x-text="selected ? 'Kamar ' + selected.nomor_kamar : '-'"></span>
                                        <span class="text-indigo-400 font-normal ml-1" x-text="selected ? '(' + selected.gedung + ')' : ''"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Kamar Baru Dropdown -->
                        <div>
                            <label for="modal_kamar_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Pilih Kamar Tujuan (Baru) <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                name="kamar_id" 
                                id="modal_kamar_id" 
                                x-model="kamarId"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs sm:text-sm transition-all shadow-2xs font-medium"
                            >
                                <option value="" disabled>-- Pilih Kamar Tujuan Baru --</option>
                                @foreach($gedungs as $gedung)
                                    <optgroup label="🏢 {{ $gedung->nama_gedung }}">
                                        @foreach($gedung->kamars as $kamar)
                                            @php
                                                $sisa = max(0, $kamar->kapasitas - $kamar->terisi_count);
                                            @endphp
                                            <option 
                                                value="{{ $kamar->id }}"
                                                :disabled="selected && (selected.kamar_id == {{ $kamar->id }} || {{ $sisa }} <= 0)"
                                            >
                                                Kamar {{ $kamar->nomor_kamar }} (Kapasitas: {{ $kamar->kapasitas }} | Sisa: {{ $sisa }} slot)
                                                @if($sisa <= 0) [PENUH] @endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[11px] text-slate-500">
                                Kamar yang sedang ditempati atau berstatus penuh dinonaktifkan secara otomatis.
                            </p>
                        </div>

                        <!-- Divider -->
                        <div class="relative py-1">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                            <div class="relative flex justify-center text-[10px] uppercase font-bold text-slate-400 tracking-wider">
                                <span class="bg-white px-2">Edit Data Peserta (Opsional)</span>
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="modal_nama_peserta" class="block text-xs font-semibold text-slate-700 mb-1">
                                Nama Lengkap Peserta <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="nama_peserta" 
                                id="modal_nama_peserta" 
                                x-model="namaPeserta"
                                required
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs transition-all"
                            >
                        </div>

                        <!-- NIP / NIK & Instansi Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="modal_nip_nik" class="block text-xs font-semibold text-slate-700 mb-1">
                                    NIP / NIK <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="nip_nik" 
                                    id="modal_nip_nik" 
                                    x-model="nipNik"
                                    required
                                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs transition-all font-mono"
                                >
                            </div>
                            <div>
                                <label for="modal_instansi" class="block text-xs font-semibold text-slate-700 mb-1">
                                    Instansi / Unit Kerja
                                </label>
                                <input 
                                    type="text" 
                                    name="instansi" 
                                    id="modal_instansi" 
                                    x-model="instansi"
                                    placeholder="Contoh: BPSDM / Dinas"
                                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 text-xs transition-all"
                                >
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50/70 border-t border-slate-100 flex items-center justify-end gap-2.5">
                        <button 
                            type="button" 
                            @click="closeModal()" 
                            class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-semibold transition-colors"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-all active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan & Pindahkan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
