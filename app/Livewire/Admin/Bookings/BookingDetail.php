<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingDetail extends Component
{
    public Booking $booking;
    public string $adminNotes = '';
    public string $rejectionReason = '';
    public bool $showApproveModal = false;
    public bool $showRejectModal = false;

    public function mount(Booking $booking): void
    {
        $this->booking = $booking->load(['items.room.building', 'documents', 'reviewer']);
        $this->adminNotes = $booking->notes_admin ?? '';
    }

    public function openApproveModal(): void
    {
        $this->showApproveModal = true;
    }

    public function openRejectModal(): void
    {
        $this->showRejectModal = true;
    }

    public function approve(BookingService $bookingService): void
    {
        if ($this->booking->status !== Booking::STATUS_PENDING_REVIEW) {
            session()->flash('error', 'Booking ini tidak dalam status menunggu review.');
            $this->showApproveModal = false;
            return;
        }

        $bookingService->approveBooking($this->booking, auth()->id(), $this->adminNotes ?: null);
        $this->booking->refresh();
        $this->showApproveModal = false;
        session()->flash('success', 'Booking berhasil disetujui.');
    }

    public function reject(BookingService $bookingService): void
    {
        $this->validate(['rejectionReason' => ['required', 'string', 'min:10', 'max:500']]);

        if ($this->booking->status !== Booking::STATUS_PENDING_REVIEW) {
            session()->flash('error', 'Booking ini tidak dalam status menunggu review.');
            $this->showRejectModal = false;
            return;
        }

        $bookingService->rejectBooking($this->booking, auth()->id(), $this->rejectionReason);
        $this->booking->refresh();
        $this->showRejectModal = false;
        session()->flash('success', 'Booking berhasil ditolak.');
    }

    /**
     * Download KTP / Identitas file (private disk: uploads)
     */
    public function downloadKtp(): StreamedResponse
    {
        abort_unless($this->booking->ktp_file_path && Storage::disk('uploads')->exists($this->booking->ktp_file_path), 404, 'File tidak ditemukan.');

        $ext = pathinfo($this->booking->ktp_file_path, PATHINFO_EXTENSION);
        $filename = 'KTP_' . $this->booking->ticket_number . '.' . $ext;

        return Storage::disk('uploads')->download($this->booking->ktp_file_path, $filename);
    }

    /**
     * Download surat persetujuan WD2 (private disk: uploads)
     */
    public function downloadApprovalLetter(): StreamedResponse
    {
        abort_unless($this->booking->approval_letter_path && Storage::disk('uploads')->exists($this->booking->approval_letter_path), 404, 'File tidak ditemukan.');

        $filename = 'Surat_Persetujuan_' . $this->booking->ticket_number . '.pdf';

        return Storage::disk('uploads')->download($this->booking->approval_letter_path, $filename);
    }

    /**
     * Download PDF booking receipt (disk: bookings)
     */
    public function downloadBookingPdf(): StreamedResponse
    {
        abort_unless($this->booking->booking_pdf_path && Storage::disk('bookings')->exists($this->booking->booking_pdf_path), 404, 'File PDF belum tersedia.');

        $filename = 'Booking_Receipt_' . $this->booking->ticket_number . '.pdf';

        return Storage::disk('bookings')->download($this->booking->booking_pdf_path, $filename);
    }

    /**
     * Download PDF persetujuan admin (disk: bookings)
     */
    public function downloadApprovalPdf(): StreamedResponse
    {
        abort_unless($this->booking->approval_pdf_path && Storage::disk('bookings')->exists($this->booking->approval_pdf_path), 404, 'File PDF belum tersedia.');

        $filename = 'Surat_Persetujuan_Admin_' . $this->booking->ticket_number . '.pdf';

        return Storage::disk('bookings')->download($this->booking->approval_pdf_path, $filename);
    }

    public function render()
    {
        return view('livewire.admin.bookings.detail')->layout('layouts.admin');
    }
}
