<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.gedung.index') }}" class="hover:text-indigo-600">Gedung</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Edit Gedung</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Edit Data Gedung</h2>
                <p class="text-xs text-slate-500 mt-1">Perbarui nama gedung asrama.</p>
            </div>

            <form action="{{ route('admin.gedung.update', $gedung) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama_gedung" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Gedung <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama_gedung" 
                        id="nama_gedung" 
                        value="{{ old('nama_gedung', $gedung->nama_gedung) }}" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.gedung.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
