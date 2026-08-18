<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.peserta.index') }}" class="hover:text-indigo-600">Peserta</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Import CSV</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Import Data Peserta dari CSV</h2>
                        <p class="text-xs text-slate-500">Unggah daftar peserta secara massal dari panitia diklat</p>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Format File -->
            <div class="p-4 mb-6 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Format Kolom File CSV (.csv):</span>
                </div>
                <p>Pastikan file CSV memiliki struktur kolom berurutan sebagai berikut:</p>
                <div class="p-2.5 rounded-lg bg-white border border-slate-200 font-mono text-[11px] text-slate-700">
                    Nama Peserta, NIP/NIK, Instansi
                </div>
                <p class="text-[11px] text-slate-400">Baris pertama (header) akan otomatis dilewati oleh sistem saat pemrosesan.</p>
            </div>

            <form action="{{ route('admin.peserta.import.process') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="diklat_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tentukan Program Diklat <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="diklat_id" 
                        id="diklat_id" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                        <option value="">-- Pilih Kegiatan Diklat --</option>
                        @foreach($diklats as $diklat)
                            <option value="{{ $diklat->id }}" {{ old('diklat_id', request('diklat_id')) == $diklat->id ? 'selected' : '' }}>
                                {{ $diklat->nama_diklat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="file_peserta" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Pilih File CSV <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="file" 
                        name="file_peserta" 
                        id="file_peserta" 
                        accept=".csv,text/csv"
                        required
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer"
                    >
                    <p class="text-[11px] text-slate-400 mt-1.5">Ukuran maksimal file: 5 MB</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.peserta.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold shadow-sm transition-colors">
                        Mulai Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
