<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Pimpinan Dashboard</h1>
            <p class="text-xs text-slate-500 font-medium">Monitoring & Laporan Tingkat Hunian Asrama PPSDMAP</p>
        </div>
    </x-slot>

    <!-- Welcome Banner Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 to-indigo-800 p-6 md:p-8 text-white shadow-md shadow-blue-200">
        <div class="relative z-10 space-y-1.5 max-w-2xl">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-blue-100 text-xs font-semibold backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Role Pimpinan</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Selamat datang, {{ auth()->user()->name }}! 👋
            </h2>
            <p class="text-blue-100/90 text-sm md:text-base leading-relaxed">
                Pantau laporan tingkat hunian kamar, ketersediaan per gedung, dan rekapitulasi data peserta diklat secara komprehensif.
            </p>
        </div>
    </div>
</x-app-layout>
