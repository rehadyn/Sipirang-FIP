<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ReportController;
use App\Livewire\Guest\LiveBoard;
use App\Livewire\Guest\Checkout;
use App\Livewire\Guest\Tracking;
use App\Livewire\Guest\Guide;
use Illuminate\Support\Facades\Route;

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

    // Users — Sysadmin only
    Route::prefix('users')->name('users.')->middleware(['sysadmin'])->group(function () {
        Route::get('/', \App\Livewire\Admin\Users\UserList::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Users\UserForm::class)->name('create');
        Route::get('/{user}/edit', \App\Livewire\Admin\Users\UserForm::class)->name('edit');
    });
});
