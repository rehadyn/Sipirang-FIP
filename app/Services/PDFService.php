<?php

namespace App\Services;

use App\Helpers\SettingHelper;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFService
{
    public function __construct(protected SettingHelper $settingHelper)
    {
    }

    /**
     * Generate booking receipt PDF (Dokumen 1: Bukti Booking Sementara)
     */
    public function generateBookingReceipt(Booking $booking): string
    {
        $pdf = Pdf::loadView('pdfs.booking-receipt', [
            'booking' => $booking,
            'faculty' => $this->settingHelper->get('general.faculty_name', 'FIP UNM'),
            'university' => $this->settingHelper->get('general.university_name', 'UNM'),
            'deadline_hours' => $this->settingHelper->get('booking.deadline_hours', 24),
        ]);

        $filename = "BUKTI_BOOKING_{$booking->ticket_number}.pdf";
        $content = $pdf->output();

        $fileService = new FileStorageService();
        $path = $fileService->storePDF($content, $filename);

        return $path;
    }

    /**
     * Generate approval letter PDF (Dokumen 2: Surat Izin Penggunaan)
     */
    public function generateApprovalPDF(Booking $booking): string
    {
        $pdf = Pdf::loadView('pdfs.approval-letter', [
            'booking' => $booking,
            'faculty' => $this->settingHelper->get('general.faculty_name', 'FIP UNM'),
            'university' => $this->settingHelper->get('general.university_name', 'UNM'),
            'qrCodeUrl' => $this->generateQRCode($booking->qr_token),
        ]);

        $filename = "SURAT_IZIN_{$booking->ticket_number}.pdf";
        $content = $pdf->output();

        $fileService = new FileStorageService();
        $path = $fileService->storePDF($content, $filename);

        return $path;
    }

    public function generateQRCode($token): string
    {
        $url = route('tracking.show', ['ticketNumber' => Booking::where('qr_token', $token)->value('ticket_number')]);
        // generate base64 SVG to embed in PDF
        return 'data:image/svg+xml;base64,' . base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->generate($url));
    }

    /**
     * Get PDF for download
     */
    public function getPDFDownload(Booking $booking, $type = 'approval'): ?string
    {
        $fileService = new FileStorageService();
        
        if ($type === 'receipt' && $booking->booking_pdf_path) {
            $filename = "BUKTI_BOOKING_{$booking->ticket_number}.pdf";
            return $fileService->getDownloadResponse('bookings', $booking->booking_pdf_path, $filename);
        }
        
        if ($type === 'approval' && $booking->approval_pdf_path) {
            $filename = "SURAT_IZIN_{$booking->ticket_number}.pdf";
            return $fileService->getDownloadResponse('bookings', $booking->approval_pdf_path, $filename);
        }
        
        return null;
    }
}
