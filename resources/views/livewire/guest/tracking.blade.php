@section('title', 'Tracking Booking - SIPIRANG')

<div>
    <section class="mb-8 grid gap-4 lg:grid-cols-[1.5fr_0.9fr]" id="tracking">
        <div class="rounded-2xl border {{ $booking->status === 'approved' ? 'border-green-200 bg-green-50' : ($booking->status === 'rejected' ? 'border-red-200 bg-red-50' : 'border-zinc-200 bg-white') }} p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-wider {{ $booking->status === 'approved' ? 'text-green-700' : ($booking->status === 'rejected' ? 'text-red-700' : 'text-indigo-600') }}">Status Tiket</p>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-zinc-900 sm:text-3xl">
                @if($booking->status === 'approved')
                    Booking Disetujui
                @elseif($booking->status === 'rejected')
                    Booking Ditolak
                @else
                    Status Booking & Surat
                @endif
            </h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-zinc-600">
                @if($booking->status === 'approved')
                    Permohonan Anda telah diverifikasi oleh admin. Silakan unduh surat izin resmi di bawah.
                @elseif($booking->status === 'rejected')
                    Mohon maaf, permohonan Anda tidak dapat kami setujui saat ini. Lihat alasan penolakan di bawah.
                @else
                    Pantau progres validasi dan unduh surat resmi secara real-time.
                @endif
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 flex flex-col justify-center">
                <div class="text-[10px] uppercase tracking-wider font-semibold text-zinc-500">Nomor Tiket</div>
                <div class="mt-1 break-all text-sm font-semibold text-zinc-900 leading-tight">{{ $booking->ticket_number }}</div>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 flex flex-col justify-center">
                <div class="text-[10px] uppercase tracking-wider font-semibold text-zinc-500">Status Saat Ini</div>
                <div class="mt-1 text-sm font-semibold {{ $booking->status === 'approved' ? 'text-green-700' : ($booking->status === 'rejected' ? 'text-red-700' : 'text-zinc-900') }}">
                    {{ $booking->status === 'pending_upload' ? 'Menunggu Upload' : ($booking->status === 'pending_review' ? 'Menunggu Review' : ($booking->status === 'approved' ? 'Disetujui' : ($booking->status === 'rejected' ? 'Ditolak' : $booking->status))) }}
                </div>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-4 flex flex-col justify-center">
                <div class="text-[10px] uppercase tracking-wider font-semibold text-zinc-500">Total Slot</div>
                <div class="mt-1 text-sm font-semibold text-zinc-900">{{ $booking->items->count() }} Ruangan</div>
            </div>
        </div>
    </section>

    @if (session()->has('status'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if($booking->status === 'rejected')
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-6">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-600 text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-red-900">Alasan Penolakan</h3>
                    <p class="mt-1 text-sm text-red-700 leading-relaxed">
                        {{ $booking->rejection_reason ?? 'Maaf, permohonan Anda tidak dapat disetujui karena tidak memenuhi kriteria atau dokumen tidak lengkap.' }}
                    </p>
                    <div class="mt-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Helpers\SettingHelper::get('general.phone', '08123456789')) }}" target="_blank" class="text-xs font-semibold text-red-700 hover:underline inline-flex items-center gap-1">
                            Hubungi Admin via WhatsApp
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8">
            <div class="flex items-center justify-between gap-3 mb-8">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-zinc-900">Progres Booking</h2>
                    <p class="mt-1 text-sm text-zinc-500">Riwayat validasi tiket Anda.</p>
                </div>

                <div class="px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider border {{ $booking->status === 'approved' ? 'bg-green-50 text-green-700 border-green-200' : ($booking->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-zinc-50 text-zinc-600 border-zinc-200') }}">
                    {{ $booking->status === 'pending_upload' ? 'Menunggu Upload' : ($booking->status === 'pending_review' ? 'Menunggu Review' : ($booking->status === 'approved' ? 'Disetujui' : ($booking->status === 'rejected' ? 'Ditolak' : $booking->status))) }}
                </div>
            </div>

            <div class="space-y-7 relative">
                <div class="absolute left-5 top-2 bottom-2 w-px bg-zinc-200"></div>

                @php
                    $steps = [
                        ['label' => 'Booking Dibuat', 'desc' => 'Tiket berhasil didaftarkan ke sistem.', 'status' => true, 'failed' => false],
                        ['label' => 'Upload Surat', 'desc' => 'Batas waktu 5 jam untuk unggah dokumen WD 2.', 'status' => in_array($booking->status, ['pending_review', 'approved']) || $booking->approval_letter_path, 'failed' => $booking->status === 'expired'],
                        ['label' => 'Verifikasi Admin', 'desc' => 'Estimasi verifikasi maksimal 2 hari kerja.', 'status' => in_array($booking->status, ['approved']), 'failed' => $booking->status === 'rejected'],
                        ['label' => 'Selesai', 'desc' => 'Booking disetujui & surat izin terbit.', 'status' => $booking->status === 'approved', 'failed' => false]
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="relative flex gap-5">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 {{ $step['failed'] ? 'bg-red-600 border-red-600 text-white' : ($step['status'] ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-zinc-200 text-zinc-400') }} z-10 transition-colors">
                            @if($step['failed'])
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                            @elseif($step['status'])
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            @else
                                <span class="text-xs font-semibold">{{ $index + 1 }}</span>
                            @endif
                        </div>
                        <div class="flex flex-col pt-1">
                            <span class="text-sm font-semibold {{ $step['failed'] ? 'text-red-700' : ($step['status'] ? 'text-zinc-900' : 'text-zinc-500') }}">{{ $step['label'] }}</span>
                            <span class="text-xs {{ $step['failed'] ? 'text-red-500' : 'text-zinc-500' }} mt-0.5">{{ $step['desc'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($booking->booking_pdf_path)
                <div class="mt-10 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('tracking.pdf', ['ticketNumber' => $booking->ticket_number, 'type' => 'receipt']) }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-200 bg-white px-5 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Tanda Terima Booking
                    </a>

                    @if($booking->status === 'approved' && $booking->approval_pdf_path)
                        <a href="{{ route('tracking.pdf', ['ticketNumber' => $booking->ticket_number, 'type' => 'approval']) }}" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Unduh Surat Izin Resmi
                        </a>
                    @endif
                </div>
            @endif
        </section>

        <aside class="space-y-4">
            {{-- SLA / Countdown Section --}}
            @if ($booking->status === 'pending_upload' && $timeRemaining > 0)
                <div class="rounded-2xl bg-amber-50 border border-amber-200 p-5 text-center" x-data="countdownTimer({{ $timeRemaining }})" x-init="init()">
                    <div class="text-[10px] uppercase tracking-wider font-semibold text-amber-700 mb-2">Sisa Waktu Lengkapi Persyaratan</div>
                    <div class="text-2xl font-semibold text-amber-700 tracking-tight" x-text="format(remaining)">--:--:--</div>
                    <p class="mt-2 text-xs text-amber-700 leading-relaxed">Segera unggah surat persetujuan WD 2 sebelum waktu habis.</p>
                </div>
            @elseif ($booking->status === 'pending_review' && $adminSlaRemaining > 0)
                <div class="rounded-2xl bg-indigo-50 border border-indigo-200 p-5 text-center" x-data="countdownTimer({{ $adminSlaRemaining }})" x-init="init()">
                    <div class="text-[10px] uppercase tracking-wider font-semibold text-indigo-700 mb-2">SLA Verifikasi Admin</div>
                    <div class="text-2xl font-semibold text-indigo-700 tracking-tight" x-text="format(remaining)">--:--:--</div>
                    <p class="mt-2 text-xs text-indigo-700 leading-relaxed">Admin akan meninjau dokumen Anda dalam waktu maksimal 2 hari kerja.</p>
                </div>
            @endif

            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Detail Booking
                </h3>

                <dl class="space-y-3">
                    <div class="flex flex-col gap-1">
                        <dt class="text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">Peminjam</dt>
                        <dd class="text-sm font-semibold text-zinc-900">{{ $booking->borrower_name }}</dd>
                    </div>

                    @if($booking->status === 'approved')
                        <div class="flex flex-col gap-1">
                            <dt class="text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">Waktu Review</dt>
                            <dd class="text-sm font-semibold text-zinc-900">{{ $booking->reviewed_at->translatedFormat('d F Y, H:i') }}</dd>
                        </div>
                    @else
                        <div class="flex flex-col gap-1">
                            <dt class="text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">Deadline Persyaratan</dt>
                            <dd class="text-sm font-semibold text-zinc-900">{{ $booking->deadline_at->translatedFormat('d F Y, H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <h3 class="text-sm font-semibold text-zinc-900 mb-4">Daftar Ruangan & Sesi</h3>
                <div class="space-y-2">
                    @foreach ($booking->items as $item)
                        <div class="p-3 rounded-lg bg-zinc-50 border border-zinc-200">
                            <div class="text-sm font-semibold text-zinc-900">{{ $item->room->name }}</div>
                            <div class="mt-1.5 flex items-center gap-2">
                                <div class="px-2 py-0.5 rounded bg-white border border-zinc-200 text-[10px] font-semibold text-indigo-600 uppercase tracking-wider">
                                    {{ $item->booking_date->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">
                                    {{ $item->session_label }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($canUpload)
                <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                    <h3 class="text-base font-semibold text-zinc-900 mb-1">Unggah Surat WD 2</h3>
                    <p class="text-xs text-zinc-500 mb-5">Unggah file PDF permohonan resmi.</p>

                    <form wire:submit="uploadLetter" class="space-y-3">
                        @if (!$approval_letter)
                            <div class="relative">
                                <input id="file-upload" wire:model="approval_letter" type="file" class="sr-only" accept="application/pdf">
                                <label for="file-upload" class="flex flex-col items-center justify-center rounded-lg border-2 border-dashed border-zinc-300 p-6 transition hover:border-indigo-500 cursor-pointer bg-zinc-50">
                                    <div wire:loading.remove wire:target="approval_letter" class="flex flex-col items-center">
                                        <svg class="h-7 w-7 text-zinc-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                        <span class="text-xs font-semibold text-zinc-600 uppercase tracking-wider">Pilih File PDF</span>
                                    </div>
                                    <div wire:loading wire:target="approval_letter" class="flex flex-col items-center">
                                        <svg class="animate-spin h-7 w-7 text-indigo-500 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Memproses...</span>
                                    </div>
                                </label>
                            </div>
                        @else
                            <div class="flex items-center gap-3 rounded-lg bg-indigo-50 border border-indigo-200 p-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-semibold text-indigo-700 uppercase tracking-wider">File Siap Dikirim</p>
                                    <p class="mt-0.5 text-xs text-zinc-700 truncate">{{ $approval_letter->getClientOriginalName() }}</p>
                                </div>
                                <button type="button" wire:click="$set('approval_letter', null)" class="text-zinc-400 hover:text-red-600 p-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        @endif

                        @error('approval_letter') <span class="text-xs text-red-600 block mt-1">{{ $message }}</span> @enderror

                        <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed" wire:loading.attr="disabled" {{ !$approval_letter ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="uploadLetter">Kirim Persetujuan WD</span>
                            <span wire:loading wire:target="uploadLetter">Mengirim...</span>
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
