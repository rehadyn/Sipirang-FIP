<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\BookingService;
use App\Models\Room;
use App\Models\Booking;

// Provide fallback for helper `setting()` when running outside full HTTP bootstrap
if (! function_exists('setting')) {
    function setting($key = null, $default = null) {
        return $default;
    }
}

try {
    $svc = $app->make(BookingService::class);
    $room = Room::where('is_active', 1)->first();
    if (! $room) {
        echo "NO_ACTIVE_ROOM\n";
        exit(1);
    }

    $items = [[
        'room_id' => $room->id,
        'booking_date' => date('Y-m-d', strtotime('+1 day')),
        'start_time' => '09:00',
        'end_time' => '11:00',
        'notes' => 'integration test',
    ]];

    $data = [
        'borrower_name' => 'Automated Test',
        'borrower_id_number' => '0000',
        'borrower_type' => Booking::BORROWER_TYPES[0],
        'borrower_organization' => 'Test',
        'borrower_whatsapp' => '08123456789',
        'purpose' => 'Integration test',
        'items' => $items,
    ];

    $booking = $svc->createBooking($data);
    echo 'BOOKING_CREATED:' . $booking->id . '|' . $booking->ticket_number . "\n";
    foreach ($booking->items as $it) {
        echo 'ITEM:' . $it->id . '|room=' . $it->room_id . '|date=' . $it->booking_date . '|start=' . $it->start_time . '|end=' . $it->end_time . "\n";
    }
    exit(0);
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(2);
}
