<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>SIPIRANG - Sistem Peminjaman Ruangan FIP UNM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 text-zinc-950 antialiased">
    <div class="min-h-screen">
        <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 border-b border-zinc-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-zinc-950 text-sm font-bold text-white">SP</div>
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.24em]">SIPIRANG</div>
                        <div class="text-sm text-zinc-500">Sistem peminjaman ruangan FIP UNM</div>
                    </div>
                </div>

                {{-- Desktop Nav --}}
                <nav class="hidden md:flex items-center gap-2 text-sm">
                    <a href="#fitur" class="rounded-full border border-zinc-200 px-4 py-2 font-medium text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Fitur</a>
                    <a href="#alur" class="rounded-full border border-zinc-200 px-4 py-2 font-medium text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Alur</a>
                    <a href="{{ route('guest.calendar') }}" class="rounded-full border border-zinc-200 px-4 py-2 font-medium text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Kalender</a>
                    <a href="{{ route('guest.bookings.rooms') }}" class="rounded-full bg-zinc-950 px-4 py-2 font-medium text-white transition hover:bg-zinc-800">Mulai Booking</a>
                </nav>

                {{-- Mobile Toggle --}}
                <div class="flex items-center gap-2 md:hidden">
                    <button @click="mobileMenuOpen = true" class="p-2 rounded-xl text-zinc-500 hover:bg-zinc-100 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu Drawer --}}
            <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100] md:hidden" x-cloak>
                <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-zinc-900/40 backdrop-blur-sm"></div>
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-2xl p-6 flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <span class="text-xl font-bold text-zinc-900">Menu</span>
                        <button @click="mobileMenuOpen = false" class="p-2 rounded-xl text-zinc-400 hover:text-zinc-600 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <nav class="flex flex-col gap-3">
                        <a href="#fitur" @click="mobileMenuOpen = false" class="px-4 py-3 text-base font-medium text-zinc-700 hover:bg-zinc-50 rounded-xl transition">Fitur</a>
                        <a href="#alur" @click="mobileMenuOpen = false" class="px-4 py-3 text-base font-medium text-zinc-700 hover:bg-zinc-50 rounded-xl transition">Alur</a>
                        <a href="{{ route('guest.calendar') }}" class="px-4 py-3 text-base font-medium text-zinc-700 hover:bg-zinc-50 rounded-xl transition">Kalender</a>
                        <a href="{{ route('guest.bookings.rooms') }}" class="mt-4 flex items-center justify-center gap-2 rounded-2xl bg-zinc-950 px-4 py-4 text-sm font-bold text-white shadow-lg transition active:scale-95">Mulai Booking</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <section class="grid gap-4 lg:grid-cols-[1.35fr_0.85fr]">
                <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm sm:p-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Landing page</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-tight text-zinc-950 sm:text-5xl">Booking ruangan FIP UNM yang cepat, jelas, dan rapi.</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-zinc-600 sm:text-base">
                        SIPIRANG membantu mahasiswa dan dosen memilih ruangan, mengatur slot, upload surat, dan memantau status booking dalam satu alur ringan.
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('guest.bookings.rooms') }}" class="rounded-full bg-zinc-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-zinc-800">Mulai Pilih Ruangan</a>
                        <a href="{{ route('guest.bookings.checkout.show') }}" class="rounded-full border border-zinc-200 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Lihat Checkout</a>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">Guest flow</div>
                        <div class="mt-2 text-lg font-semibold">Tanpa login</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Langsung pilih ruangan lalu lanjut checkout.</p>
                    </div>
                    <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">Dokumen</div>
                        <div class="mt-2 text-lg font-semibold">2 PDF utama</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Bukti booking dan surat izin final ber-QR.</p>
                    </div>
                    <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-sm">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">Status</div>
                        <div class="mt-2 text-lg font-semibold">Tracking real-time</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Cek status booking dan upload surat dari satu halaman.</p>
                    </div>
                </div>
            </section>

            <section id="fitur" class="mt-6 grid gap-4 lg:grid-cols-3">
                <article class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Pencarian cepat</h2>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Cari ruangan berdasarkan gedung, kapasitas, dan fasilitas secara sederhana.</p>
                </article>
                <article class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Multi-slot booking</h2>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Satu tiket bisa memuat beberapa ruangan dan beberapa jadwal sekaligus.</p>
                </article>
                <article class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold">Notifikasi in-app</h2>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Status booking dibaca langsung di halaman tracking tanpa notifikasi eksternal.</p>
                </article>
            </section>

            <section id="alur" class="mt-6 rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight">Alur penggunaan</h2>
                        <p class="mt-1 text-sm text-zinc-500">Dibuat untuk mahasiswa agar bisa selesai cepat dari HP.</p>
                    </div>
                    <a href="{{ route('guest.bookings.rooms') }}" class="rounded-full border border-zinc-200 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:border-zinc-950 hover:text-zinc-950">Masuk ke booking</a>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-4">
                    <div class="rounded-2xl border border-zinc-200 p-4">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">01</div>
                        <div class="mt-2 font-semibold">Pilih ruangan</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Pilih ruangan dan slot waktu dari daftar.</p>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 p-4">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">02</div>
                        <div class="mt-2 font-semibold">Checkout</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Isi identitas peminjam dan tujuan booking.</p>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 p-4">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">03</div>
                        <div class="mt-2 font-semibold">Upload surat</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Unggah surat WD 2 di halaman tracking.</p>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 p-4">
                        <div class="text-xs uppercase tracking-[0.2em] text-zinc-500">04</div>
                        <div class="mt-2 font-semibold">Unduh final PDF</div>
                        <p class="mt-2 text-sm leading-6 text-zinc-600">Ambil surat izin final ketika booking disetujui.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="mt-12 border-t border-zinc-100 bg-white/50 py-12 px-4 text-center">
            <div class="mx-auto max-w-5xl">
                <p class="text-sm font-medium text-zinc-500">
                    Made with ❤️ & ☕ by <a href="https://edumc.id/" target="_blank" class="inline-flex px-2 py-0.5 rounded-lg bg-zinc-100 text-zinc-950 font-bold hover:bg-zinc-200 transition-colors">REHAD</a>
                </p>
                <p class="text-[10px] font-mono text-zinc-400 mt-2 uppercase tracking-[0.25em] leading-relaxed">
                    Clavis Ignoti Profundi Arcanorum
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
