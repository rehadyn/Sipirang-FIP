<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReportController;
use App\Livewire\Guest\LiveBoard;
use App\Livewire\Guest\Checkout;
use App\Livewire\Guest\Tracking;
use App\Livewire\Guest\Guide;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// ─── Guest Routes ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('home');
});

Route::get('/guide', Guide::class)->name('guest.guide');

Route::prefix('booking')->name('guest.bookings.')->group(function () {
    Route::get('/rooms', LiveBoard::class)->name('rooms');
    Route::get('/checkout', Checkout::class)->name('checkout.show');
});

Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/{ticketNumber}', Tracking::class)->name('show');
    Route::get('/{ticketNumber}/pdf/{type}', function (string $ticketNumber, string $type) {
        abort_unless(in_array($type, ['receipt', 'approval'], true), 404);

        $booking = Booking::where('ticket_number', $ticketNumber)->firstOrFail();

        if ($type === 'approval') {
            abort_unless($booking->status === Booking::STATUS_APPROVED, 403);
            $path = $booking->approval_pdf_path;
            $filename = "Surat_Izin_{$booking->ticket_number}.pdf";
        } else {
            $path = $booking->booking_pdf_path;
            $filename = "Bukti_Booking_{$booking->ticket_number}.pdf";
        }

        abort_if(!$path || !Storage::disk('bookings')->exists($path), 404, 'File PDF tidak ditemukan.');

        return Storage::disk('bookings')->response($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    })->where('type', 'receipt|approval')->name('pdf');
});

Route::get('/calendar', \App\Livewire\Guest\RoomCalendar::class)->name('guest.calendar');

// ─── Admin Auth Routes ──────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ─── Admin Protected Routes ─────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {

    // Dashboard
    Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

    // Calendar
    Route::get('/calendar', \App\Livewire\Admin\RoomCalendar::class)->name('calendar');

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Bookings\BookingList::class)->name('index');
        Route::get('/{booking}', \App\Livewire\Admin\Bookings\BookingDetail::class)->name('show');

        // Inline preview untuk dokumen upload (WD2, KTP)
        Route::get('/{booking}/preview/{type}', function (\App\Models\Booking $booking, string $type) {
            abort_unless(in_array($type, ['letter', 'ktp'], true), 404);

            if ($type === 'letter') {
                $path = $booking->approval_letter_path;
                $disk = 'uploads';
                $mime = 'application/pdf';
            } else {
                $path = $booking->ktp_file_path;
                $disk = 'uploads';
                $ext  = pathinfo($path ?? '', PATHINFO_EXTENSION);
                $mime = in_array(strtolower($ext), ['jpg','jpeg','png','webp']) ? 'image/'.$ext : 'application/pdf';
            }

            abort_if(!$path || !Storage::disk($disk)->exists($path), 404, 'File tidak ditemukan.');

            return Storage::disk($disk)->response($path, basename($path), [
                'Content-Type'        => $mime,
                'Content-Disposition' => 'inline',
            ]);
        })->where('type', 'letter|ktp')->name('preview');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Reports\MonthlyReport::class)->name('index');
        Route::get('/download', [ReportController::class, 'download'])->name('download');
    });

    // Rooms
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Rooms\RoomList::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Rooms\RoomForm::class)->name('create');
        Route::get('/{room}/edit', \App\Livewire\Admin\Rooms\RoomForm::class)->name('edit');
    });

    // Buildings
    Route::prefix('buildings')->name('buildings.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Buildings\BuildingList::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Buildings\BuildingForm::class)->name('create');
        Route::get('/{building}/edit', \App\Livewire\Admin\Buildings\BuildingForm::class)->name('edit');
    });

    // Settings & Users — Sysadmin only
    Route::middleware(['sysadmin'])->group(function () {

        Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');

        // PDF preview dummy untuk testing dari halaman Pengaturan
        Route::get('/settings/pdf-preview/{type}', function (string $type) {
            abort_unless(in_array($type, ['receipt', 'approval'], true), 404);

            // Dummy data — tidak disimpan ke DB
            $building = new \App\Models\Building(['name' => 'Gedung A - Administrasi', 'code' => 'GED-A']);
            $room     = new \App\Models\Room(['name' => 'Ruang Rapat Dekanat', 'code' => 'R-01', 'floor' => '1']);
            $room->setRelation('building', $building);

            $item = new \App\Models\BookingItem([
                'booking_date' => now()->addDays(3)->format('Y-m-d'),
                'session'      => \App\Models\Booking::SESSION_PAGI,
                'start_time'   => now()->setTime(7, 0)->toDateTimeString(),
                'end_time'     => now()->setTime(12, 0)->toDateTimeString(),
            ]);
            $item->setRelation('room', $room);

            $booking = new \App\Models\Booking([
                'ticket_number'         => 'FIP-RNG-SAMPLE',
                'borrower_name'         => 'Contoh Nama Peminjam',
                'borrower_id_number'    => '200101001',
                'borrower_type'         => 'mahasiswa',
                'borrower_organization' => 'Prodi Teknologi Pendidikan',
                'purpose'               => 'Diskusi Kelompok dan Rapat Koordinasi',
                'notes_admin'           => 'Pastikan ruangan dikembalikan dalam kondisi rapi.',
                'status'                => 'approved',
                'qr_token'              => \Illuminate\Support\Str::uuid()->toString(),
                'reviewed_at'           => now(),
            ]);
            $booking->setRelation('items', collect([$item]));

            $faculty      = \App\Helpers\SettingHelper::get('general.faculty_name', 'FIP UNM');
            $university   = \App\Helpers\SettingHelper::get('general.university_name', 'UNM');
            $deadlineHours = \App\Helpers\SettingHelper::get('booking.deadline_hours', 5);

            if ($type === 'receipt') {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.booking-receipt', compact(
                    'booking', 'faculty', 'university', 'deadlineHours'
                ) + ['deadline_hours' => $deadlineHours]);
                return $pdf->stream('PREVIEW_Tanda_Terima.pdf');
            }

            $qrUrl = 'data:image/svg+xml;base64,' . base64_encode(
                \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)
                    ->generate(url('/tracking/FIP-RNG-SAMPLE?qr=sample-preview'))
            );

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.approval-letter', compact(
                'booking', 'faculty', 'university', 'qrUrl'
            ));
            return $pdf->stream('PREVIEW_Surat_Izin.pdf');
        })->where('type', 'receipt|approval')->name('settings.pdf-preview');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Users\UserList::class)->name('index');
            Route::get('/create', \App\Livewire\Admin\Users\UserForm::class)->name('create');
            Route::get('/{user}/edit', \App\Livewire\Admin\Users\UserForm::class)->name('edit');
        });

    }); // end sysadmin middleware group

});
