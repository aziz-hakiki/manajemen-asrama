<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('resepsionis.checkin.create') }}" class="hover:text-indigo-600">Check-in</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Tambah Peserta Baru</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Registrasi Peserta di Lokasi</h2>
                        <p class="text-xs text-slate-500">Daftarkan peserta diklat baru secara manual sebelum proses check-in</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('resepsionis.peserta.store') }}" method="POST" class="space-y-5">
                @csrf

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
                        <option value="">-- Pilih Program Diklat --</option>
                        @foreach($diklats as $diklat)
                            <option value="{{ $diklat->id }}" {{ old('diklat_id') == $diklat->id ? 'selected' : '' }}>
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
                        value="{{ old('nama_peserta') }}" 
                        placeholder="Contoh: Rian Pratama, S.STP"
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
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
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
                            <option value="Peserta" {{ old('keterangan', 'Peserta') == 'Peserta' ? 'selected' : '' }}>Peserta</option>
                            <option value="Panitia" {{ old('keterangan') == 'Panitia' ? 'selected' : '' }}>Panitia</option>
                            <option value="Narasumber" {{ old('keterangan') == 'Narasumber' ? 'selected' : '' }}>Narasumber</option>
                        </select>
                        @error('keterangan')
                            <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nip_nik" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            NIP / NIK <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            inputmode="numeric"
                            pattern="[0-9]*"
                            name="nip_nik" 
                            id="nip_nik" 
                            value="{{ old('nip_nik') }}" 
                            placeholder="Contoh: 199001012015011002"
                            required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            class="w-full px-4 py-2.5 rounded-xl border @error('nip_nik') border-rose-400 bg-rose-50/30 focus:border-rose-500 focus:ring-rose-200 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 @enderror text-sm transition-all"
                        >
                        <p class="text-xs text-slate-400 mt-1">Wajib angka tanpa spasi atau karakter lain.</p>
                        @error('nip_nik')
                            <p class="text-xs text-rose-600 mt-1.5 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 inline shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="instansi" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Instansi / Asal Kantor
                        </label>
                        <input 
                            type="text" 
                            name="instansi" 
                            id="instansi" 
                            value="{{ old('instansi') }}" 
                            placeholder="Contoh: Dinas ESDM"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                    </div>
                </div>

                <!-- Checkbox Lanjut Check-in -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="checkin_now" 
                            value="1" 
                            checked
                            class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                        >
                        <span class="text-xs font-semibold text-slate-700">
                            Langsung lanjutkan ke formulir Check-in setelah peserta tersimpan
                        </span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('resepsionis.checkin.create') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
                        Simpan Peserta
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
