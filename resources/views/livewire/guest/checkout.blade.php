@section('title', 'Checkout Booking - SIPIRANG')

<div x-data="{ currentStep: 1 }">
    <!-- Header -->
    <header class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <nav class="mb-4 flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-zinc-400">
                <a href="{{ route('guest.bookings.rooms') }}" class="hover:text-indigo-600 transition-colors">Ruangan</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                <span class="text-zinc-900">Checkout</span>
            </nav>
            <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 sm:text-4xl">Checkout Booking</h1>
            <p class="mt-2 text-sm font-medium text-zinc-500">Lengkapi informasi untuk memproses peminjaman.</p>
        </div>
        
        <a href="{{ route('guest.bookings.rooms') }}" class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-5 py-3 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50 hover:text-indigo-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali Pilih Ruangan
        </a>
    </header>

    @error('cart')
        <div class="mb-8 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm text-red-700 font-medium flex items-center gap-3">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ $message }}
        </div>
    @enderror

    <div class="grid gap-10 lg:grid-cols-12 items-start">
        <!-- Sidebar: Order Summary (Top on Mobile) -->
        <div class="lg:col-span-5 lg:order-2">
            <aside class="sticky top-10 space-y-6">
                <section class="rounded-3xl border border-zinc-100 bg-white p-6 shadow-sm sm:p-8">
                    <h2 class="text-base font-semibold text-zinc-900 mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3">
                        @foreach ($cartItems as $item)
                            <div class="group relative rounded-2xl border border-zinc-50 bg-zinc-50/50 p-4 transition hover:bg-white hover:border-zinc-100">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-zinc-900 truncate">{{ $item['room_name'] }}</div>
                                        <div class="mt-1 flex items-center gap-2">
                                            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($item['booking_date'])->translatedFormat('d M') }}</span>
                                            <span class="text-[10px] text-zinc-300">•</span>
                                            <span class="text-[10px] font-medium text-zinc-500">{{ $item['session_label'] ?? ($item['start_time'] . ' - ' . $item['end_time']) }}</span>
                                        </div>
                                    </div>
                                    <div class="shrink-0 text-[9px] font-bold text-zinc-400 bg-white border border-zinc-100 px-2 py-0.5 rounded uppercase tracking-widest">
                                        {{ $item['session'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 border-t border-zinc-50 pt-6">
                        <div class="flex items-center justify-between mb-8">
                            <div class="text-sm font-medium text-zinc-500">Total Ruangan</div>
                            <div class="text-xl font-semibold text-zinc-900">{{ count($cartItems) }} Item</div>
                        </div>

                        <button wire:click="submit" wire:loading.attr="disabled" class="hidden lg:flex w-full items-center justify-center rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-semibold text-white shadow-lg transition hover:bg-indigo-700 disabled:opacity-50">
                            <span wire:loading.remove wire:target="submit">Konfirmasi Booking</span>
                            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center gap-3 text-[10px] font-medium text-zinc-400">
                                <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Verifikasi real-time oleh admin</span>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] font-medium text-zinc-400">
                                <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Output tiket digital instan</span>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="rounded-3xl bg-zinc-900 p-6 text-center shadow-xl">
                    <p class="text-xs font-medium text-zinc-400 leading-relaxed">Pastikan seluruh data sudah benar. Setelah konfirmasi, Anda tidak dapat mengubah data pemohon.</p>
                </div>
            </aside>
        </div>

        <!-- Form Section (Bottom on Mobile) -->
        <div class="lg:col-span-7 lg:order-1 space-y-8">
            <section class="rounded-3xl border border-white bg-white/40 backdrop-blur-xl p-6 shadow-sm sm:p-8">
                <div class="flex items-center gap-4 mb-8">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-sm shadow-md shadow-indigo-500/20">1</div>
                    <h2 class="text-xl font-semibold text-zinc-900">Identitas Peminjam</h2>
                </div>

                <form wire:submit="submit" class="space-y-6">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">
                                @if($borrower_type === 'mahasiswa') Nama Mahasiswa
                                @elseif($borrower_type === 'dosen') Nama Dosen
                                @elseif($borrower_type === 'lainnya') Nama Penanggung Jawab
                                @else Nama Lengkap
                                @endif
                            </label>
                            <input type="text" wire:model="borrower_name" required class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Sesuai KTP/KTM">
                            @error('borrower_name') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">NIM / NIP / ID</label>
                            <input type="text" wire:model="borrower_id_number" required class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Nomor identitas resmi">
                            @error('borrower_id_number') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">Tipe Peminjam</label>
                            <div class="relative">
                                <select wire:model.live="borrower_type" required class="w-full appearance-none rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5">
                                    @foreach($borrowerTypes as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-zinc-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            @error('borrower_type') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                        </div>

                        @if($borrower_type === 'mahasiswa' || $borrower_type === 'dosen')
                            <div>
                                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">Jurusan / Prodi</label>
                                <input type="text" wire:model="borrower_major" required class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Contoh: Teknologi Pendidikan">
                                @error('borrower_major') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($borrower_type === 'mahasiswa')
                            <div>
                                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">Matakuliah (Opsional)</label>
                                <input type="text" wire:model="borrower_subject" class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Jika untuk tugas kuliah">
                                @error('borrower_subject') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($borrower_type === 'organisasi' || $borrower_type === 'mahasiswa')
                            <div>
                                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">
                                    {{ $borrower_type === 'organisasi' ? 'Nama Organisasi' : 'Instansi / Organisasi' }}
                                </label>
                                <input type="text" wire:model="borrower_organization" {{ $borrower_type === 'organisasi' ? 'required' : '' }} class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Contoh: BEM / UKM / Prodi">
                                @error('borrower_organization') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($borrower_type === 'organisasi')
                            <div>
                                <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">Nama Penanggung Jawab</label>
                                <input type="text" wire:model="responsible_person" required class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Nama lengkap pengurus/ketua">
                                @error('responsible_person') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">No. WhatsApp</label>
                            <input type="text" wire:model="borrower_whatsapp" required class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="08xxxxxxxxxx">
                            @error('borrower_whatsapp') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em] mb-2 ml-1">Keperluan Peminjaman</label>
                            <textarea wire:model="purpose" rows="3" required class="w-full rounded-xl border border-zinc-100 bg-zinc-50/50 px-4 py-3.5 text-sm font-medium text-zinc-900 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-500/5" placeholder="Jelaskan secara singkat tujuan peminjaman..."></textarea>
                            @error('purpose') <span class="mt-1.5 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if ($needsKtp)
                        <div class="mt-8 rounded-2xl border border-amber-100 bg-amber-50/30 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                </div>
                                <h3 class="text-sm font-semibold text-amber-900">Upload KTP Wajib</h3>
                            </div>
                            <p class="text-xs text-amber-700/80 mb-5 font-medium leading-relaxed">Salah satu ruangan yang Anda pilih mewajibkan verifikasi identitas resmi (KTP/KTM) dalam format Gambar atau PDF.</p>
                            
                            @if (!$ktp_file)
                                <div class="relative group">
                                    <input type="file" wire:model="ktp_file" accept="image/*,application/pdf" id="ktp-input" class="sr-only">
                                    <label for="ktp-input" class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-amber-200 bg-white py-8 transition hover:border-amber-400 cursor-pointer">
                                        <div wire:loading.remove wire:target="ktp_file" class="flex flex-col items-center">
                                            <svg class="h-8 w-8 text-amber-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span class="text-xs font-bold text-amber-700 uppercase tracking-widest">Pilih Gambar / PDF</span>
                                        </div>
                                        <div wire:loading wire:target="ktp_file" class="flex flex-col items-center">
                                            <svg class="animate-spin h-8 w-8 text-amber-400 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span class="text-xs font-bold text-amber-500 uppercase tracking-widest">Mengunggah...</span>
                                        </div>
                                    </label>
                                </div>
                            @else
                                <div class="flex items-center gap-4 rounded-xl bg-white border border-green-200 p-4 shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                                        @if (Str::endsWith($ktp_file->getClientOriginalName(), '.pdf'))
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-green-700 uppercase tracking-widest">File Berhasil Terunggah</p>
                                        <p class="mt-0.5 text-xs font-medium text-zinc-500 truncate">{{ $ktp_file->getClientOriginalName() }}</p>
                                    </div>
                                    <button type="button" wire:click="$set('ktp_file', null)" class="rounded-lg p-2 text-zinc-400 hover:bg-zinc-100 hover:text-red-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            @endif
                            
                            @error('ktp_file') <span class="mt-2 block text-[11px] font-medium text-red-500">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Submit for Mobile -->
                    <div class="lg:hidden pt-4">
                        <button type="submit" wire:loading.attr="disabled" class="w-full rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-semibold text-white shadow-md transition hover:bg-indigo-700">
                            Konfirmasi Booking Sekarang
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
