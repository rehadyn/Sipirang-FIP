<?php

namespace App\Services;

use App\Exceptions\BookingException;
use App\Helpers\SettingHelper;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function __construct(
        protected SettingHelper $settingHelper,
        protected PDFService $pdfService
    ) {
    }

    /**
     * Create a new booking with items
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $booking = Booking::create([
                'ticket_number' => 'FIP-RNG-' . strtoupper(Str::random(6)),
                'access_token' => hash('sha256', Str::random(32)),
                'borrower_name' => $data['borrower_name'],
                'borrower_id_number' => $data['borrower_id_number'],
                'borrower_type' => $data['borrower_type'] ?? 'mahasiswa',
                'borrower_organization' => $data['borrower_organization'] ?? null,
                'responsible_person' => $data['responsible_person'] ?? null,
                'borrower_whatsapp' => $data['borrower_whatsapp'],
                'purpose' => $data['purpose'],
                'ktp_file_path' => $data['ktp_file_path'] ?? null,
                'status' => Booking::STATUS_PENDING_UPLOAD,
                'deadline_at' => now()->addHours(5),
                'qr_token' => (string) Str::uuid(),
            ]);

            // Create booking items
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    if ($this->checkConflict($item['room_id'], $item['booking_date'], $item['start_time'], $item['end_time'])) {
                        throw new BookingException('Jadwal ruangan bentrok dengan booking lain.');
                    }
                    
                    BookingItem::create([
                        'booking_id' => $booking->id,
                        'room_id' => $item['room_id'],
                        'booking_date' => $item['booking_date'],
                        'session' => $item['session'] ?? null,
                        'start_time' => $item['start_time'],
                        'end_time' => $item['end_time'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Generate Draft PDF
            $pdfPath = $this->pdfService->generateBookingReceipt($booking);
            $booking->update(['booking_pdf_path' => $pdfPath]);

            return $booking;
        });
    }

    /**
     * Check for conflicts with existing bookings
     */
    public function checkConflict($roomId, $date, $startTime, $endTime): bool
    {
        return BookingItem::withConflict($roomId, $date, $startTime, $endTime)->exists();
    }

    /**
     * Check if room is available
     */
    public function isRoomAvailable($roomId, $date, $startTime, $endTime): bool
    {
        $room = \App\Models\Room::find($roomId);
        return $room && $room->isAvailable($date, $startTime, $endTime);
    }

    /**
     * Update booking status to pending review after upload
     */
    public function markAsUploadedApprovalLetter(Booking $booking, $filePath): void
    {
        $booking->update([
            'approval_letter_path' => $filePath,
            'approval_letter_uploaded_at' => now(),
            'status' => Booking::STATUS_PENDING_REVIEW,
        ]);
    }

    /**
     * Approve booking (admin action)
     */
    public function approveBooking(Booking $booking, $userId, $notes = null): void
    {
        $booking->update([
            'status' => Booking::STATUS_APPROVED,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'notes_admin' => $notes,
        ]);

        // Generate Approval PDF
        $pdfPath = $this->pdfService->generateApprovalPDF($booking);
        $booking->update(['approval_pdf_path' => $pdfPath]);
    }

    /**
     * Reject booking (admin action)
     */
    public function rejectBooking(Booking $booking, $userId, $reason): void
    {
        $booking->update([
            'status' => Booking::STATUS_REJECTED,
            'reviewed_by' => $userId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Make booking items available again
        $booking->items()->update(['status' => BookingItem::STATUS_CANCELLED]);
    }

    /**
     * Auto-expire overdue bookings
     */
    public function expireOverdueBookings(): int
    {
        return Booking::overdue()->update([
            'status' => Booking::STATUS_EXPIRED,
            'cancellation_type' => 'auto_expired',
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Record QR verification (check-in)
     */
    public function recordQRVerification(Booking $booking, $verifier = null): void
    {
        $booking->update([
            'qr_verified_at' => now(),
            'qr_verified_by' => $verifier,
            'status' => Booking::STATUS_CHECKED_IN,
        ]);
    }
}
