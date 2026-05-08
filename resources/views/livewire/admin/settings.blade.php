<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Pengaturan Sistem</h1>
        <p class="text-sm text-slate-500 mt-1">Konfigurasi umum, booking, dan dokumen PDF</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- Kiri: Informasi Institusi --}}
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-700">Informasi Institusi</h2>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Fakultas <span class="text-red-500">*</span></label>
                    <input wire:model="faculty_name" type="text" placeholder="cth. Fakultas Ilmu Pendidikan"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('faculty_name') border-red-300 @enderror">
                    @error('faculty_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-slate-400 mt-1">Muncul di header PDF surat izin</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Universitas <span class="text-red-500">*</span></label>
                    <input wire:model="university_name" type="text" placeholder="cth. Universitas Negeri Makassar"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('university_name') border-red-300 @enderror">
                    @error('university_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">No. Telepon Fakultas</label>
                    <input wire:model="phone" type="text" placeholder="cth. (0411) 883076"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400">
                    <p class="text-xs text-slate-400 mt-1">Muncul di header PDF & tombol WA admin</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Email Fakultas</label>
                    <input wire:model="email" type="email" placeholder="cth. fip@unm.ac.id"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none focus:border-indigo-400 @error('email') border-red-300 @enderror">
                    @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-5 border-t border-slate-100 pt-4">
                <button wire:click="saveGeneral" wire:loading.attr="disabled"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                    <span wire:loading.remove wire:target="saveGeneral">Simpan Informasi Institusi</span>
                    <span wire:loading wire:target="saveGeneral">Menyimpan...</span>
                </button>
            </div>
        </div>

        {{-- Kanan: Pengaturan Booking + PDF info + Catatan --}}
        <div class="space-y-6">

            {{-- Pengaturan Booking --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-700">Pengaturan Booking</h2>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Batas Waktu Upload <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input wire:model="deadline_hours" type="number" min="1" max="72"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 pr-12 text-sm outline-none focus:border-indigo-400 @error('deadline_hours') border-red-300 @enderror">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">jam</span>
                        </div>
                        @error('deadline_hours')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-slate-400 mt-1">Waktu maksimal upload surat WD2 setelah booking dibuat. Booking dibatalkan otomatis jika terlewat.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Maks. Slot per Booking <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input wire:model="max_items_per_cart" type="number" min="1" max="20"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 pr-12 text-sm outline-none focus:border-indigo-400 @error('max_items_per_cart') border-red-300 @enderror">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">slot</span>
                            </div>
                            @error('max_items_per_cart')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Maks. Hari ke Depan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input wire:model="max_days_advance" type="number" min="1" max="365"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 pr-12 text-sm outline-none focus:border-indigo-400 @error('max_days_advance') border-red-300 @enderror">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">hari</span>
                            </div>
                            @error('max_days_advance')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                <div class="mt-5 border-t border-slate-100 pt-4">
                    <button wire:click="saveBooking" wire:loading.attr="disabled"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                        <span wire:loading.remove wire:target="saveBooking">Simpan Pengaturan Booking</span>
                        <span wire:loading wire:target="saveBooking">Menyimpan...</span>
                    </button>
                </div>
            </div>

            {{-- Info PDF --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-700">Dokumen PDF</h2>
                </div>
                <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                    <p class="text-xs text-emerald-800 leading-relaxed">
                        Header PDF diambil dari <strong>Informasi Institusi</strong> di sebelah kiri. Semua PDF yang diterbitkan setelah penyimpanan akan menggunakan nilai terbaru.
                    </p>
                    <p class="text-xs text-emerald-700 mt-2 leading-relaxed">
                        PDF lama <strong>tidak diperbarui otomatis</strong> — approve ulang booking dari halaman detail untuk menerbitkan PDF baru.
                    </p>
                </div>
            </div>

            {{-- Preview PDF Testing --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-700">Preview Dokumen PDF</h2>
                </div>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Generate PDF dengan data dummy untuk memeriksa tampilan template sebelum digunakan secara nyata.
                    PDF akan terbuka di tab baru.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.settings.pdf-preview', 'receipt') }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Tanda Terima Booking
                    </a>
                    <a href="{{ route('admin.settings.pdf-preview', 'approval') }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Surat Izin Penggunaan Ruangan
                    </a>
                </div>
            </div>

        {{-- Catatan .env --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Catatan</p>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pengaturan seperti <strong>APP_URL</strong> (mempengaruhi QR Code surat izin) diatur di file <code class="bg-slate-200 px-1 rounded">.env</code>.
                    Jika QR mengarah ke URL salah, perbarui <code class="bg-slate-200 px-1 rounded">APP_URL</code> lalu jalankan <code class="bg-slate-200 px-1 rounded">php artisan config:clear</code>.
                </p>
            </div>

        </div>
    </div>
</div>
