@section('title', 'Tracking Booking - SIPIRANG')

<div>
    <section class="mb-8 grid gap-4 lg:grid-cols-[1.5fr_0.9fr]" id="tracking">
        <div class="rounded-3xl border border-white {{ $booking->status === 'approved' ? 'bg-green-50/50' : ($booking->status === 'rejected' ? 'bg-red-50/50' : 'bg-white/40') }} backdrop-blur-xl p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] {{ $booking->status === 'approved' ? 'text-green-600' : ($booking->status === 'rejected' ? 'text-red-600' : 'text-indigo-500') }}">Tracking</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-zinc-900 sm:text-4xl">
                @if($booking->status === 'approved')
                    Booking Disetujui!
                @elseif($booking->status === 'rejected')
                    Booking Ditolak
                @else
                    Status Booking & Surat
                @endif
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-zinc-500 sm:text-base font-medium">
                @if($booking->status === 'approved')
                    Permohonan Anda telah diverifikasi oleh admin. Silakan unduh surat izin resmi di bawah.
                @elseif($booking->status === 'rejected')
                    Mohon maaf, permohonan Anda tidak dapat kami setujui saat ini. Lihat alasan penolakan di bawah.
                @else
                    Pantau progres validasi dan unduh surat resmi secara real-time.
                @endif
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
            <div class="rounded-3xl border border-zinc-100 bg-white p-5 shadow-sm flex flex-col justify-center">
                <div class="text-[10px] uppercase tracking-[0.2em] font-bold text-zinc-400">Nomor Tiket</div>
                <div class="mt-1 break-all text-base font-semibold text-zinc-900 leading-tight">{{ $booking->ticket_number }}</div>
            </div>
            <div class="rounded-3xl border border-zinc-100 bg-white p-5 shadow-sm flex flex-col justify-center">
                <div class="text-[10px] uppercase tracking-[0.2em] font-bold text-zinc-400">Status Saat Ini</div>
                <div class="mt-1 text-base font-semibold {{ $booking->status === 'approved' ? 'text-green-600' : ($booking->status === 'rejected' ? 'text-red-600' : 'text-zinc-900') }} uppercase tracking-wide">
                    {{ $booking->status === 'pending_upload' ? 'Menunggu Upload' : ($booking->status === 'pending_review' ? 'Menunggu Review' : ($booking->status === 'approved' ? 'Disetujui' : ($booking->status === 'rejected' ? 'Ditolak' : $booking->status))) }}
                </div>
            </div>
            <div class="rounded-3xl border border-zinc-100 bg-white p-5 shadow-sm flex flex-col justify-center">
                <div class="text-[10px] uppercase tracking-[0.2em] font-bold text-zinc-400">Total Slot</div>
                <div class="mt-1 text-base font-semibold text-zinc-900">{{ $booking->items->count() }} Ruangan</div>
            </div>
        </div>
    </section>

    @if (session()->has('status'))
        <div class="mb-6 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if($booking->status === 'rejected')
        <div class="mb-8 rounded-3xl border border-red-100 bg-red-50 p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-600 text-white shadow-lg shadow-red-500/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-red-900">Alasan Penolakan</h3>
                    <p class="mt-1 text-sm font-medium text-red-700 leading-relaxed">
                        {{ $booking->rejection_reason ?? 'Maaf, permohonan Anda tidak dapat disetujui karena tidak memenuhi kriteria atau dokumen tidak lengkap.' }}
                    </p>
                    <div class="mt-4 flex items-center gap-4">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Helpers\SettingHelper::get('general.phone', '08123456789')) }}" target="_blank" class="text-xs font-bold text-red-600 uppercase tracking-widest hover:underline flex items-center gap-1">
                            Hubungi Admin via WhatsApp
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-3xl border border-zinc-100 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-center justify-between gap-3 mb-10">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-zinc-900">Progres Booking</h2>
                    <p class="mt-1 text-sm font-medium text-zinc-400">Riwayat validasi tiket Anda.</p>
                </div>

                <div class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $booking->status === 'approved' ? 'bg-green-50 text-green-700 border-green-100' : ($booking->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-zinc-50 text-zinc-500 border-zinc-100') }}">
                    {{ $booking->status === 'pending_upload' ? 'Menunggu Upload' : ($booking->status === 'pending_review' ? 'Menunggu Review' : ($booking->status === 'approved' ? 'Disetujui' : ($booking->status === 'rejected' ? 'Ditolak' : $booking->status))) }}
                </div>
            </div>

            <div class="space-y-8 relative">
                <!-- Vertical Line -->
                <div class="absolute left-5 top-2 bottom-2 w-0.5 bg-zinc-50"></div>

                <!-- Steps -->
                @php
                    $steps = [
                        ['label' => 'Booking Dibuat', 'desc' => 'Tiket berhasil didaftarkan ke sistem.', 'status' => true, 'failed' => false],
                        ['label' => 'Upload Surat', 'desc' => 'Batas waktu 5 jam untuk unggah dokumen WD 2.', 'status' => in_array($booking->status, ['pending_review', 'approved']) || $booking->approval_letter_path, 'failed' => $booking->status === 'expired'],
                        ['label' => 'Verifikasi Admin', 'desc' => 'Estimasi verifikasi maksimal 2 hari kerja.', 'status' => in_array($booking->status, ['approved']), 'failed' => $booking->status === 'rejected'],
                        ['label' => 'Selesai', 'desc' => 'Booking disetujui & surat izin terbit.', 'status' => $booking->status === 'approved', 'failed' => false]
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="relative flex gap-6">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 {{ $step['failed'] ? 'bg-red-600 border-red-600 text-white shadow-md shadow-red-500/20' : ($step['status'] ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'bg-white border-zinc-100 text-zinc-300') }} z-10 transition-colors duration-500">
                            @if($step['failed'])
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            @elseif($step['status'])
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            @else
                                <span class="text-xs font-bold">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex flex-col pt-1">
                            <span class="text-sm font-semibold {{ $step['failed'] ? 'text-red-600' : ($step['status'] ? 'text-zinc-900' : 'text-zinc-400') }}">{{ $step['label'] }}</span>
                            <span class="text-xs font-medium {{ $step['failed'] ? 'text-red-400' : 'text-zinc-400' }} mt-0.5">{{ $step['desc'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($booking->booking_pdf_path)
                <div class="mt-12 flex flex-col sm:flex-row gap-3">
                    <a href="{{ Storage::url($booking->booking_pdf_path) }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-200 bg-white px-6 py-3.5 text-sm font-semibold text-zinc-600 transition hover:bg-zinc-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Tanda Terima Booking
                    </a>

                    @if($booking->status === 'approved' && $booking->approval_pdf_path)
                        <a href="{{ Storage::url($booking->approval_pdf_path) }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Unduh Surat Izin Resmi
                        </a>
                    @endif
                </div>
            @endif
        </section>

        <aside class="space-y-6">
            <!-- SLA / Countdown Section -->
            @if ($booking->status === 'pending_upload' && $timeRemaining > 0)
                <div class="rounded-3xl bg-amber-50 border border-amber-100 p-6 text-center shadow-sm" x-data="countdownTimer({{ $timeRemaining }})" x-init="init()">
                    <div class="text-[10px] uppercase tracking-[0.2em] font-bold text-amber-500 mb-2">Sisa Waktu Lengkapi Persyaratan</div>
                    <div class="text-3xl font-semibold text-amber-700 tracking-tight" x-text="format(remaining)">--:--:--</div>
                    <p class="mt-3 text-[10px] font-medium text-amber-600/70 leading-relaxed">Segera unggah surat persetujuan WD 2 sebelum waktu habis.</p>
                </div>
            @elseif ($booking->status === 'pending_review' && $adminSlaRemaining > 0)
                <div class="rounded-3xl bg-indigo-50 border border-indigo-100 p-6 text-center shadow-sm" x-data="countdownTimer({{ $adminSlaRemaining }})" x-init="init()">
                    <div class="text-[10px] uppercase tracking-[0.2em] font-bold text-indigo-500 mb-2">SLA Estimasi Verifikasi Admin</div>
                    <div class="text-3xl font-semibold text-indigo-700 tracking-tight" x-text="format(remaining)">--:--:--</div>
                    <p class="mt-3 text-[10px] font-medium text-indigo-600/70 leading-relaxed">Admin akan meninjau dokumen Anda dalam waktu maksimal 2 hari kerja.</p>
                </div>
            @endif

            <div class="rounded-3xl border border-zinc-100 bg-white p-6 shadow-sm overflow-hidden relative">
                @if($booking->status === 'approved')
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-green-500/10 rounded-full flex items-center justify-center pt-6 pr-6">
                        <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                @elseif($booking->status === 'rejected')
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center pt-6 pr-6">
                        <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                @endif
                
                <h3 class="text-sm font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Detail Booking Terkonfirmasi
                </h3>
                
                <dl class="space-y-4">
                    <div class="flex flex-col gap-1">
                        <dt class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Peminjam</dt>
                        <dd class="text-sm font-semibold text-zinc-900">{{ $booking->borrower_name }}</dd>
                    </div>
                    
                    @if($booking->status === 'approved')
                        <div class="flex flex-col gap-1">
                            <dt class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Waktu Review</dt>
                            <dd class="text-sm font-semibold text-zinc-900">{{ $booking->reviewed_at->translatedFormat('d F Y, H:i') }}</dd>
                        </div>
                    @else
                        <div class="flex flex-col gap-1">
                            <dt class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Deadline Persyaratan</dt>
                            <dd class="text-sm font-semibold text-zinc-900">{{ $booking->deadline_at->translatedFormat('d F Y, H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-3xl border border-zinc-100 bg-white p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-zinc-900 mb-5">Daftar Ruangan & Sesi</h3>
                <div class="space-y-3">
                    @foreach ($booking->items as $item)
                        <div class="p-4 rounded-2xl bg-zinc-50 border border-zinc-100 group transition-all hover:bg-white hover:shadow-sm hover:border-indigo-100">
                            <div class="text-sm font-bold text-zinc-900">{{ $item->room->name }}</div>
                            <div class="mt-2 flex items-center gap-3">
                                <div class="px-2 py-1 rounded-lg bg-white border border-zinc-100 text-[10px] font-bold text-indigo-600 uppercase tracking-widest shadow-sm">
                                    {{ $item->booking_date->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-widest">
                                    {{ $item->session_label }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($canUpload)
                <div class="rounded-3xl bg-zinc-900 p-6 shadow-xl overflow-hidden relative">
                    <!-- Decorative Background Element -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
                    
                    <h3 class="text-base font-semibold text-white mb-1 relative z-10">Unggah Surat</h3>
                    <p class="text-xs text-zinc-400 mb-6 relative z-10">Unggah file PDF permohonan resmi.</p>

                    <form wire:submit="uploadLetter" class="space-y-4 relative z-10">
                        @if (!$approval_letter)
                            <div class="relative group">
                                <input id="file-upload" wire:model="approval_letter" type="file" class="sr-only" accept="application/pdf">
                                <label for="file-upload" class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-zinc-700 p-8 transition hover:border-indigo-500 cursor-pointer bg-zinc-800/30">
                                    <div wire:loading.remove wire:target="approval_letter" class="flex flex-col items-center">
                                        <svg class="h-8 w-8 text-zinc-500 mb-2 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                        <span class="text-[10px] font-bold text-zinc-500 group-hover:text-white uppercase tracking-widest">Pilih File PDF</span>
                                    </div>
                                    <div wire:loading wire:target="approval_letter" class="flex flex-col items-center">
                                        <svg class="animate-spin h-8 w-8 text-indigo-500 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Memproses...</span>
                                    </div>
                                </label>
                            </div>
                        @else
                            <div class="flex items-center gap-3 rounded-xl bg-indigo-500/10 border border-indigo-500/20 p-4 animate-in fade-in zoom-in duration-300">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">File Siap Dikirim</p>
                                    <p class="mt-0.5 text-xs font-medium text-white truncate">{{ $approval_letter->getClientOriginalName() }}</p>
                                </div>
                                <button type="button" wire:click="$set('approval_letter', null)" class="text-zinc-500 hover:text-red-400 p-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        @endif

                        @error('approval_letter') <span class="text-[10px] font-bold text-red-400 block mt-2">{{ $message }}</span> @enderror

                        <button type="submit" class="w-full rounded-xl bg-white px-4 py-3.5 text-sm font-bold text-zinc-900 transition hover:bg-zinc-100 disabled:opacity-30 disabled:cursor-not-allowed group" wire:loading.attr="disabled" {{ !$approval_letter ? 'disabled' : '' }}>
                            <div class="flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="uploadLetter">Kirim Persetujuan WD</span>
                                <span wire:loading wire:target="uploadLetter">Sedang Mengirim...</span>
                                <svg wire:loading.remove wire:target="uploadLetter" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </div>
                        </button>
                    </form>
                </div>
            @endif
        </aside>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('countdownTimer', (initialSeconds) => ({
            remaining: initialSeconds,
            timer: null,
            init() {
                if (this.remaining > 0) {
                    this.timer = setInterval(() => {
                        this.remaining--;
                        if (this.remaining <= 0) {
                            clearInterval(this.timer);
                            window.location.reload();
                        }
                    }, 1000);
                }
            },
            format(seconds) {
                if (seconds <= 0) return '00:00:00';
                const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
                const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
                const s = Math.floor(seconds % 60).toString().padStart(2, '0');
                return `${h}:${m}:${s}`;
            }
        }));
    });
</script>
@endpush
