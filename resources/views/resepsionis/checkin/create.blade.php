<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Formulir Check-in Asrama</h1>
            <p class="text-xs text-slate-500 font-medium">Layanan alokasi kamar bagi peserta diklat yang baru tiba</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Check-in -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Proses Check-in Peserta</h2>
                        <p class="text-xs text-slate-500">Pilih peserta dan tentukan kamar yang akan ditempati</p>
                    </div>
                </div>

                <form action="{{ route('resepsionis.checkin.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Pilih Peserta (Searchable Dropdown / Live Auto-Filter & Sort) -->
                    @php
                        $pesertaList = $pesertas->map(function($p) {
                            return [
                                'id' => (string) $p->id,
                                'nama' => $p->nama_peserta,
                                'nip' => $p->nip_nik ?? '',
                                'instansi' => $p->instansi ?? 'Umum',
                                'diklat' => $p->diklat->nama_diklat ?? 'Program Diklat',
                                'searchText' => strtolower($p->nama_peserta . ' ' . ($p->nip_nik ?? '') . ' ' . ($p->instansi ?? '') . ' ' . ($p->diklat->nama_diklat ?? '')),
                            ];
                        })->values();
                        $initialPesertaId = (string) old('peserta_id', $selectedPesertaId ?? '');
                    @endphp

                    <div 
                        x-data="pesertaCombobox({{ Js::from($pesertaList) }}, '{{ $initialPesertaId }}')"
                        class="relative"
                    >
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="peserta_search_input" class="block text-sm font-semibold text-slate-700">
                                Pilih Peserta Diklat <span class="text-rose-500">*</span>
                            </label>
                            <a href="{{ route('resepsionis.peserta.create', ['checkin_now' => 1]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Peserta Baru</span>
                            </a>
                        </div>

                        <!-- Hidden Real Form Input for Backend Validation & Submission -->
                        <input type="hidden" name="peserta_id" id="peserta_id" :value="selectedId" required>

                        <!-- Input Search & Trigger Box -->
                        <div class="relative">
                            <input 
                                type="text"
                                id="peserta_search_input"
                                x-ref="searchInput"
                                x-model="search"
                                @focus="openDropdown()"
                                @input="onInput()"
                                @keydown.down.prevent="onKeyDownDown()"
                                @keydown.up.prevent="onKeyDownUp()"
                                @keydown.enter.prevent="onKeyDownEnter($event)"
                                @keydown.escape="closeDropdown()"
                                placeholder="Ketik untuk mencari nama peserta, NIP, instansi, atau program diklat..."
                                autocomplete="off"
                                class="w-full px-4 pr-20 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all shadow-2xs"
                                :class="{'border-indigo-400 ring-2 ring-indigo-100': isOpen, 'border-emerald-500 bg-emerald-50/20': selectedItem}"
                            >

                            <!-- Right Action Icons (Clear & Dropdown Toggle) -->
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1">
                                <button 
                                    type="button" 
                                    x-show="search.length > 0 || selectedId !== ''" 
                                    @click="clearSelection()"
                                    title="Hapus pencarian / pilihan"
                                    class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <button 
                                    type="button" 
                                    @click="toggleDropdown()" 
                                    class="p-1 rounded-md text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
                                >
                                    <svg 
                                        class="w-4 h-4 transition-transform duration-200" 
                                        :class="{'rotate-180 text-indigo-600': isOpen}"
                                        fill="none" 
                                        stroke="currentColor" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Dropdown Menu / Results List -->
                        <div 
                            x-show="isOpen" 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            @click.outside="closeDropdown()"
                            class="absolute z-50 mt-1.5 w-full bg-white rounded-xl border border-slate-200 shadow-xl overflow-hidden"
                            style="display: none;"
                        >
                            <!-- Search info bar -->
                            <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                                <span>
                                    Ditemukan <strong class="text-slate-800" x-text="filteredItems.length"></strong> peserta belum check-in
                                </span>
                                <span class="text-slate-400 text-[10px]">Gunakan panah ↑↓ & Enter</span>
                            </div>

                            <ul 
                                x-ref="listContainer"
                                class="max-h-60 overflow-y-auto divide-y divide-slate-100 text-sm focus:outline-none"
                            >
                                <template x-for="(item, index) in filteredItems" :key="item.id">
                                    <li 
                                        :data-index="index"
                                        @click="selectItem(item)"
                                        @mouseenter="highlightedIndex = index"
                                        :class="{
                                            'bg-indigo-50/80 text-indigo-900 font-medium': highlightedIndex === index,
                                            'bg-emerald-50/40': selectedId === item.id && highlightedIndex !== index,
                                            'text-slate-700': highlightedIndex !== index && selectedId !== item.id
                                        }"
                                        class="px-4 py-2.5 cursor-pointer hover:bg-indigo-50 transition-colors flex items-center justify-between gap-3"
                                    >
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-slate-800 text-sm truncate" x-text="item.nama"></span>
                                                <span 
                                                    x-show="item.nip" 
                                                    class="text-[11px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-mono"
                                                    x-text="item.nip"
                                                ></span>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1 text-xs text-slate-500">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px]">
                                                    🏛️ <span x-text="item.instansi"></span>
                                                </span>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-medium">
                                                    🎓 <span x-text="item.diklat"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Selected Checkmark -->
                                        <div x-show="selectedId === item.id" class="text-emerald-600 shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </li>
                                </template>

                                <!-- Empty State when search matches nothing -->
                                <li 
                                    x-show="filteredItems.length === 0" 
                                    class="p-6 text-center text-slate-500 space-y-2"
                                >
                                    <div class="w-10 h-10 mx-auto rounded-full bg-amber-50 text-amber-500 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-700">Tidak ada peserta yang cocok</p>
                                    <p class="text-[11px] text-slate-400">
                                        Tidak ditemukan peserta dengan kata kunci "<span class="font-medium text-slate-600" x-text="search"></span>"
                                    </p>
                                    <div class="pt-2">
                                        <a 
                                            href="{{ route('resepsionis.peserta.create', ['checkin_now' => 1]) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition-colors"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span>+ Daftarkan Peserta Baru</span>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Summary Card for Selected Participant -->
                        <template x-if="selectedItem">
                            <div class="mt-2.5 p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/80 flex items-center justify-between gap-3 animate-fadeIn">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-emerald-950" x-text="selectedItem.nama"></span>
                                            <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100/80 px-1.5 py-0.5 rounded">Terpilih</span>
                                        </div>
                                        <p class="text-[11px] text-emerald-800">
                                            <span x-text="selectedItem.instansi"></span> &bull; <span x-text="selectedItem.diklat"></span>
                                        </p>
                                    </div>
                                </div>

                                <button 
                                    type="button" 
                                    @click="clearSelection()"
                                    class="text-xs text-slate-400 hover:text-rose-600 font-medium px-2 py-1 rounded-md hover:bg-white/80 transition-colors"
                                >
                                    Ganti
                                </button>
                            </div>
                        </template>

                        @if($pesertas->isEmpty())
                            <p class="text-xs text-amber-600 mt-2 bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                                ⚠️ Seluruh peserta sudah melakukan check-in atau belum ada data peserta. Silakan tambahkan peserta baru terlebih dahulu.
                            </p>
                        @endif

                        @error('peserta_id')
                            <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Kamar -->
                    <div>
                        <label for="kamar_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Pilih Kamar Asrama <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="kamar_id" 
                            id="kamar_id" 
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                            <option value="">-- Pilih Kamar Tersedia --</option>
                            @foreach($gedungs as $gedung)
                                @if($gedung->kamars->isNotEmpty())
                                    <optgroup label="🏢 {{ $gedung->nama_gedung }}">
                                        @foreach($gedung->kamars as $kamar)
                                            @php
                                                $terisi = $kamar->terisi_count;
                                                $statusKet = $terisi === 0 ? 'Kosong' : 'Terisi ' . $terisi . ' orang';
                                            @endphp
                                            <option value="{{ $kamar->id }}" {{ (old('kamar_id', $selectedKamarId) == $kamar->id) ? 'selected' : '' }}>
                                                {{ $gedung->nama_gedung }} kamar {{ $kamar->nomor_kamar }} (Kapasitas {{ $kamar->kapasitas }} Orang) {{ $statusKet }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1.5">Kamar dengan kapasitas 3 orang dapat diisi oleh hingga 3 peserta berbeda.</p>
                    </div>

                    <!-- Tanggal & Waktu Masuk -->
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tanggal & Waktu Masuk (Check-in) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="datetime-local" 
                            name="tanggal_masuk" 
                            id="tanggal_masuk" 
                            value="{{ old('tanggal_masuk', now()->format('Y-m-d\TH:i')) }}" 
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                        <p class="text-[11px] text-slate-400 mt-1.5">Waktu otomatis terisi saat ini, Anda dapat menyesuaikannya jika diperlukan.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('resepsionis.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-colors">
                            Simpan & Check-in
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Panduan / Info Cepat -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Petunjuk Alur Check-in</span>
                </h3>
                <ol class="list-decimal list-inside space-y-2 text-xs text-slate-600 leading-relaxed">
                    <li>Pilih nama peserta yang telah terdaftar dari daftar di sebelah kiri.</li>
                    <li>Jika peserta belum terdata, klik <strong>+ Tambah Peserta Baru</strong>.</li>
                    <li>Tentukan kamar yang berstatus kosong di gedung yang diinginkan.</li>
                    <li>Klik <strong>Simpan & Check-in</strong>. Kamar otomatis berubah status menjadi <em>Terisi</em> dan peserta tercatat sebagai penghuni aktif.</li>
                </ol>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl border border-indigo-100 p-6">
                <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-wider mb-2">Pintasan</h4>
                <div class="space-y-2">
                    <a href="{{ route('resepsionis.kamar-kosong.index') }}" class="block p-2.5 rounded-xl bg-white text-xs font-semibold text-slate-700 hover:text-indigo-600 shadow-2xs transition-colors">
                        🛏️ Cek Daftar Kamar Kosong &rarr;
                    </a>
                    <a href="{{ route('resepsionis.penghuni.index') }}" class="block p-2.5 rounded-xl bg-white text-xs font-semibold text-slate-700 hover:text-indigo-600 shadow-2xs transition-colors">
                        👥 Cek Daftar Penghuni Aktif &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pesertaCombobox(items, initialId) {
            return {
                items: items || [],
                selectedId: initialId ? String(initialId) : '',
                search: '',
                isOpen: false,
                highlightedIndex: 0,

                init() {
                    if (this.selectedId) {
                        const item = this.items.find(i => String(i.id) === String(this.selectedId));
                        if (item) {
                            this.search = item.nama;
                        }
                    }
                },

                get selectedItem() {
                    return this.items.find(i => String(i.id) === String(this.selectedId)) || null;
                },

                get filteredItems() {
                    if (!this.search || !this.search.trim()) {
                        return this.items;
                    }
                    
                    // If the current search text exactly matches the already selected item, show all items
                    if (this.selectedItem && this.search.trim() === this.selectedItem.nama.trim()) {
                        return this.items;
                    }

                    const q = this.search.toLowerCase().trim();
                    return this.items
                        .filter(item => item.searchText.includes(q))
                        .sort((a, b) => {
                            const aStart = a.nama.toLowerCase().startsWith(q);
                            const bStart = b.nama.toLowerCase().startsWith(q);
                            if (aStart && !bStart) return -1;
                            if (!aStart && bStart) return 1;
                            return a.nama.localeCompare(b.nama);
                        });
                },

                openDropdown() {
                    this.isOpen = true;
                    this.highlightedIndex = 0;
                },

                closeDropdown() {
                    this.isOpen = false;
                    if (this.selectedItem) {
                        this.search = this.selectedItem.nama;
                    } else {
                        this.search = '';
                    }
                },

                toggleDropdown() {
                    if (this.isOpen) {
                        this.closeDropdown();
                    } else {
                        this.openDropdown();
                        this.$nextTick(() => this.$refs.searchInput.focus());
                    }
                },

                selectItem(item) {
                    if (!item) return;
                    this.selectedId = String(item.id);
                    this.search = item.nama;
                    this.isOpen = false;
                },

                clearSelection() {
                    this.selectedId = '';
                    this.search = '';
                    this.isOpen = true;
                    this.highlightedIndex = 0;
                    this.$nextTick(() => this.$refs.searchInput.focus());
                },

                onInput() {
                    this.isOpen = true;
                    this.highlightedIndex = 0;
                    if (this.selectedItem && this.search.trim() !== this.selectedItem.nama.trim()) {
                        this.selectedId = '';
                    }
                },

                onKeyDownDown() {
                    if (!this.isOpen) {
                        this.openDropdown();
                        return;
                    }
                    if (this.filteredItems.length > 0 && this.highlightedIndex < this.filteredItems.length - 1) {
                        this.highlightedIndex++;
                        this.scrollToHighlighted();
                    }
                },

                onKeyDownUp() {
                    if (!this.isOpen) {
                        this.openDropdown();
                        return;
                    }
                    if (this.highlightedIndex > 0) {
                        this.highlightedIndex--;
                        this.scrollToHighlighted();
                    }
                },

                onKeyDownEnter(e) {
                    if (this.isOpen && this.filteredItems.length > 0) {
                        const item = this.filteredItems[this.highlightedIndex] || this.filteredItems[0];
                        if (item) {
                            this.selectItem(item);
                        }
                    }
                },

                scrollToHighlighted() {
                    this.$nextTick(() => {
                        const container = this.$refs.listContainer;
                        const el = container ? container.querySelector(`[data-index="${this.highlightedIndex}"]`) : null;
                        if (el && container) {
                            const elTop = el.offsetTop;
                            const elBottom = elTop + el.offsetHeight;
                            const containerTop = container.scrollTop;
                            const containerBottom = containerTop + container.offsetHeight;
                            if (elTop < containerTop) {
                                container.scrollTop = elTop;
                            } else if (elBottom > containerBottom) {
                                container.scrollTop = elBottom - container.offsetHeight;
                            }
                        }
                    });
                }
            };
        }
    </script>
</x-app-layout>
