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
<body class="font-sans antialiased bg-zinc-50 min-h-screen text-zinc-900 selection:bg-indigo-100 selection:text-indigo-900">

    {{-- Top Navigation --}}
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 border-b border-zinc-200 bg-white">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('guest.bookings.rooms') }}" class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-zinc-900">SIPIRANG</span>
                </a>
            </div>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1 text-sm">
                <a href="{{ route('guest.guide') }}" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Tata Cara</a>
                <a href="{{ route('guest.bookings.rooms') }}" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Ruangan</a>
                <a href="{{ route('guest.calendar') }}" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Kalender</a>
                <a href="{{ route('guest.bookings.checkout.show') }}" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Checkout</a>

                <div x-data="{ open: false, ticket: '', phone: '' }" @keydown.escape.window="open = false" class="inline-block ml-2">
                    <button @click="open = true" class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white transition hover:bg-indigo-700">Tracking</button>

                    <template x-teleport="body">
                        <div x-show="open" class="fixed inset-0 z-[100] grid place-items-center p-4" x-cloak>
                            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-zinc-900/50"></div>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-lg border border-zinc-200">
                                <button @click="open = false" type="button" class="absolute top-4 right-4 text-zinc-400 hover:text-zinc-600 transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                                <h3 class="text-lg font-semibold tracking-tight text-zinc-900 mb-1">Cek Status Booking</h3>
                                <p class="text-sm text-zinc-500 mb-5">Masukkan nomor tiket dan nomor WhatsApp yang didaftarkan.</p>
                                <div class="space-y-3">
                                    <input x-model="ticket" type="text" placeholder="Nomor Tiket" class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                    <input x-model="phone" type="text" placeholder="Nomor WhatsApp" class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm text-zinc-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                </div>
                                <div class="mt-6 flex flex-col gap-2">
                                    <button @click="if(ticket && phone) window.location.href = '/tracking/' + encodeURIComponent(ticket) + '?phone=' + encodeURIComponent(phone)" type="button" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Cek Tiket</button>
                                    <button @click="open = false" type="button" class="w-full rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-600 transition hover:bg-zinc-50">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </nav>

            {{-- Mobile Toggle --}}
            <div class="flex items-center gap-2 md:hidden">
                <button @click="mobileMenuOpen = true" class="p-2 rounded-lg text-zinc-600 hover:bg-zinc-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Drawer --}}
        <template x-teleport="body">
            <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100]" x-cloak>
                <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-zinc-900/50"></div>
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-lg p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-lg font-semibold text-zinc-900">Menu</span>
                        <button @click="mobileMenuOpen = false" class="p-2 rounded-lg text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <nav class="flex flex-col gap-1">
                        <a href="{{ route('guest.bookings.rooms') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Pilih Ruangan</a>
                        <a href="{{ route('guest.calendar') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Kalender Ruangan</a>
                        <a href="{{ route('guest.guide') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Tata Cara</a>
                        <a href="{{ route('guest.bookings.checkout.show') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Checkout</a>

                        <div x-data="{ open: false, ticket: '', phone: '' }" class="mt-3">
                            <button @click="open = true" class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">Tracking Booking</button>

                            <template x-teleport="body">
                                <div x-show="open" class="fixed inset-0 z-[110] grid place-items-center p-4" x-cloak>
                                    <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-zinc-900/50"></div>
                                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-lg border border-zinc-200">
                                        <h3 class="text-lg font-semibold text-zinc-900 mb-4">Cek Status</h3>
                                        <div class="space-y-3">
                                            <input x-model="ticket" type="text" placeholder="Nomor Tiket" class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                            <input x-model="phone" type="text" placeholder="Nomor WhatsApp" class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                        </div>
                                        <div class="mt-6 flex flex-col gap-2">
                                            <button @click="if(ticket && phone) window.location.href = '/tracking/' + encodeURIComponent(ticket) + '?phone=' + encodeURIComponent(phone)" class="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white">Cari Tiket</button>
                                            <button @click="open = false" class="w-full rounded-lg border border-zinc-200 py-2.5 text-sm font-medium text-zinc-600">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </nav>
                    <div class="mt-auto pt-6 text-center">
                        <p class="text-[10px] font-semibold text-zinc-400 uppercase tracking-widest">SIPIRANG v1.0</p>
                    </div>
                </div>
            </div>
        </template>
    </header>

    <div class="flex-1 flex flex-col">
        <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8 flex-1">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('hero')
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <footer class="mt-auto border-t border-zinc-200 bg-white py-6 px-4 text-center">
            <div class="mx-auto max-w-5xl">
                <p class="text-xs text-zinc-500">
                    Made with ❤️ & ☕ by <a href="https://edumc.id/" target="_blank" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">REHAD</a>
                </p>
                <p class="text-[10px] font-mono text-zinc-400 mt-1.5 uppercase tracking-[0.2em]">
                    Clavis Ignoti Profundi Arcanorum
                </p>
            </div>
        </footer>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
