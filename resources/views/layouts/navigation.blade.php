<!-- Left Sidebar Navigation -->
<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col h-full shrink-0 transition-transform duration-200 ease-in-out md:translate-x-0 shadow-lg md:shadow-none"
>
    <!-- Logo & Brand Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-9 w-auto object-contain">
            <div class="flex flex-col">
                <span class="font-bold text-slate-800 text-sm leading-tight">Asrama App</span>
                <span class="text-[10px] font-semibold text-indigo-600 uppercase tracking-wider">PPSDMAP</span>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button 
            @click="sidebarOpen = false" 
            type="button"
            class="md:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 px-4 py-5 space-y-6 overflow-y-auto">
        <!-- Section: Main Menu -->
        <div>
            <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                MAIN MENU
            </div>
            <div class="space-y-1">
                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('resepsionis.dashboard') || request()->routeIs('pimpinan.dashboard')">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </x-sidebar-link>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
            <!-- Section: Kelola Data (Admin) -->
            <div>
                <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    KELOLA DATA
                </div>
                <div class="space-y-1">
                    <x-sidebar-link :href="route('admin.gedung.index')" :active="request()->routeIs('admin.gedung.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.gedung.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Gedung</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.kamar.index')" :active="request()->routeIs('admin.kamar.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.kamar.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Kamar</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.diklat.index')" :active="request()->routeIs('admin.diklat.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.diklat.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Kegiatan</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.peserta.index')" :active="request()->routeIs('admin.peserta.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.peserta.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Tamu</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.peserta.import')" :active="request()->routeIs('admin.peserta.import')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.peserta.import') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span>Import</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.user.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Pengguna</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('admin.laporan.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Laporan</span>
                    </x-sidebar-link>
                </div>
            </div>
        @elseif(auth()->user()->role === 'resepsionis')
            <!-- Section: Operasional (Resepsionis) -->
            <div>
                <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    OPERASIONAL
                </div>
                <div class="space-y-1">
                    <x-sidebar-link :href="route('resepsionis.checkin.create')" :active="request()->routeIs('resepsionis.checkin.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('resepsionis.checkin.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>Check-in</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('resepsionis.checkout.index')" :active="request()->routeIs('resepsionis.checkout.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('resepsionis.checkout.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Check-out</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('resepsionis.kamar-kosong.index')" :active="request()->routeIs('resepsionis.kamar-kosong.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('resepsionis.kamar-kosong.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span>Kamar Kosong</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('resepsionis.penghuni.index')" :active="request()->routeIs('resepsionis.penghuni.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('resepsionis.penghuni.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Penghuni Aktif</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('resepsionis.peserta.create')" :active="request()->routeIs('resepsionis.peserta.*')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('resepsionis.peserta.*') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>Tambah Peserta</span>
                    </x-sidebar-link>
                </div>
            </div>
        @elseif(auth()->user()->role === 'pimpinan')
            <!-- Section: Laporan (Pimpinan) -->
            <div>
                <div class="px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    LAPORAN
                </div>
                <div class="space-y-1">
                    <x-sidebar-link :href="route('pimpinan.laporan.hunian')" :active="request()->routeIs('pimpinan.laporan.hunian')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('pimpinan.laporan.hunian') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Laporan Hunian</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('pimpinan.laporan.gedung')" :active="request()->routeIs('pimpinan.laporan.gedung')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('pimpinan.laporan.gedung') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Laporan Per Gedung</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('pimpinan.laporan.diklat')" :active="request()->routeIs('pimpinan.laporan.diklat')">
                        <svg class="w-5 h-5 text-slate-500 {{ request()->routeIs('pimpinan.laporan.diklat') ? 'text-indigo-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Laporan Per Diklat</span>
                    </x-sidebar-link>
                </div>
            </div>
        @endif
    </nav>

    <!-- User Profile Card -->
    <div class="p-4 border-t border-slate-200 shrink-0 bg-white">
        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
            <div class="flex items-center gap-3">
                <!-- Avatar Initials -->
                <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>

                <!-- Name & Role -->
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="font-bold text-slate-800 text-xs truncate leading-tight">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="text-[10px] font-semibold text-indigo-600 uppercase tracking-wide">
                        {{ Auth::user()->role }}
                    </span>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button 
                        type="submit" 
                        title="Log Out"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
