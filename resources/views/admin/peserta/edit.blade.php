<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.peserta.index') }}" class="hover:text-indigo-600">Peserta</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Edit Peserta</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Edit Data Peserta</h2>
                <p class="text-xs text-slate-500 mt-1">Perbarui data peserta diklat.</p>
            </div>

            <form action="{{ route('admin.peserta.update', $peserta) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="diklat_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Pilih Program Diklat <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="diklat_id" 
                        id="diklat_id" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                        @foreach($diklats as $diklat)
                            <option value="{{ $diklat->id }}" {{ old('diklat_id', $peserta->diklat_id) == $diklat->id ? 'selected' : '' }}>
                                {{ $diklat->nama_diklat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nama_peserta" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap Peserta <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama_peserta" 
                        id="nama_peserta" 
                        value="{{ old('nama_peserta', $peserta->nama_peserta) }}" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Jenis Kelamin
                        </label>
                        <select 
                            name="jenis_kelamin" 
                            id="jenis_kelamin" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Keterangan (Sebagai)
                        </label>
                        <select 
                            name="keterangan" 
                            id="keterangan" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                            <option value="Peserta" {{ old('keterangan', $peserta->keterangan ?? 'Peserta') == 'Peserta' ? 'selected' : '' }}>Peserta</option>
                            <option value="Narasumber" {{ old('keterangan', $peserta->keterangan) == 'Narasumber' ? 'selected' : '' }}>Narasumber</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nip_nik" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            NIP / NIK
                        </label>
                        <input 
                            type="text" 
                            name="nip_nik" 
                            id="nip_nik" 
                            value="{{ old('nip_nik', $peserta->nip_nik) }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                    </div>

                    <div>
                        <label for="instansi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Instansi / Asal Kantor
                        </label>
                        <input 
                            type="text" 
                            name="instansi" 
                            id="instansi" 
                            value="{{ old('instansi', $peserta->instansi) }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.peserta.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
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
