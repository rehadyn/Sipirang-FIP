@section('title', 'Tata Cara Peminjaman - SIPIRANG')

<div class="max-w-5xl mx-auto">
    {{-- Header Section --}}
    <header class="text-center mb-12">
        <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 mb-3 block">Panduan Pengguna</span>
        <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 sm:text-4xl mb-4">Tata Cara Peminjaman</h1>
        <p class="max-w-2xl mx-auto text-base text-zinc-600 leading-relaxed">
            Selamat datang di SIPIRANG. Proses peminjaman dirancang sederhana — Anda dapat memilih beberapa ruangan pada tanggal yang berbeda dalam satu alur pengajuan yang praktis.
        </p>
    </header>

    {{-- Flow Section --}}
    <section class="mb-14">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold mb-4">1</div>
                <h3 class="text-base font-semibold text-zinc-900 mb-2">Pilih Ruangan</h3>
                <p class="text-sm text-zinc-600 leading-relaxed">
                    Cek ketersediaan ruangan pada halaman <a href="{{ route('guest.bookings.rooms') }}" class="text-indigo-600 hover:underline font-medium">daftar ruangan</a>. Anda dapat memasukkan beberapa ruangan pada tanggal yang berbeda ke dalam satu keranjang pengajuan.
                </p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold mb-4">2</div>
                <h3 class="text-base font-semibold text-zinc-900 mb-2">Isi Identitas</h3>
                <p class="text-sm text-zinc-600 leading-relaxed">
                    Lengkapi data diri, instansi, dan keperluan peminjaman pada halaman checkout. Pastikan nomor WhatsApp aktif untuk koordinasi dengan admin.
                </p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold mb-4">3</div>
                <h3 class="text-base font-semibold text-zinc-900 mb-2">Unggah Dokumen</h3>
                <p class="text-sm text-zinc-600 leading-relaxed">
                    Setelah checkout, Anda memiliki waktu <span class="font-semibold text-zinc-900">5 jam</span> untuk mengunggah Surat Persetujuan WD 2 dalam format PDF.
                </p>
            </div>
        </div>
    </section>

    {{-- Details Grid --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Requirements Card --}}
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
            <h2 class="text-lg font-semibold text-zinc-900 mb-6 flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                Syarat & Ketentuan
            </h2>

            <ul class="space-y-5">
                <li class="flex items-start gap-3">
                    <div class="mt-1.5 h-1.5 w-1.5 rounded-full bg-indigo-600 shrink-0"></div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900">Identitas Resmi</p>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">Wajib memiliki KTM (mahasiswa) atau KTP (umum) yang masih berlaku.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="mt-1.5 h-1.5 w-1.5 rounded-full bg-indigo-600 shrink-0"></div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900">Surat Persetujuan WD 2</p>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">Dokumen utama yang menjadi dasar verifikasi admin. Tanpa dokumen ini, booking akan dibatalkan otomatis oleh sistem setelah 5 jam.</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <div class="mt-1.5 h-1.5 w-1.5 rounded-full bg-indigo-600 shrink-0"></div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900">SLA Verifikasi</p>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">Admin akan meninjau dokumen Anda dalam waktu maksimal 2 hari kerja.</p>
                    </div>
                </li>
            </ul>
        </section>

        {{-- Features Card --}}
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
            <h2 class="text-lg font-semibold text-zinc-900 mb-6 flex items-center gap-2.5">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </span>
                Fitur Utama
            </h2>

            <div class="space-y-5">
                <div class="flex gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900">Status Ruangan Real-time</h4>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">Pantau ketersediaan seluruh ruangan di Fakultas tanpa harus datang ke lokasi.</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900">Tiket & Surat Digital</h4>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">Sistem menerbitkan tanda terima dan surat izin resmi dalam format PDF yang dapat diunduh kapan saja.</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-zinc-900">Pelacakan Status</h4>
                        <p class="mt-1 text-sm text-zinc-600 leading-relaxed">Gunakan nomor WhatsApp Anda untuk melacak status verifikasi dokumen secara instan.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Final CTA --}}
    <div class="mt-10 text-center rounded-2xl bg-indigo-600 p-10">
        <h2 class="text-2xl font-semibold text-white mb-3">Sudah Paham Alurnya?</h2>
        <p class="text-indigo-100 mb-6 max-w-xl mx-auto text-sm leading-relaxed">Mulai pengajuan peminjaman Anda sekarang dan nikmati kemudahan pengelolaan fasilitas di Fakultas Ilmu Pendidikan.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('guest.bookings.rooms') }}" class="w-full sm:w-auto rounded-lg bg-white px-6 py-2.5 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50">Pilih Ruangan Sekarang</a>
            <a href="{{ url('/') }}" class="w-full sm:w-auto rounded-lg border border-indigo-400 bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Kembali ke Beranda</a>
        </div>
    </div>
</div>
