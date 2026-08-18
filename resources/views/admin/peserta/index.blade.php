<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Data Peserta</h1>
            <p class="text-xs text-slate-500 font-medium">Manajemen data peserta diklat dan alokasi asrama</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Top Action & Filter Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Peserta Diklat</h2>
            <p class="text-xs text-slate-500">Kelola dan pantau seluruh peserta diklat yang terdaftar</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.peserta.import') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold shadow-xs transition-colors">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Import CSV</span>
            </a>
            <a href="{{ route('admin.peserta.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Peserta</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('admin.peserta.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Search -->
            <div>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, NIP/NIK, instansi..." 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>

            <!-- Filter Diklat -->
            <div>
                <select name="diklat_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all">
                    <option value="">Semua Kegiatan Diklat</option>
                    @foreach($diklats as $d)
                        <option value="{{ $d->id }}" {{ request('diklat_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_diklat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'diklat_id']))
                    <a href="{{ route('admin.peserta.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">NIP / NIK</th>
                        <th class="px-6 py-4">Instansi</th>
                        <th class="px-6 py-4">Program Diklat</th>
                        <th class="px-6 py-4">Status Menginap</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesertas as $index => $peserta)
                        @php
                            $activeTransaksi = $peserta->transaksi->first();
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-400">
                                {{ $pesertas->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $peserta->nama_peserta }}
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-slate-600">
                                {{ $peserta->nip_nik ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600">
                                {{ $peserta->instansi ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $peserta->diklat->nama_diklat ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($activeTransaksi && $activeTransaksi->kamar)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Kamar {{ $activeTransaksi->kamar->nomor_kamar }} ({{ $activeTransaksi->kamar->gedung->nama_gedung ?? '' }})
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                        Belum Check-in
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.peserta.edit', $peserta) }}" class="p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.peserta.destroy', $peserta) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peserta ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data peserta yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pesertas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $pesertas->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
