<div x-data="{ approveOpen: @entangle('showApproveModal'), rejectOpen: @entangle('showRejectModal') }">

    {{-- Header --}}
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.bookings.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Detail Booking</h1>
            <p class="font-mono text-sm text-indigo-600 mt-0.5">{{ $booking->ticket_number }}</p>
        </div>
        @php
        $statusColors = ['pending_upload'=>'bg-slate-100 text-slate-600','pending_review'=>'bg-amber-100 text-amber-700','approved'=>'bg-emerald-100 text-emerald-700','rejected'=>'bg-red-100 text-red-700','expired'=>'bg-slate-100 text-slate-500','cancelled'=>'bg-slate-100 text-slate-500','checked_in'=>'bg-blue-100 text-blue-700','completed'=>'bg-indigo-100 text-indigo-700'];
        $statusLabels = ['pending_upload'=>'Menunggu Upload','pending_review'=>'Menunggu Review','approved'=>'Disetujui','rejected'=>'Ditolak','expired'=>'Kedaluwarsa','cancelled'=>'Dibatalkan','checked_in'=>'Check-in','completed'=>'Selesai'];
        @endphp
        <span class="ml-auto rounded-full px-3 py-1 text-sm font-semibold {{ $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">
            {{ $statusLabels[$booking->status] ?? $booking->status }}
        </span>
        <a href="{{ route('tracking.show', $booking->ticket_number) }}?qr={{ $booking->qr_token }}"
           target="_blank"
           class="ml-2 inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 transition"
           title="Buka halaman tracking peminjam">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Tracking
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Left: Booking Info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Peminjam --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Informasi Peminjam</h2>
                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div><p class="text-slate-400 text-xs">Nama</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->borrower_name }}</p></div>
                    <div><p class="text-slate-400 text-xs">No. Identitas</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->borrower_id_number }}</p></div>
                    <div><p class="text-slate-400 text-xs">Tipe</p><p class="font-medium text-slate-800 mt-0.5 capitalize">{{ $booking->borrower_type }}</p></div>
                    <div>
                        <p class="text-slate-400 text-xs">WhatsApp</p>
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $booking->borrower_whatsapp ?? '');
                            if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);
                            elseif (!str_starts_with($wa, '62')) $wa = '62' . $wa;
                        @endphp
                        <a href="https://wa.me/{{ $wa }}" target="_blank"
                           class="font-medium text-indigo-600 hover:underline mt-0.5 inline-flex items-center gap-1 text-sm">
                            {{ $booking->borrower_whatsapp }}
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                    @if($booking->borrower_major)<div><p class="text-slate-400 text-xs">Jurusan</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->borrower_major }}</p></div>@endif
                    @if($booking->borrower_subject)<div><p class="text-slate-400 text-xs">Mata Kuliah</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->borrower_subject }}</p></div>@endif
                    @if($booking->borrower_organization)<div><p class="text-slate-400 text-xs">Organisasi</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->borrower_organization }}</p></div>@endif
                    @if($booking->responsible_person)<div><p class="text-slate-400 text-xs">Penanggung Jawab</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->responsible_person }}</p></div>@endif
                    <div class="col-span-2"><p class="text-slate-400 text-xs">Keperluan</p><p class="font-medium text-slate-800 mt-0.5">{{ $booking->purpose }}</p></div>
                </div>
            </div>

            {{-- Items --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Jadwal Peminjaman ({{ $booking->items->count() }} item)</h2>
                <div class="space-y-3">
                    @foreach($booking->items as $item)
                    <div class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800">{{ $item->room?->name }}</p>
                            <p class="text-xs text-slate-500">{{ $item->room?->building?->name }} &bull; {{ $item->booking_date?->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-700">{{ $item->session_label }}</p>
                            <p class="text-xs text-slate-400">{{ $item->time_range }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Dokumen --}}
            @php
                $hasKtp       = $booking->ktp_file_path && \Illuminate\Support\Facades\Storage::disk('uploads')->exists($booking->ktp_file_path);
                $hasLetter    = $booking->approval_letter_path && \Illuminate\Support\Facades\Storage::disk('uploads')->exists($booking->approval_letter_path);
                $hasBookingPdf = $booking->booking_pdf_path && \Illuminate\Support\Facades\Storage::disk('bookings')->exists($booking->booking_pdf_path);
                $hasApprovalPdf = $booking->approval_pdf_path && \Illuminate\Support\Facades\Storage::disk('bookings')->exists($booking->approval_pdf_path);
            @endphp

            @if($hasKtp || $hasLetter || $hasBookingPdf || $hasApprovalPdf)
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Berkas & Dokumen</h2>
                <div class="space-y-3">

                    {{-- PDF Booking Receipt (berkas awal 1) --}}
                    @if($hasBookingPdf)
                    <div class="flex items-center gap-3 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-100">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-indigo-900">PDF Booking (Berkas Awal)</p>
                            <p class="text-xs text-indigo-500 mt-0.5">Dokumen konfirmasi booking yang diterima peminjam</p>
                        </div>
                        <button wire:click="downloadBookingPdf"
                            wire:loading.attr="disabled"
                            wire:target="downloadBookingPdf"
                            class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500 transition active:scale-95 disabled:opacity-60">
                            <svg class="h-3.5 w-3.5" wire:loading.remove wire:target="downloadBookingPdf" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <svg class="h-3.5 w-3.5 animate-spin" wire:loading wire:target="downloadBookingPdf" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span wire:loading.remove wire:target="downloadBookingPdf">Unduh</span>
                            <span wire:loading wire:target="downloadBookingPdf">...</span>
                        </button>
                    </div>
                    @endif

                    {{-- KTP / Identitas (berkas awal 2) --}}
                    @if($hasKtp)
                    @php
                        $ktpExt = strtolower(pathinfo($booking->ktp_file_path ?? '', PATHINFO_EXTENSION));
                        $ktpIsImage = in_array($ktpExt, ['jpg', 'jpeg', 'png', 'webp']);
                    @endphp
                    <div x-data="{ previewOpen: false }" class="rounded-xl border border-slate-200 bg-slate-50 overflow-hidden">
                        <div class="flex items-center gap-3 px-4 py-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800">KTP / Identitas Peminjam</p>
                                <p class="text-xs text-slate-400 mt-0.5">Format: {{ strtoupper($ktpExt) }} — diupload saat checkout</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button @click="previewOpen = !previewOpen"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span x-text="previewOpen ? 'Tutup' : 'Preview'"></span>
                                </button>
                                <button wire:click="downloadKtp" wire:loading.attr="disabled" wire:target="downloadKtp"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition active:scale-95 disabled:opacity-60">
                                    <svg class="h-3.5 w-3.5" wire:loading.remove wire:target="downloadKtp" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <svg class="h-3.5 w-3.5 animate-spin" wire:loading wire:target="downloadKtp" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span wire:loading.remove wire:target="downloadKtp">Unduh</span>
                                    <span wire:loading wire:target="downloadKtp">...</span>
                                </button>
                            </div>
                        </div>
                        {{-- Inline Preview --}}
                        <div x-show="previewOpen" x-transition.opacity class="border-t border-slate-200 bg-white p-3">
                            @if($ktpIsImage)
                                <img src="{{ route('admin.bookings.preview', [$booking, 'ktp']) }}"
                                    alt="KTP Peminjam"
                                    class="max-w-full rounded-lg border border-slate-200 mx-auto block"
                                    style="max-height: 500px; object-fit: contain;">
                            @else
                                <iframe src="{{ route('admin.bookings.preview', [$booking, 'ktp']) }}"
                                    class="w-full rounded-lg"
                                    style="height: 500px;"
                                    title="Preview KTP">
                                </iframe>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Surat Persetujuan WD2 (diupload peminjam) --}}
                    @if($hasLetter)
                    <div x-data="{ previewOpen: false }" class="rounded-xl border border-amber-100 bg-amber-50 overflow-hidden">
                        <div class="flex items-center gap-3 px-4 py-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-100">
                                <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-amber-900">Surat Persetujuan WD2</p>
                                <p class="text-xs text-amber-500 mt-0.5">Diunggah: {{ $booking->approval_letter_uploaded_at?->translatedFormat('d M Y, H:i:s') ?? '—' }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button @click="previewOpen = !previewOpen"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-amber-300 bg-white px-3 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-50 transition">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span x-text="previewOpen ? 'Tutup' : 'Preview'"></span>
                                </button>
                                <button wire:click="downloadApprovalLetter" wire:loading.attr="disabled" wire:target="downloadApprovalLetter"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-500 transition active:scale-95 disabled:opacity-60">
                                    <svg class="h-3.5 w-3.5" wire:loading.remove wire:target="downloadApprovalLetter" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <svg class="h-3.5 w-3.5 animate-spin" wire:loading wire:target="downloadApprovalLetter" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    <span wire:loading.remove wire:target="downloadApprovalLetter">Unduh</span>
                                    <span wire:loading wire:target="downloadApprovalLetter">...</span>
                                </button>
                            </div>
                        </div>
                        {{-- Inline PDF Preview --}}
                        <div x-show="previewOpen" x-transition.opacity class="border-t border-amber-200">
                            <iframe src="{{ route('admin.bookings.preview', [$booking, 'letter']) }}"
                                class="w-full rounded-b-xl bg-white"
                                style="height: 600px;"
                                title="Preview Surat WD2">
                            </iframe>
                        </div>
                    </div>
                    @endif

                    {{-- PDF Surat Persetujuan Admin (generated setelah approve) --}}
                    @if($hasApprovalPdf)
                    <div class="flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-100">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-emerald-900">PDF Persetujuan Admin</p>
                            <p class="text-xs text-emerald-500 mt-0.5">Dibuat otomatis saat booking disetujui</p>
                        </div>
                        <button wire:click="downloadApprovalPdf"
                            wire:loading.attr="disabled"
                            wire:target="downloadApprovalPdf"
                            class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500 transition active:scale-95 disabled:opacity-60">
                            <svg class="h-3.5 w-3.5" wire:loading.remove wire:target="downloadApprovalPdf" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <svg class="h-3.5 w-3.5 animate-spin" wire:loading wire:target="downloadApprovalPdf" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span wire:loading.remove wire:target="downloadApprovalPdf">Unduh</span>
                            <span wire:loading wire:target="downloadApprovalPdf">...</span>
                        </button>
                    </div>
                    @endif

                </div>
            </div>
            @endif


            {{-- Admin Notes --}}
            @if($booking->status === 'pending_review')
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-3">Catatan Admin (Opsional)</h2>
                <textarea wire:model="adminNotes" rows="3" placeholder="Tambahkan catatan untuk peminjam..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-indigo-400 focus:bg-white resize-none"></textarea>
            </div>
            @endif
        </div>

        {{-- Right: Actions & Timeline --}}
        <div class="space-y-5">

            {{-- Actions --}}
            @if($booking->status === 'pending_review')
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Tindakan Admin</h2>
                <div class="space-y-2">
                    <button wire:click="openApproveModal"
                        class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white hover:bg-emerald-500 transition flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui Booking
                    </button>
                    <button wire:click="openRejectModal"
                        class="w-full rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700 hover:bg-red-100 transition flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak Booking
                    </button>
                </div>
            </div>
            @endif

            @if($booking->rejection_reason)
            <div class="rounded-2xl border border-red-100 bg-red-50 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-600 text-white">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-red-900">Alasan Penolakan</h2>
                        <p class="mt-1 text-sm text-red-700 leading-relaxed">{{ $booking->rejection_reason }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($booking->notes_admin)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                <h2 class="text-sm font-semibold text-amber-800 mb-2">Catatan Admin</h2>
                <p class="text-sm text-amber-700">{{ $booking->notes_admin }}</p>
            </div>
            @endif

            {{-- Timeline --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Riwayat</h2>
                <div class="space-y-3 text-xs">
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center"><div class="h-2 w-2 rounded-full bg-indigo-500 mt-1"></div><div class="flex-1 w-px bg-slate-200 my-1"></div></div>
                        <div><p class="font-medium text-slate-700">Booking dibuat</p><p class="text-slate-400">{{ $booking->created_at->translatedFormat('d M Y, H:i') }}</p></div>
                    </div>
                    @if($booking->approval_letter_uploaded_at)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center"><div class="h-2 w-2 rounded-full bg-blue-500 mt-1"></div><div class="flex-1 w-px bg-slate-200 my-1"></div></div>
                        <div><p class="font-medium text-slate-700">Surat diunggah</p><p class="text-slate-400">{{ $booking->approval_letter_uploaded_at->translatedFormat('d M Y, H:i:s') }}</p></div>
                    </div>
                    @endif
                    @if($booking->reviewed_at)
                    <div class="flex gap-3">
                        <div class="h-2 w-2 rounded-full {{ $booking->status === 'approved' ? 'bg-emerald-500' : 'bg-red-500' }} mt-1 flex-shrink-0"></div>
                        <div><p class="font-medium text-slate-700">Direview oleh {{ $booking->reviewer?->name }}</p><p class="text-slate-400">{{ $booking->reviewed_at->translatedFormat('d M Y, H:i') }}</p></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Approve Modal --}}
    <div x-show="approveOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="approveOpen" x-transition.opacity class="fixed inset-0 bg-black/50" @click="approveOpen = false"></div>
        <div x-show="approveOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 mx-auto mb-4">
                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 text-center">Setujui Booking?</h3>
            <p class="text-sm text-slate-500 text-center mt-1 mb-5">Booking <strong>{{ $booking->ticket_number }}</strong> akan disetujui dan PDF persetujuan akan dibuat.</p>
            <div class="flex gap-3">
                <button @click="approveOpen = false" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</button>
                <button wire:click="approve" wire:loading.attr="disabled" class="flex-1 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500 transition">
                    <span wire:loading.remove wire:target="approve">Ya, Setujui</span>
                    <span wire:loading wire:target="approve">Memproses...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div x-show="rejectOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="rejectOpen" x-transition.opacity class="fixed inset-0 bg-black/50" @click="rejectOpen = false"></div>
        <div x-show="rejectOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mx-auto mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 text-center">Tolak Booking?</h3>
            <p class="text-sm text-slate-500 text-center mt-1 mb-4">Masukkan alasan penolakan yang jelas untuk peminjam.</p>
            <textarea wire:model="rejectionReason" rows="3" placeholder="Alasan penolakan (minimal 10 karakter)..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-red-400 focus:bg-white resize-none mb-4"></textarea>
            @error('rejectionReason')<p class="text-xs text-red-600 mb-3">{{ $message }}</p>@enderror
            <div class="flex gap-3">
                <button @click="rejectOpen = false" class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</button>
                <button wire:click="reject" wire:loading.attr="disabled" class="flex-1 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">
                    <span wire:loading.remove wire:target="reject">Ya, Tolak</span>
                    <span wire:loading wire:target="reject">Memproses...</span>
                </button>
            </div>
        </div>
    </div>

</div>
