<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', 'SIPIRANG')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak]{display:none !important}
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen text-zinc-900 selection:bg-indigo-200 selection:text-indigo-900">
    
    <!-- Decorative background blobs -->
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-200/40 blur-[120px]"></div>
        <div class="absolute top-[40%] -right-[10%] w-[40%] h-[60%] rounded-full bg-purple-200/40 blur-[120px]"></div>
    </div>

    <!-- Top Navigation -->
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 border-b border-white/20 bg-white/70 backdrop-blur-xl shadow-[0_4px_30px_rgb(0,0,0,0.03)]">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('guest.bookings.rooms') }}" class="flex items-center gap-2 group">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 text-white shadow-md transition-transform group-hover:scale-105">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-xl font-semibold tracking-tight text-zinc-900">SIPIRANG</span>
                </a>
            </div>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-2 text-sm">
                <a href="{{ route('guest.guide') }}" class="rounded-full bg-white/50 border border-white/40 px-4 py-2 font-medium text-zinc-600 transition hover:bg-white hover:text-zinc-900">Tata Cara</a>
                <a href="{{ route('guest.bookings.rooms') }}" class="rounded-full bg-white/50 border border-white/40 px-4 py-2 font-medium text-zinc-600 transition hover:bg-white hover:text-zinc-900">Ruangan</a>
                <a href="{{ route('guest.calendar') }}" class="rounded-full bg-white/50 border border-white/40 px-4 py-2 font-medium text-zinc-600 transition hover:bg-white hover:text-zinc-900">Kalender</a>
                <a href="{{ route('guest.bookings.checkout.show') }}" class="rounded-full bg-white/50 border border-white/40 px-4 py-2 font-medium text-zinc-600 transition hover:bg-white hover:text-zinc-900">Checkout</a>
                
                <div x-data="{ open: false, ticket: '', phone: '' }" @keydown.escape.window="open = false" class="inline-block ml-2">
                    <a href="#" @click.prevent="open = true" class="rounded-full bg-gradient-to-r from-indigo-500 to-violet-500 px-5 py-2 font-medium text-white shadow-sm transition hover:shadow-md hover:-translate-y-0.5 inline-block">Tracking</a>

                    <template x-teleport="body">
                        <div x-show="open" class="fixed inset-0 z-[100] grid place-items-center p-4" x-cloak>
                            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-zinc-900/40 backdrop-blur-sm"></div>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl border border-zinc-100">
                                <button @click="open = false" type="button" class="absolute top-5 right-5 text-zinc-400 hover:text-zinc-600 transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                                <h3 class="text-xl font-semibold tracking-tight text-zinc-900 mb-2">Cek Status Booking</h3>
                                <p class="text-sm text-zinc-500 mb-6 whitespace-normal pr-4 leading-relaxed">Masukkan nomor tiket dan nomor WhatsApp yang didaftarkan.</p>
                                <div class="space-y-3">
                                    <input x-model="ticket" type="text" placeholder="Nomor Tiket" class="w-full rounded-xl bg-zinc-50 border border-zinc-200 px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/5">
                                    <input x-model="phone" type="text" placeholder="Nomor WhatsApp" class="w-full rounded-xl bg-zinc-50 border border-zinc-200 px-4 py-3 text-sm text-zinc-900 outline-none transition focus:border-indigo-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/5">
                                </div>
                                <div class="mt-8 flex flex-col gap-2">
                                    <button @click="if(ticket && phone) window.location.href = '/tracking/' + encodeURIComponent(ticket) + '?phone=' + encodeURIComponent(phone)" type="button" class="w-full rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">Cek Tiket Sekarang</button>
                                    <button @click="open = false" type="button" class="w-full rounded-xl border border-zinc-200 bg-white px-6 py-3.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-50">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </nav>

            {{-- Mobile Toggle --}}
            <div class="flex items-center gap-2 md:hidden">
                <button @click="mobileMenuOpen = true" class="p-2 rounded-xl text-zinc-500 hover:bg-zinc-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Drawer --}}
        <template x-teleport="body">
            <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100]" x-cloak>
                <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-zinc-900/40 backdrop-blur-sm"></div>
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-2xl p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <span class="text-xl font-bold text-zinc-900">Menu</span>
                        <button @click="mobileMenuOpen = false" class="p-2 rounded-xl text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <nav class="flex flex-col gap-2">
                        <a href="{{ route('guest.bookings.rooms') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 transition">
                            <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Pilih Ruangan
                        </a>
                        <a href="{{ route('guest.calendar') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 transition">
                            <svg class="h-5 w-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Kalender Ruangan
                        </a>
                        <a href="{{ route('guest.guide') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 transition">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Tata Cara
                        </a>
                        <a href="{{ route('guest.bookings.checkout.show') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-semibold text-zinc-900 hover:bg-zinc-50 transition">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Checkout
                        </a>
                        
                        <div x-data="{ open: false, ticket: '', phone: '' }" class="mt-4">
                            <button @click="open = true" class="w-full flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-4 py-4 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition active:scale-95">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Tracking Booking
                            </button>
                            
                            {{-- Mobile Tracking Modal inside drawer --}}
                            <template x-teleport="body">
                                <div x-show="open" class="fixed inset-0 z-[110] grid place-items-center p-4" x-cloak>
                                    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-zinc-900/60 backdrop-blur-sm"></div>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl border border-zinc-100">
                                        <h3 class="text-lg font-bold text-zinc-900 mb-4">Cek Status</h3>
                                        <div class="space-y-3">
                                            <input x-model="ticket" type="text" placeholder="Nomor Tiket" class="w-full rounded-xl bg-zinc-50 border border-zinc-200 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white">
                                            <input x-model="phone" type="text" placeholder="Nomor WhatsApp" class="w-full rounded-xl bg-zinc-50 border border-zinc-200 px-4 py-3 text-sm outline-none focus:border-indigo-400 focus:bg-white">
                                        </div>
                                        <div class="mt-6 flex flex-col gap-2">
                                            <button @click="if(ticket && phone) window.location.href = '/tracking/' + encodeURIComponent(ticket) + '?phone=' + encodeURIComponent(phone)" class="w-full rounded-xl bg-indigo-600 py-3.5 text-sm font-bold text-white">Cari Tiket</button>
                                            <button @click="open = false" class="w-full rounded-xl border border-zinc-200 py-3.5 text-sm font-medium text-zinc-600">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </nav>
                    <div class="mt-auto pt-8 text-center">
                        <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">SIPIRANG v1.0</p>
                    </div>
                </div>
            </div>
        </template>
    </header>

    <div class="flex-1 flex flex-col">
        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8 flex-1">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-700 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-zinc-300 bg-zinc-950 px-4 py-3 text-sm text-white shadow-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('hero')
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <footer class="mt-auto border-t border-zinc-100 bg-white/50 py-8 px-4 text-center">
            <div class="mx-auto max-w-5xl">
                <p class="text-xs font-medium text-zinc-500">
                    Made with ❤️ & ☕ by <a href="https://edumc.id/" target="_blank" class="inline-flex px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-600 font-bold hover:bg-indigo-100 transition-colors">REHAD</a>
                </p>
                <p class="text-[10px] font-mono text-zinc-400 mt-2 uppercase tracking-[0.2em] leading-relaxed">
                    Clavis Ignoti Profundi Arcanorum
                </p>
            </div>
        </footer>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
