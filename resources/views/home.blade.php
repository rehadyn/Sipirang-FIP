<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title>SIPIRANG - Sistem Peminjaman Ruangan FIP UNM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none !important}</style>
</head>
<body class="font-sans antialiased bg-zinc-50 min-h-screen text-zinc-900 selection:bg-indigo-100 selection:text-indigo-900">

    {{-- Header --}}
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 border-b border-zinc-200 bg-white">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-lg font-semibold tracking-tight text-zinc-900">SIPIRANG</span>
            </div>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-1 text-sm">
                <a href="#fitur" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Fitur</a>
                <a href="#alur" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Alur</a>
                <a href="{{ route('guest.calendar') }}" class="rounded-lg px-3 py-2 font-medium text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-900">Kalender</a>
                <a href="{{ route('guest.bookings.rooms') }}" class="ml-2 rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white transition hover:bg-indigo-700">Ajukan Peminjaman</a>
            </nav>

            {{-- Mobile Toggle --}}
            <div class="flex items-center gap-2 md:hidden">
                <button @click="mobileMenuOpen = true" class="p-2 rounded-lg text-zinc-600 hover:bg-zinc-100 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu Drawer --}}
        <div x-show="mobileMenuOpen" class="fixed inset-0 z-[100] md:hidden" x-cloak>
            <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-zinc-900/50"></div>
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-lg p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <span class="text-lg font-semibold text-zinc-900">Menu</span>
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-lg text-zinc-400 hover:text-zinc-600 hover:bg-zinc-100 transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <nav class="flex flex-col gap-1">
                    <a href="#fitur" @click="mobileMenuOpen = false" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Fitur</a>
                    <a href="#alur" @click="mobileMenuOpen = false" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Alur Penggunaan</a>
                    <a href="{{ route('guest.calendar') }}" class="rounded-lg px-4 py-3 text-sm font-medium text-zinc-700 hover:bg-zinc-100 transition">Kalender Ruangan</a>
                    <a href="{{ route('guest.bookings.rooms') }}" class="mt-3 rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white text-center transition hover:bg-indigo-700">Ajukan Peminjaman</a>
                </nav>
                <div class="mt-auto pt-6 text-center">
                    <p class="text-[10px] font-semibold text-zinc-400 uppercase tracking-widest">SIPIRANG v1.0</p>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">

        {{-- Hero --}}
        <section class="grid gap-6 lg:grid-cols-[1.4fr_0.8fr]">
            <div class="rounded-2xl border border-zinc-200 bg-white p-8 sm:p-10">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-600">Sistem Peminjaman Ruangan · FIP UNM</p>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 sm:text-4xl leading-tight">
                    Peminjaman ruangan kampus yang mudah, terstruktur, dan terdokumentasi.
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-zinc-600">
                    SIPIRANG menghadirkan alur peminjaman ruangan yang transparan — dari pemilihan jadwal hingga penerbitan surat izin resmi ber-QR Code, tanpa birokrasi yang berbelit.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('guest.bookings.rooms') }}" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Ajukan Peminjaman</a>
                    <a href="{{ route('guest.bookings.checkout.show') }}" class="rounded-lg border border-zinc-200 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50">Lihat Keranjang</a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mb-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="text-sm font-semibold text-zinc-900">Akses Terbuka</div>
                    <p class="mt-1 text-xs leading-5 text-zinc-500">Tersedia untuk seluruh civitas akademika tanpa perlu membuat akun.</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mb-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="text-sm font-semibold text-zinc-900">Surat Izin Digital</div>
                    <p class="mt-1 text-xs leading-5 text-zinc-500">Surat izin resmi ber-QR Code diterbitkan otomatis setelah persetujuan admin.</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mb-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div class="text-sm font-semibold text-zinc-900">Pemantauan Real-time</div>
                    <p class="mt-1 text-xs leading-5 text-zinc-500">Lacak status pengajuan dan unggah dokumen persyaratan dari satu halaman.</p>
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="fitur" class="mt-6 grid gap-4 lg:grid-cols-3">
            <article class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h2 class="text-base font-semibold text-zinc-900">Pencarian Ruangan Fleksibel</h2>
                <p class="mt-2 text-sm leading-6 text-zinc-600">Telusuri ketersediaan ruangan berdasarkan gedung, kapasitas, dan slot waktu yang diinginkan.</p>
            </article>
            <article class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-base font-semibold text-zinc-900">Pengajuan Multi-Jadwal</h2>
                <p class="mt-2 text-sm leading-6 text-zinc-600">Satu tiket pengajuan dapat memuat beberapa ruangan dan sesi waktu dalam satu kali proses.</p>
            </article>
            <article class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h2 class="text-base font-semibold text-zinc-900">Dokumen Digital Otomatis</h2>
                <p class="mt-2 text-sm leading-6 text-zinc-600">Tanda terima dan surat izin resmi diterbitkan secara otomatis dan dapat diunduh kapan saja.</p>
            </article>
        </section>

        {{-- Steps --}}
        <section id="alur" class="mt-6 rounded-2xl border border-zinc-200 bg-white p-8 sm:p-10">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight text-zinc-900">Alur Penggunaan</h2>
                    <p class="mt-1 text-sm text-zinc-500">Dirancang agar pengajuan dapat diselesaikan dengan cepat, bahkan melalui perangkat mobile.</p>
                </div>
                <a href="{{ route('guest.bookings.rooms') }}" class="rounded-lg border border-zinc-200 bg-white px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">Mulai Sekarang</a>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
                    <div class="text-xs font-semibold uppercase tracking-[0.15em] text-indigo-600">Langkah 01</div>
                    <div class="mt-2 font-semibold text-zinc-900">Pilih Ruangan & Slot</div>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Telusuri ketersediaan ruangan dan tambahkan jadwal yang dibutuhkan ke dalam keranjang.</p>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
                    <div class="text-xs font-semibold uppercase tracking-[0.15em] text-indigo-600">Langkah 02</div>
                    <div class="mt-2 font-semibold text-zinc-900">Isi Data Peminjaman</div>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Lengkapi identitas peminjam, nomor identitas, dan tujuan penggunaan ruangan.</p>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
                    <div class="text-xs font-semibold uppercase tracking-[0.15em] text-indigo-600">Langkah 03</div>
                    <div class="mt-2 font-semibold text-zinc-900">Unggah Dokumen</div>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Upload surat permohonan resmi dari Wakil Dekan melalui halaman pelacakan tiket.</p>
                </div>
                <div class="rounded-lg border border-zinc-200 bg-zinc-50 p-5">
                    <div class="text-xs font-semibold uppercase tracking-[0.15em] text-indigo-600">Langkah 04</div>
                    <div class="mt-2 font-semibold text-zinc-900">Terima Surat Izin</div>
                    <p class="mt-2 text-sm leading-6 text-zinc-600">Unduh surat izin resmi ber-QR Code setelah pengajuan disetujui oleh admin.</p>
                </div>
            </div>
        </section>

        {{-- CTA Banner --}}
        <section class="mt-6 rounded-2xl bg-indigo-600 p-8 sm:p-10 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div>
                    <h2 class="text-xl font-semibold">Siap mengajukan peminjaman ruangan?</h2>
                    <p class="mt-1 text-sm text-indigo-100">Proses pengajuan dapat diselesaikan dalam beberapa menit.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('guest.bookings.rooms') }}" class="rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50">Pilih Ruangan</a>
                    <a href="{{ route('guest.guide') }}" class="rounded-lg border border-indigo-400 bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Panduan Penggunaan</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="mt-6 border-t border-zinc-200 bg-white py-6 px-4 text-center">
        <div class="mx-auto max-w-5xl">
            <p class="text-xs text-zinc-500">
                Made with ❤️ & ☕ by <a href="https://edumc.id/" target="_blank" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">REHAD</a>
            </p>
            <p class="text-[10px] font-mono text-zinc-400 mt-1.5 uppercase tracking-[0.2em]">
                Clavis Ignoti Profundi Arcanorum
            </p>
        </div>
    </footer>

</body>
</html>
