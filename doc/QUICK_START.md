# SIPIRANG - Quick Start Guide

## 🚀 Setup Cepat

### 1. Persiapan Database
```bash
# Jalankan migrations
php artisan migrate

# Seed data test
php artisan db:seed

# Verify
php artisan tinker
> User::count()  // Should return 3
> Room::count()  // Should return 5
> Setting::count() // Should return ~11
```

### 2. Instalasi Dependencies
```bash
composer require barryvdh/laravel-dompdf spatie/laravel-activitylog
```

### 3. Daftar Helper di AppServiceProvider
Edit `app/Providers/AppServiceProvider.php`:

```php
public function boot(): void
{
    require_once app_path('Helpers/SettingHelper.php');
}
````

---

## 💻 Common Development Tasks

### Membuat Booking Baru
```php
use App\Services\BookingService;

$service = new BookingService();

$booking = $service->createBooking([
    'borrower_name' => 'Mahasiswa Test',
    'borrower_id_number' => '1234567',
    'borrower_whatsapp' => '081234567890',
    'purpose' => 'Rapat BEM',
    'borrower_type' => 'mahasiswa',
    'items' => [
        [
            'room_id' => 1,
            'booking_date' => '2026-05-20',
            'start_time' => '09:00:00',
            'end_time' => '11:00:00',
        ],
        [
            'room_id' => 2,
            'booking_date' => '2026-05-21',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
        ]
    ]
]);

echo $booking->ticket_number; // UUID format
```

### Upload Approval Letter
```php
use App\Services\FileStorageService;
use App\Services\BookingService;

$fileService = new FileStorageService();
$bookingService = new BookingService();

$path = $fileService->storeApprovalLetter($request->file('approval_letter'));
$bookingService->markAsUploadedApprovalLetter($booking, $path);
```

### Approve Booking (Admin)
```php
use App\Services\BookingService;
use App\Services\PDFService;

$service = new BookingService();
$pdfService = new PDFService();

// Approve
$service->approveBooking($booking, auth()->id(), 'Setuju untuk diteruskan');

// Generate PDF
$approvalPath = $pdfService->generateApprovalPDF($booking);
$booking->update(['approval_pdf_path' => $approvalPath]);
```

### Check Room Availability
```php
use App\Models\Room;

$room = Room::find(1);

// Method 1: Direct method
$isAvailable = $room->isAvailable('2026-05-20', '09:00', '11:00');

// Method 2: Service
$service = new \App\Services\BookingService();
$conflicts = $service->checkConflict(1, '2026-05-20', '09:00', '11:00');
```

### List Available Rooms for Date
```php
use App\Models\Room;
use Carbon\Carbon;

$date = Carbon::parse('2026-05-20');
$startTime = '09:00';
$endTime = '11:00';

$availableRooms = Room::active()
    ->where('is_active', true)
    ->get()
    ->filter(function ($room) use ($date, $startTime, $endTime) {
        return $room->isAvailable($date, $startTime, $endTime);
    });
```

### Get Booking Details
```php
use App\Models\Booking;

$booking = Booking::with('items.room', 'items.room.facilities', 'documents')
    ->find($id);

// Get all items
foreach ($booking->items as $item) {
    echo $item->room->name . ': ' . $item->time_range; // "Ruang A: 09:00 - 11:00"
}

// Check status
if ($booking->is_approved) {
    // Show download button for PDF
}

// Get time remaining (countdown)
echo $booking->time_remaining; // seconds remaining
```

---

## 🧪 Testing Scenarios

### Scenario 1: Multi-Booking
```php
// User selects 3 time slots in booking form:
// - Room A, May 20, 09:00-11:00
// - Room B, May 20, 14:00-16:00
// - Room C, May 21, 10:00-12:00

// Semua simpan dalam 1 booking dengan 1 ticket number
$booking->items()->count(); // 3
$booking->ticket_number; // Same for all
```

### Scenario 2: Conflict Detection
```php
// Booking 1: Room A, May 20, 09:00-11:00 ✓ Approved
// Booking 2: Room A, May 20, 10:00-12:00 ✗ Conflict!

$service->checkConflict(1, '2026-05-20', '10:00', '12:00'); // true
```

### Scenario 3: Auto-Expire
```php
// Created at: 2026-05-20 10:00
// Deadline: 2026-05-21 10:00
// Current: 2026-05-21 11:00 (1 hour overdue)
// Status: pending_upload, no upload yet

// Run cron job:
php artisan schedule:run

$booking->refresh();
$booking->status; // 'expired'
$booking->cancellation_type; // 'auto_expired'
```

### Scenario 4: QR Verification
```php
// After approval, booking has QR code in PDF
// On-site staff scans QR via mobile/app:

// Find booking by QR token
$booking = Booking::where('qr_token', $token)->firstOrFail();

// Record verification
$service->recordQRVerification($booking, 'Petugas Gedung A');

$booking->status; // 'checked_in'
$booking->qr_verified_at; // 2026-05-20 14:30:00
$booking->qr_verified_by; // 'Petugas Gedung A'
```

---

## 📱 Model Scopes & Methods

### Booking Scopes
```php
Booking::pendingUpload()->count();      // pending upload
Booking::pendingReview()->count();      // pending admin review
Booking::approved()->count();           // approved
Booking::active()->count();             // pending_upload | pending_review | approved
Booking::overdue()->count();            // status='pending_upload' AND deadline < now
```

### Booking Methods & Accessors
```php
$booking->is_expired;       // Boolean
$booking->is_approved;      // Boolean
$booking->can_upload;       // Boolean (status pending + deadline not passed)
$booking->time_remaining;   // Integer (seconds)
```

### Room Methods
```php
$room->isAvailable($date, $start, $end);        // Boolean
$room->isWithinOperatingHours($date, $s, $e);   // Boolean
$room->hasConflict($date, $start, $end);        // Boolean
$room->getCapacityLabel();                      // String
```

### BookingItem Scopes
```php
BookingItem::active()->count();
BookingItem::forDate('2026-05-20')->count();
BookingItem::forRoom(1)->count();
BookingItem::withConflict(1, '2026-05-20', '09:00', '11:00')->count();
```

---

## 🗄️ Database Queries

### Find Rooms by Building
```php
$rooms = Room::where('building_id', $buildingId)
    ->with('facilities', 'photos')
    ->active()
    ->ordered()
    ->get();
```

### Get Booking with All Relations
```php
$booking = Booking::with([
    'items' => fn($q) => $q->with('room.building', 'room.facilities'),
    'documents',
    'reviewer'
])->findOrFail($id);
```

### Count Active Bookings per Room
```php
$counts = Booking::approved()
    ->with('items')
    ->get()
    ->groupBy('items.0.room_id')
    ->map->count();
```

---

## 🔧 Configuration

### Default Settings
```php
// Cek di database seeders
setting('booking.deadline_hours');          // 24
setting('booking.max_items_per_cart');      // 5
setting('booking.max_days_advance');        // 30
setting('general.faculty_name');            // Fakultas Ilmu Pendidikan
setting('general.university_name');         // Universitas Negeri Makassar
```

### Ubah Settings (Admin)
```php
use App\Models\Setting;

Setting::where('key', 'booking.deadline_hours')
    ->first()
    ->update(['value' => '48']);

// Or using helper
setting()->set('booking.deadline_hours', 48);
```

---

## 📝 Checklist Sebelum Deployment

- [ ] Database seeded dengan data test (building, room, facility, user)
- [ ] Storage folders exist: `storage/app/bookings`, `storage/app/uploads`
- [ ] SettingHelper registered di AppServiceProvider
- [ ] PDF templates created: `resources/views/pdfs/`
- [ ] Routes for booking API created
- [ ] Controllers for admin CRUD created
- [ ] Filament admin panel configured
- [ ] Schedule job untuk auto-expire dibuat
- [ ] Queue worker untuk notification dibuat
- [ ] Testing done (conflict detection, deadline, etc)
- [ ] .env configured dengan FILESYSTEM_DISK

---

## 🆘 Troubleshooting

### "Model not found" Error
**Cause:** Model file tidak di-register di composer.json autoload
**Solution:**
```bash
composer dump-autoload
```

### File Upload Failed
**Cause:** Storage folder tidak exist
**Solution:**
```bash
mkdir -p storage/app/uploads/ktp
mkdir -p storage/app/uploads/approval-letters
mkdir -p storage/app/bookings
php artisan storage:link
```

### Setting Not Working
**Cause:** SettingHelper tidak di-load
**Solution:**
- Pastikan helper di-require di AppServiceProvider
- Clear cache: `php artisan cache:clear`

### PDF Generation Failed
**Cause:** DomPDF not installed
**Solution:**
```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

**Last Updated:** April 30, 2026
