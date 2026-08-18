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

                    <!-- Pilih Peserta -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="peserta_id" class="block text-sm font-semibold text-slate-700">
                                Pilih Peserta Diklat <span class="text-rose-500">*</span>
                            </label>
                            <a href="{{ route('resepsionis.peserta.create', ['checkin_now' => 1]) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                + Tambah Peserta Baru
                            </a>
                        </div>
                        <select 
                            name="peserta_id" 
                            id="peserta_id" 
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                            <option value="">-- Pilih Peserta (yang belum check-in) --</option>
                            @foreach($pesertas as $peserta)
                                <option value="{{ $peserta->id }}" {{ (old('peserta_id', $selectedPesertaId) == $peserta->id) ? 'selected' : '' }}>
                                    {{ $peserta->nama_peserta }} - {{ $peserta->instansi ?? 'Umum' }} ({{ $peserta->diklat->nama_diklat ?? 'Diklat' }})
                                </option>
                            @endforeach
                        </select>
                        @if($pesertas->isEmpty())
                            <p class="text-xs text-amber-600 mt-1.5">
                                Seluruh peserta sudah melakukan check-in atau belum ada data peserta. Silakan tambahkan peserta baru terlebih dahulu.
                            </p>
                        @endif
                    </div>

                    <!-- Pilih Kamar -->
                    <div>
                        <label for="kamar_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Pilih Kamar Kosong <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="kamar_id" 
                            id="kamar_id" 
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                            <option value="">-- Pilih Kamar Siap Huni --</option>
                            @foreach($gedungs as $gedung)
                                @if($gedung->kamars->isNotEmpty())
                                    <optgroup label="🏢 {{ $gedung->nama_gedung }}">
                                        @foreach($gedung->kamars as $kamar)
                                            <option value="{{ $kamar->id }}" {{ (old('kamar_id', $selectedKamarId) == $kamar->id) ? 'selected' : '' }}>
                                                Kamar {{ $kamar->nomor_kamar }} (Kapasitas: {{ $kamar->kapasitas }} Orang)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
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
</x-app-layout>
