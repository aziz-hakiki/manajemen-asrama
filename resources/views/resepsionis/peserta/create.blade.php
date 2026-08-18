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
                        <label for="nip_nik" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            NIP / NIK
                        </label>
                        <input 
                            type="text" 
                            name="nip_nik" 
                            id="nip_nik" 
                            value="{{ old('nip_nik') }}" 
                            placeholder="199001012015011002"
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
