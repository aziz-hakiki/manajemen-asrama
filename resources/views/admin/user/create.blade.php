<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.user.index') }}" class="hover:text-indigo-600">Pengguna</a>
            <span>/</span>
            <span class="font-semibold text-slate-800">Tambah Pengguna</span>
        </div>
    </x-slot>

    <x-alert />

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-slate-800">Buat Akun Pengguna Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Tentukan nama, email, password, dan peran (role) untuk akses sistem.</p>
            </div>

            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        placeholder="Contoh: Budi Santoso"
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
                        value="{{ old('email') }}" 
                        placeholder="nama@example.com"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-all"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Minimal 8 karakter"
                        required
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
                        <option value="">-- Pilih Peran Pengguna --</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Akses Penuh Master Data)</option>
                        <option value="resepsionis" {{ old('role') === 'resepsionis' ? 'selected' : '' }}>Resepsionis (Check-in & Check-out)</option>
                        <option value="pimpinan" {{ old('role') === 'pimpinan' ? 'selected' : '' }}>Pimpinan (Laporan & Monitoring)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.user.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
