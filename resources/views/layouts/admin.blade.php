<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SIPIRANG</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        :root {
            --sidebar-w: 256px;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">

<div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

    {{-- ────────────────── SIDEBAR ────────────────── --}}
    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-transition.opacity></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-slate-900 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex h-16 items-center gap-3 border-b border-slate-700/50 px-5">
            <div class="flex h-9 w-9 items-center justify-center">
                <x-unm-logo class="h-8 w-8" />
            </div>
            <div>
                <div class="text-sm font-bold text-white tracking-wider">SIPIRANG</div>
                <div class="text-xs text-slate-400">Panel Admin</div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            @php
                $currentRoute = request()->route()->getName();
                $navItem = function(string $icon, string $label, string $route, string $matchPrefix = '') use ($currentRoute): string {
                    $isActive = str_starts_with($currentRoute, $matchPrefix ?: $route);
                    $activeClass = $isActive
                        ? 'bg-indigo-600 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white';
                    $url = route($route);
                    return <<<HTML
                    <a href="{$url}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150 {$activeClass}">
                        {$icon}
                        {$label}
                    </a>
                    HTML;
                };
            @endphp

            <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Menu Utama</p>

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                'Dashboard', 'admin.dashboard', 'admin.dashboard'
            ) !!}

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                'Jadwal Kalender', 'admin.calendar'
            ) !!}

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                'Booking', 'admin.bookings.index', 'admin.bookings'
            ) !!}

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                'Laporan', 'admin.reports.index', 'admin.reports'
            ) !!}

            <p class="mt-4 px-3 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Manajemen</p>

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                'Ruangan', 'admin.rooms.index', 'admin.rooms'
            ) !!}

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>',
                'Gedung', 'admin.buildings.index', 'admin.buildings'
            ) !!}

            @if(auth()->user()?->isSysadmin())
            <p class="mt-4 px-3 pb-1 text-xs font-semibold uppercase tracking-widest text-slate-500">Sistem</p>

            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                'Pengaturan', 'admin.settings', 'admin.settings'
            ) !!}
            {!! $navItem(
                '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
                'Users', 'admin.users.index', 'admin.users'
            ) !!}
            @endif
        </nav>

        {{-- User Info --}}
        <div class="border-t border-slate-700/50 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500/20 text-indigo-300 text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-white">{{ auth()->user()?->name }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()?->role_label }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-400 hover:text-red-400 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ────────────────── MAIN ────────────────── --}}
    <div class="flex flex-1 flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 shadow-sm lg:px-6">
            <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                <span class="hidden sm:inline">—</span>
                <span class="hidden sm:inline font-medium text-slate-700">{{ now()->format('H:i') }} WIB</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('guest.bookings.rooms') }}" target="_blank"
                   class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Situs
                </a>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6 flex flex-col">
            <div class="flex-1">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="mb-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    <svg class="h-4 w-4 flex-shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <svg class="h-4 w-4 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
                @endif

                {{ $slot }}
            </div>

            <footer class="mt-12 py-8 border-t border-slate-200/50">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 md:gap-0">
                    <!-- Left -->
                    <div>
                        <p class="text-xs font-medium text-slate-600">
                            SIPIRANG &copy; {{ date('Y') }} —<br>
                            Sistem Peminjaman Ruangan
                        </p>
                    </div>
                    
                    <!-- Right -->
                    <div class="md:text-right">
                        <p class="text-xs text-slate-600">
                            Made with ❤️ & ☕ by <a href="https://edumc.id" target="_blank" rel="noopener noreferrer" class="inline-block bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">Reza HD</a><br>
                            Clavis Ignoti Profundi Arcanorum
                        </p>
                    </div>
                </div>
            </footer>
        </main>
    </div>
</div>

@stack('scripts')
@livewireScripts
</body>
</html>
