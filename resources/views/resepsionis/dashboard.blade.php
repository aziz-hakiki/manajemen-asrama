<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h1 class="text-xl font-bold text-slate-800 tracking-tight">Resepsionis Dashboard</h1>
            <p class="text-xs text-slate-500 font-medium">Layanan operasional Check-in, Check-out, dan Penghuni Asrama</p>
        </div>
    </x-slot>

    <!-- Welcome Banner Card -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-700 p-6 md:p-8 text-white shadow-md shadow-emerald-200">
        <div class="relative z-10 space-y-1.5 max-w-2xl">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-emerald-100 text-xs font-semibold backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-amber-300"></span>
                <span>Role Resepsionis</span>
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                Selamat bertugas, {{ auth()->user()->name }}! 👋
            </h2>
            <p class="text-emerald-100/90 text-sm md:text-base leading-relaxed">
                Silakan lakukan proses Check-in bagi peserta diklat yang baru tiba, Check-out peserta yang selesai, atau pantau ketersediaan kamar kosong.
            </p>
        </div>
    </div>
</x-app-layout>
