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
                            NIP / NIK <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="text" 
                                inputmode="numeric"
                                pattern="[0-9]*"
                                name="nip_nik" 
                                id="nip_nik" 
                                value="{{ old('nip_nik', $peserta->nip_nik) }}" 
                                placeholder="Contoh: 198901012015011001"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border @error('nip_nik') border-rose-400 bg-rose-50/30 focus:border-rose-500 focus:ring-rose-200 @else border-slate-200 focus:border-indigo-500 focus:ring-indigo-200 @enderror text-sm transition-all"
                            >
                            <div id="nip-spinner" class="hidden absolute right-3 top-3">
                                <svg class="animate-spin h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Wajib angka tanpa spasi atau karakter lain.</p>

                        {{-- Alert Real-time AJAX saat NIP/NIK sudah terdaftar pada peserta lain --}}
                        <div id="nip-alert" class="hidden mt-2 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 items-start gap-2 shadow-xs">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <span class="font-semibold block">NIP / NIK Sudah Digunakan!</span>
                                <span id="nip-alert-text"></span>
                            </div>
                        </div>

                        {{-- Alert Error Validasi Server --}}
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
                            value="{{ old('instansi', $peserta->instansi) }}" 
                            placeholder="Contoh: BPSDM Prov. Jabar"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.peserta.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" id="btn-submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold shadow-sm transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nipInput = document.getElementById('nip_nik');
            const nipAlert = document.getElementById('nip-alert');
            const nipAlertText = document.getElementById('nip-alert-text');
            const nipSpinner = document.getElementById('nip-spinner');
            const submitBtn = document.getElementById('btn-submit');
            const currentPesertaId = "{{ $peserta->id }}";

            let timeout = null;

            function checkNip(nip) {
                if (!nip) {
                    nipAlert.classList.add('hidden');
                    nipAlert.classList.remove('flex');
                    nipInput.classList.remove('border-rose-500', 'bg-rose-50/30', 'focus:border-rose-500', 'focus:ring-rose-200');
                    if (submitBtn) submitBtn.disabled = false;
                    return;
                }

                if (nipSpinner) nipSpinner.classList.remove('hidden');

                fetch(`{{ route('admin.peserta.check-nip') }}?nip_nik=${encodeURIComponent(nip)}&ignore_id=${currentPesertaId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (nipSpinner) nipSpinner.classList.add('hidden');
                        if (data.exists) {
                            nipAlertText.textContent = data.message;
                            nipAlert.classList.remove('hidden');
                            nipAlert.classList.add('flex');
                            nipInput.classList.add('border-rose-500', 'bg-rose-50/30', 'focus:border-rose-500', 'focus:ring-rose-200');
                            nipInput.classList.remove('border-slate-200', 'focus:border-indigo-500', 'focus:ring-indigo-200');
                            if (submitBtn) submitBtn.disabled = true;
                        } else {
                            nipAlert.classList.add('hidden');
                            nipAlert.classList.remove('flex');
                            nipInput.classList.remove('border-rose-500', 'bg-rose-50/30', 'focus:border-rose-500', 'focus:ring-rose-200');
                            nipInput.classList.add('border-slate-200', 'focus:border-indigo-500', 'focus:ring-indigo-200');
                            if (submitBtn) submitBtn.disabled = false;
                        }
                    })
                    .catch(() => {
                        if (nipSpinner) nipSpinner.classList.add('hidden');
                    });
            }

            nipInput.addEventListener('input', function () {
                // Wajib angka: bersihkan semua karakter non-angka secara otomatis
                this.value = this.value.replace(/[^0-9]/g, '');

                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    checkNip(this.value.trim());
                }, 350);
            });
        });
    </script>
</x-app-layout>
