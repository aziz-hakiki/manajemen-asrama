<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Proses Check-out Asrama</h1>
            <p class="text-xs text-slate-500 font-medium">Layanan kepulangan peserta dan pelepasan status kamar asrama</p>
        </div>
    </x-slot>

    <x-alert />

    <!-- Top Info Card -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Daftar Tamu Menginap (Siap Check-out)</h2>
            <p class="text-xs text-slate-500">Pilih peserta yang telah menyelesaikan agenda dan akan meninggalkan asrama</p>
        </div>
        <div class="text-xs font-semibold px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
            Total Penghuni: {{ $penghunis->total() }} Orang
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4">
        <form method="GET" action="{{ route('resepsionis.checkout.index') }}" class="flex items-center gap-3">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama peserta, NIP/NIK, atau nomor kamar..." 
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-xs transition-all"
                >
            </div>
            <button type="submit" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold shadow-xs transition-colors">
                Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('resepsionis.checkout.index') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50 text-xs font-semibold transition-colors">
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
                        <th class="px-6 py-4">Nama Peserta</th>
                        <th class="px-6 py-4">Kamar & Gedung</th>
                        <th class="px-6 py-4">Program Diklat</th>
                        <th class="px-6 py-4">Tanggal Masuk</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($penghunis as $index => $transaksi)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-400">
                                {{ $penghunis->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $transaksi->peserta->nama_peserta ?? '-' }}</span>
                                    <span class="text-xs text-slate-400">{{ $transaksi->peserta->instansi ?? 'Umum' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($transaksi->kamar)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs">
                                        <span>Kamar {{ $transaksi->kamar->nomor_kamar }}</span>
                                        <span class="text-indigo-400 font-normal">({{ $transaksi->kamar->gedung->nama_gedung ?? '' }})</span>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-700">
                                {{ $transaksi->peserta->diklat->nama_diklat ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('resepsionis.checkout.process', $transaksi) }}" method="POST" onsubmit="return confirm('Proses Check-out untuk {{ $transaksi->peserta->nama_peserta ?? 'peserta ini' }}? Kamar akan otomatis kosong kembali.')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Check-out</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada peserta yang sedang menginap saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penghunis->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $penghunis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
