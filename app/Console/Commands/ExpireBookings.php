<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('bookings:expire')]
#[Description('Otomatis membatalkan booking yang melewati batas waktu 5 jam tanpa upload.')]
class ExpireBookings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(\App\Services\BookingService $bookingService)
    {
        $count = $bookingService->expireOverdueBookings();
        $this->info("Berhasil membatalkan {$count} booking yang kedaluwarsa.");
    }
}
