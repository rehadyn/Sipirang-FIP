<?php

namespace App\Livewire\Guest;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class Tracking extends Component
{
    use WithFileUploads;

    public $ticketNumber;
    public $phone;
    public $booking;
    
    public $approval_letter;

    public function mount($ticketNumber, Request $request)
    {
        $this->ticketNumber = $ticketNumber;
        $this->phone = $request->query('phone', '');

        $this->booking = Booking::with(['items.room.building', 'documents', 'reviewer'])
            ->where('ticket_number', $this->ticketNumber)
            ->firstOrFail();

        abort_unless($this->phone !== '' && $this->booking->borrower_whatsapp === $this->phone, 403, 'Akses tracking tidak valid. Nomor WhatsApp tidak sesuai.');
    }

    public function uploadLetter(BookingService $bookingService, FileStorageService $fileStorageService)
    {
        $this->validate([
            'approval_letter' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        if (! $this->booking->can_upload) {
            $this->addError('approval_letter', 'Batas waktu upload sudah lewat atau status booking tidak memungkinkan.');
            return;
        }

        $filePath = $fileStorageService->storeApprovalLetter($this->approval_letter);
        $bookingService->markAsUploadedApprovalLetter($this->booking, $filePath);

        $this->booking->refresh();
        $this->approval_letter = null;

        session()->flash('status', 'Surat berhasil diunggah dan menunggu verifikasi admin.');
    }

    public function render()
    {
        return view('livewire.guest.tracking', [
            'canUpload' => $this->booking->can_upload,
            'timeRemaining' => $this->booking->time_remaining,
            'adminSlaRemaining' => $this->booking->admin_sla_remaining,
        ])->layout('layouts.guest');
    }
}
