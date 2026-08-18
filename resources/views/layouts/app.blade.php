<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Asrama PPSDMAP') }}</title>

        <!-- Favicon / Icon Title -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
        </style>
    </head>
    <body class="h-full font-sans antialiased text-slate-800 bg-slate-100">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen w-full overflow-hidden">
            
            <!-- Mobile Sidebar Backdrop Overlay -->
            <div 
                x-show="sidebarOpen" 
                x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarOpen = false" 
                class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs md:hidden"
                style="display: none;"
            ></div>

            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden bg-slate-100">
                <!-- Topbar -->
                <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0 z-10 shadow-xs">
                    <!-- Mobile Hamburger Button & Title -->
                    <div class="flex items-center gap-3 md:hidden">
                        <button 
                            @click="sidebarOpen = !sidebarOpen" 
                            type="button"
                            class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition-colors"
                            aria-label="Buka Menu"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-7 w-auto object-contain">
                            <span class="font-bold text-slate-800 tracking-tight text-sm sm:text-base">Asrama PPSDMAP</span>
                        </div>
                    </div>

                    <!-- Desktop Header Left Content (Title / Breadcrumb) -->
                    <div class="hidden md:flex items-center gap-2">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-lg font-bold text-slate-800 tracking-tight">Dashboard Asrama</h1>
                        @endisset
                    </div>

                    <!-- Right Quick Info -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-emerald-700 bg-emerald-50 py-1.5 px-3 rounded-full border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Sistem Online</span>
                        </div>

                        <!-- Date Display -->
                        <div class="text-xs text-slate-500 font-medium">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </header>

                <!-- Page Main Scrollable Content Area -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-100">
                    <div class="max-w-7xl mx-auto space-y-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
