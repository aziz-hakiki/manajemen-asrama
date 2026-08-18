<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.kamar.index') }}" class="hover:text-indigo-600">Kamar</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Tambah Kamar</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Tambah Kamar Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Pilih gedung dan tentukan nomor serta kapasitas kamar.</p>
            </div>

            <form action="{{ route('admin.kamar.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="gedung_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Pilih Gedung <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="gedung_id" 
                        id="gedung_id" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                        <option value="">-- Pilih Gedung --</option>
                        @foreach($gedungs as $gedung)
                            <option value="{{ $gedung->id }}" {{ old('gedung_id') == $gedung->id ? 'selected' : '' }}>
                                {{ $gedung->nama_gedung }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nomor_kamar" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nomor / Nama Kamar <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nomor_kamar" 
                        id="nomor_kamar" 
                        value="{{ old('nomor_kamar') }}" 
                        placeholder="Contoh: A101, B204"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div>
                    <label for="kapasitas" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Kapasitas Orang <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="kapasitas" 
                        id="kapasitas" 
                        value="{{ old('kapasitas', 2) }}" 
                        min="1"
                        max="10"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                    <p class="text-[11px] text-slate-400 mt-1.5">Jumlah tempat tidur / orang maksimal dalam satu kamar.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.kamar.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
                        Simpan Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
