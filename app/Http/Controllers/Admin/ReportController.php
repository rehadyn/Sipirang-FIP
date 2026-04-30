<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExcelReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function download(Request $request, ExcelReportService $excelService)
    {
        $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year'  => ['required', 'integer', 'min:2020', 'max:' . (date('Y') + 1)],
        ]);

        $month = (int) $request->month;
        $year  = (int) $request->year;

        $path = $excelService->generateMonthlyReport($month, $year);

        $filename = 'Laporan_Booking_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.xlsx';

        return response()->download($path, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(false);
    }
}
