<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Data Diklat</h1>
            <p class="text-xs text-slate-500 font-medium">Manajemen program pelatihan dan agenda diklat PPSDMAP</p>
        </div>
    </x-slot>

    <x-alert />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Program Diklat</h2>
            <p class="text-xs text-slate-500">Kelola kegiatan diklat yang sedang maupun akan berlangsung</p>
        </div>
        <a href="{{ route('admin.diklat.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Diklat</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('admin.diklat.index') }}" class="flex items-center gap-3">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama kegiatan diklat..." 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>
            <button type="submit" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.diklat.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kegiatan Diklat</th>
                        <th class="px-6 py-4">Jadwal Pelaksanaan</th>
                        <th class="px-6 py-4">Jumlah Peserta</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($diklats as $index => $diklat)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-400">
                                {{ $diklats->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $diklat->nama_diklat }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-600">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ \Carbon\Carbon::parse($diklat->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                                    <span>s/d</span>
                                    <span>{{ \Carbon\Carbon::parse($diklat->tanggal_selesai)->translatedFormat('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.peserta.index', ['diklat_id' => $diklat->id]) }}" class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors">
                                    <span>{{ $diklat->pesertas_count }} Peserta</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.diklat.edit', $diklat) }}" class="p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.diklat.destroy', $diklat) }}" method="POST" onsubmit="return confirm('Menghapus diklat akan menghapus seluruh data peserta di dalamnya. Lanjutkan?')">
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
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data diklat yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($diklats->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $diklats->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
