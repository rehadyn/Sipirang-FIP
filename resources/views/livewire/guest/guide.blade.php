@section('title', 'Tata Cara Peminjaman - SIPIRANG')

<div class="max-w-5xl mx-auto px-4 py-12">
    <!-- Header Section -->
    <header class="text-center mb-16">
        <span class="text-xs font-bold uppercase tracking-[0.3em] text-indigo-500 mb-4 block">Panduan Pengguna</span>
        <h1 class="text-4xl font-semibold tracking-tight text-zinc-900 sm:text-5xl mb-6">Tata Cara Peminjaman</h1>
        <p class="max-w-2xl mx-auto text-base text-zinc-500 font-medium leading-relaxed">
            Selamat datang di SIPIRANG. Proses peminjaman di sini sangat mudah, layaknya sedang <span class="text-indigo-600 font-bold">belanja ruangan</span>. Anda bebas memilih ruangan apa saja di tanggal kapan saja dalam satu alur booking yang praktis.
        </p>
    </header>

    <!-- Flow Section -->
    <section class="mb-24">
        <div class="grid gap-8 md:grid-cols-3">
            <!-- Step 1 -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                <div class="relative rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-xl mb-6 shadow-lg shadow-indigo-500/20">1</div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-3">Belanja Ruangan</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed font-medium">
                        Cek ketersediaan ruangan secara real-time pada halaman <a href="{{ route('guest.bookings.rooms') }}" class="text-indigo-600 hover:underline">Live Board</a>. 
                        <br><br>
                        Sama seperti belanja online, Anda dapat memasukkan <span class="text-indigo-600 font-semibold">berbagai ruangan</span> di <span class="text-indigo-600 font-semibold">berbagai tanggal berbeda</span> ke dalam satu keranjang checkout. Cukup pilih ruangan, ubah tanggal di kalender atas, dan pilih ruangan lagi sesuai kebutuhan Anda.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                <div class="relative rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-xl mb-6 shadow-lg shadow-indigo-500/20">2</div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-3">Isi Identitas</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed font-medium">
                        Lengkapi data diri, instansi, dan keperluan peminjaman pada halaman Checkout. Pastikan nomor WhatsApp aktif untuk koordinasi.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                <div class="relative rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white font-bold text-xl mb-6 shadow-lg shadow-indigo-500/20">3</div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-3">Upload Dokumen</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed font-medium">
                        Setelah checkout, Anda memiliki waktu <span class="text-indigo-600 font-bold">5 jam</span> untuk mengunggah Surat Persetujuan WD 2 dalam format PDF.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Details Grid -->
    <div class="grid gap-10 lg:grid-cols-2">
        <!-- Requirements Card -->
        <section class="rounded-3xl border border-zinc-100 bg-white p-8 shadow-sm sm:p-10 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8 opacity-5">
                <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            
            <h2 class="text-2xl font-semibold text-zinc-900 mb-8 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                Syarat & Ketentuan
            </h2>

            <ul class="space-y-6">
                <li class="flex items-start gap-4">
                    <div class="mt-1 h-2 w-2 rounded-full bg-indigo-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-bold text-zinc-900 uppercase tracking-widest">Identitas Resmi</p>
                        <p class="mt-1 text-sm text-zinc-500 font-medium">Wajib memiliki KTM (Mahasiswa) atau KTP (Umum) yang masih berlaku.</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="mt-1 h-2 w-2 rounded-full bg-indigo-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-bold text-zinc-900 uppercase tracking-widest">Surat Persetujuan WD 2</p>
                        <p class="mt-1 text-sm text-zinc-500 font-medium">Dokumen utama yang menjadi dasar verifikasi admin. Tanpa dokumen ini, booking akan dibatalkan otomatis oleh sistem setelah 5 jam.</p>
                    </div>
                </li>
                <li class="flex items-start gap-4">
                    <div class="mt-1 h-2 w-2 rounded-full bg-indigo-500 shrink-0"></div>
                    <div>
                        <p class="text-sm font-bold text-zinc-900 uppercase tracking-widest">SLA Verifikasi</p>
                        <p class="mt-1 text-sm text-zinc-500 font-medium">Admin akan meninjau dokumen Anda dalam waktu maksimal 2 hari kerja.</p>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Features Card -->
        <section class="rounded-3xl bg-zinc-900 p-8 shadow-xl sm:p-10 relative overflow-hidden">
            <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
            
            <h2 class="text-2xl font-semibold text-white mb-8 flex items-center gap-3">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-indigo-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </span>
                Fitur Unggulan
            </h2>

            <div class="grid gap-6">
                <div class="flex gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/5 border border-white/10">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-white">Live Board Status</h4>
                        <p class="mt-1 text-xs text-zinc-400 leading-relaxed font-medium">Pantau ketersediaan seluruh ruangan di Fakultas secara real-time tanpa harus datang ke lokasi.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/5 border border-white/10">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-white">Output Tiket Digital</h4>
                        <p class="mt-1 text-xs text-zinc-400 leading-relaxed font-medium">Sistem akan menghasilkan Tanda Terima dan Surat Izin Resmi dalam format PDF yang dapat diunduh kapan saja.</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/5 border border-white/10">
                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-white">Tracking Real-time</h4>
                        <p class="mt-1 text-xs text-zinc-400 leading-relaxed font-medium">Gunakan nomor WhatsApp Anda untuk melacak status verifikasi dokumen secara instan.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Final CTA -->
    <div class="mt-20 text-center rounded-3xl bg-indigo-600 p-12 shadow-2xl shadow-indigo-500/20 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <h2 class="text-3xl font-semibold text-white mb-6 relative z-10">Sudah Paham Alurnya?</h2>
        <p class="text-indigo-100 mb-10 max-w-xl mx-auto font-medium relative z-10">Mulai peminjaman Anda sekarang dan nikmati kemudahan pengelolaan fasilitas di Fakultas Ilmu Pendidikan.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 relative z-10">
            <a href="{{ route('guest.bookings.rooms') }}" class="w-full sm:w-auto rounded-xl bg-white px-8 py-4 text-sm font-bold text-indigo-600 transition hover:bg-zinc-100">Pilih Ruangan Sekarang</a>
            <a href="{{ url('/') }}" class="w-full sm:w-auto rounded-xl border border-indigo-400 px-8 py-4 text-sm font-bold text-white transition hover:bg-indigo-500">Kembali ke Beranda</a>
        </div>
    </div>
</div>
