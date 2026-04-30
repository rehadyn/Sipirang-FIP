<?php

namespace App\Services;

use App\Models\Booking;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Carbon\Carbon;

class ExcelReportService
{
    public function generateMonthlyReport(int $month, int $year): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Booking');

        // Fetch data
        $bookings = Booking::with(['items.room.building', 'reviewer'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at')
            ->get();

        $periodLabel = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        $now = now()->translatedFormat('d F Y, H:i');

        $this->setColumnWidths($sheet);
        $this->buildHeader($sheet, $periodLabel);
        $this->buildTableHeader($sheet);
        $lastRow = $this->buildTableBody($sheet, $bookings);
        $this->buildFooter($sheet, $bookings, $lastRow, $now);

        // Save file
        $filename = 'laporan_booking_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.xlsx';
        $path = storage_path('app/reports/' . $filename);

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    private function setColumnWidths($sheet): void
    {
        $widths = ['A' => 5, 'B' => 18, 'C' => 25, 'D' => 12, 'E' => 14, 'F' => 22, 'G' => 14, 'H' => 15, 'I' => 16, 'J' => 18, 'K' => 18];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }

    private function buildHeader($sheet, string $periodLabel): void
    {
        // Row 1: Instansi
        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'FAKULTAS ILMU PENDIDIKAN');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Row 2: Sub-instansi
        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', 'Universitas Negeri — Sub-unit Pengelolaan Ruangan');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 11, 'color' => ['rgb' => '374151']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Row 3: Judul Laporan
        $sheet->mergeCells('A3:K3');
        $sheet->setCellValue('A3', 'LAPORAN BOOKING RUANGAN BULANAN');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Row 4: Periode
        $sheet->mergeCells('A4:K4');
        $sheet->setCellValue('A4', 'Periode: ' . $periodLabel);
        $sheet->getStyle('A4')->applyFromArray([
            'font'      => ['size' => 11, 'italic' => true, 'color' => ['rgb' => '4B5563']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Row 5: Garis bawah header
        $sheet->mergeCells('A5:K5');
        $sheet->getStyle('A5:K5')->applyFromArray([
            'borders' => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1E3A5F']]],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(6);
    }

    private function buildTableHeader($sheet): void
    {
        $headers = ['No', 'No. Tiket', 'Nama Peminjam', 'Tipe', 'Ruangan', 'Gedung', 'Tanggal', 'Sesi', 'Status', 'Tgl. Review', 'Reviewer'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach ($cols as $i => $col) {
            $sheet->setCellValue($col . '6', $headers[$i]);
        }

        $sheet->getStyle('A6:K6')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(20);
    }

    private function buildTableBody($sheet, $bookings): int
    {
        $row = 7;
        $no = 1;
        $statusMap = [
            'pending_upload'  => 'Menunggu Upload',
            'pending_review'  => 'Menunggu Review',
            'approved'        => 'Disetujui',
            'rejected'        => 'Ditolak',
            'expired'         => 'Kedaluwarsa',
            'cancelled'       => 'Dibatalkan',
            'checked_in'      => 'Check-in',
            'completed'       => 'Selesai',
        ];

        $typeMap = [
            'mahasiswa'  => 'Mahasiswa',
            'dosen'      => 'Dosen',
            'organisasi' => 'Organisasi',
            'lainnya'    => 'Lainnya',
        ];

        foreach ($bookings as $booking) {
            $firstItem = $booking->items->first();
            $roomName = $firstItem?->room?->name ?? '-';
            $buildingName = $firstItem?->room?->building?->name ?? '-';
            $bookingDate = $firstItem?->booking_date?->format('d/m/Y') ?? '-';
            $session = $firstItem?->session_label ?? '-';

            $bgColor = $row % 2 === 0 ? 'F9FAFB' : 'FFFFFF';

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $booking->ticket_number);
            $sheet->setCellValue('C' . $row, $booking->borrower_name);
            $sheet->setCellValue('D' . $row, $typeMap[$booking->borrower_type] ?? $booking->borrower_type);
            $sheet->setCellValue('E' . $row, $roomName);
            $sheet->setCellValue('F' . $row, $buildingName);
            $sheet->setCellValue('G' . $row, $bookingDate);
            $sheet->setCellValue('H' . $row, $session);
            $sheet->setCellValue('I' . $row, $statusMap[$booking->status] ?? $booking->status);
            $sheet->setCellValue('J' . $row, $booking->reviewed_at?->format('d/m/Y') ?? '-');
            $sheet->setCellValue('K' . $row, $booking->reviewer?->name ?? '-');

            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                'font'      => ['size' => 9],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
            ]);

            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)->setRowHeight(18);

            $row++;
            $no++;
        }

        if ($bookings->isEmpty()) {
            $sheet->mergeCells('A7:K7');
            $sheet->setCellValue('A7', 'Tidak ada data booking pada periode ini.');
            $sheet->getStyle('A7')->applyFromArray([
                'font'      => ['italic' => true, 'color' => ['rgb' => '9CA3AF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $row = 8;
        }

        return $row;
    }

    private function buildFooter($sheet, $bookings, int $startRow, string $printDate): void
    {
        $row = $startRow + 1;

        // Garis pembatas
        $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
            'borders' => ['top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '1E3A5F']]],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(6);
        $row++;

        // Rekap statistik
        $total      = $bookings->count();
        $approved   = $bookings->where('status', 'approved')->count();
        $rejected   = $bookings->where('status', 'rejected')->count();
        $pending    = $bookings->whereIn('status', ['pending_upload', 'pending_review'])->count();
        $expired    = $bookings->where('status', 'expired')->count();

        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->setCellValue('A' . $row, 'REKAPITULASI:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(10)->getColor()->setRGB('1E3A5F');

        $recaps = [
            ['Total Booking', $total],
            ['Disetujui', $approved],
            ['Ditolak', $rejected],
            ['Menunggu Proses', $pending],
            ['Kedaluwarsa', $expired],
        ];

        $sheet->getRowDimension($row)->setRowHeight(16);
        $row++;

        foreach ($recaps as [$label, $count]) {
            $sheet->mergeCells('A' . $row . ':C' . $row);
            $sheet->setCellValue('A' . $row, '  ' . $label . ' : ' . $count . ' booking');
            $sheet->getStyle('A' . $row)->getFont()->setSize(9);
            $sheet->getRowDimension($row)->setRowHeight(14);
            $row++;
        }

        $row++;

        // Kolom tanda tangan
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->setCellValue('A' . $row, 'Dicetak: ' . $printDate);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font'      => ['size' => 9, 'italic' => true, 'color' => ['rgb' => '6B7280']],
        ]);

        $sheet->mergeCells('I' . $row . ':K' . $row);
        $sheet->setCellValue('I' . $row, 'Mengetahui,');
        $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I' . $row)->getFont()->setSize(9);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row++;

        $sheet->mergeCells('I' . $row . ':K' . $row);
        $sheet->setCellValue('I' . $row, 'Penanggung Jawab Ruangan');
        $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I' . $row)->getFont()->setSize(9)->setItalic(true);
        $sheet->getRowDimension($row)->setRowHeight(14);

        // Ruang tanda tangan (4 baris kosong)
        $row += 4;

        $sheet->mergeCells('I' . $row . ':K' . $row);
        $sheet->setCellValue('I' . $row, '( ______________________________ )');
        $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I' . $row)->getFont()->setSize(9);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row++;

        $sheet->mergeCells('I' . $row . ':K' . $row);
        $sheet->setCellValue('I' . $row, 'NIP. ___________________________');
        $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I' . $row)->getFont()->setSize(9);
        $sheet->getRowDimension($row)->setRowHeight(14);
    }
}
