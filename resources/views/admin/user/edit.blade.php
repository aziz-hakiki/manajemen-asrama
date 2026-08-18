<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.user.index') }}" class="hover:text-indigo-600">Pengguna</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Edit Pengguna</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Edit Akun Pengguna</h2>
                <p class="text-xs text-slate-500 mt-1">Perbarui data profil atau peran akun pengguna.</p>
            </div>

            <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $user->name) }}" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $user->email) }}" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Password Baru <span class="text-xs text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah password)</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Peran / Hak Akses (Role) <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        name="role" 
                        id="role" 
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Akses Penuh Master Data)</option>
                        <option value="resepsionis" {{ old('role', $user->role) === 'resepsionis' ? 'selected' : '' }}>Resepsionis (Check-in & Check-out)</option>
                        <option value="pimpinan" {{ old('role', $user->role) === 'pimpinan' ? 'selected' : '' }}>Pimpinan (Laporan & Monitoring)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.user.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
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
