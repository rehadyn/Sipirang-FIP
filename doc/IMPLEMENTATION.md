# SIPIRANG - Database & Backend Implementation

## Status Implementasi ✓

Dokumentasi ini merangkum implementasi **database, migrations, models, dan services** untuk SIPIRANG v1.0 (MVP).

---

## 📊 Database Schema

### Tabel yang Telah Dibuat (16 tabel)

#### **Tabel Inti (Core)**
1. **users** - Admin & Pimpinan (internal users)
2. **buildings** - Master data gedung
3. **rooms** - Master data ruangan
4. **room_facilities** - Master data fasilitas
5. **facility_room** - Pivot table (relasi many-to-many)
6. **room_photos** - Galeri foto ruangan
7. **room_operating_hours** - Jam operasional ruangan per hari
8. **blocked_dates** - Tanggal yang diblokir (libur, maintenance)

#### **Tabel Peminjaman (Booking)**
9. **bookings** - Data utama peminjaman (ticket, status, timeline)
10. **booking_items** - Detail jadwal per ruangan (cart items)
11. **booking_documents** - File-file terkait booking (KTP, surat, PDF)
12. **booking_templates** - Template peminjaman rutin

#### **Tabel Lanjutan**
13. **waitlists** - Antrian otomatis jika slot penuh
14. **settings** - Konfigurasi sistem yang dapat diubah admin
15. **notifications** - In-app notifications (Laravel standard)
16. **activity_log** - Audit trail (dari spatie/laravel-activitylog)

---

## 🔑 Key Features yang Diimplementasikan

### ✅ Multi-Booking (Cart System)
- User dapat memilih **lebih dari satu ruangan** dan **multiple time slots** dalam satu booking
- Disimpan dalam tabel `booking_items` (relasi 1:many dengan `bookings`)
- Semua jadwal dalam satu ticket number

### ✅ 24-Hour Deadline & Auto-Expire
- `bookings.deadline_at` dihitung otomatis: `created_at + 24 jam`
- Field `status` = `pending_upload` dengan countdown
- Method `BookingService::expireOverdueBookings()` untuk auto-expire (cron job)

### ✅ Dual PDF Documents
1. **Dokumen 1: Bukti Booking Sementara** (setelah submit form)
   - Field: `bookings.booking_pdf_path`
   - Generated via `PDFService::generateBookingReceipt()`

2. **Dokumen 2: Surat Izin Penggunaan** (setelah admin approve)
   - Field: `bookings.approval_pdf_path`
   - Generated via `PDFService::generateApprovalPDF()`
   - Includes QR code untuk validasi

### ✅ QR Code Validation
- Field: `bookings.qr_token` (UUID)
- Scan by on-site staff via API endpoint `/api/bookings/verify-qr/{token}`
- Records verification: `qr_verified_at`, `qr_verified_by`

### ✅ Real-Time Availability
- Conflict detection: `BookingItem::withConflict(...)` scope
- Composite index: `(room_id, booking_date, start_time, end_time)`
- Operating hours: `room_operating_hours` table
- Blocked dates: `blocked_dates` table

### ✅ Waitlist System
- `waitlists` table untuk antrian otomatis
- Status: `waiting`, `notified`, `converted`, `expired`
- Notification workflow saat slot kosong

### ✅ In-App Notifications
- Standard Laravel notifications table
- No FCM/push eksternal (sesuai spesifikasi)
- Notification center di frontend (read/unread)

---

## 📁 Struktur File Implementasi

```
app/
├── Models/
│   ├── User.php (✓ updated)
│   ├── Building.php (✓ new)
│   ├── Room.php (✓ new - with methods)
│   ├── RoomFacility.php (✓ new)
│   ├── RoomPhoto.php (✓ new)
│   ├── RoomOperatingHours.php (✓ new)
│   ├── BlockedDate.php (✓ new)
│   ├── Booking.php (✓ updated - with state machine)
│   ├── BookingItem.php (✓ new - conflict detection)
│   ├── BookingDocument.php (✓ new)
│   ├── Waitlist.php (✓ new)
│   ├── BookingTemplate.php (✓ new)
│   └── Setting.php (✓ new)
│
├── Services/
│   ├── BookingService.php (✓ new)
│   │   └── Methods: createBooking, checkConflict, isRoomAvailable,
│   │             approveBooking, rejectBooking, expireOverdueBookings,
│   │             recordQRVerification
│   ├── FileStorageService.php (✓ new)
│   │   └── Methods: storeKTP, storeApprovalLetter, storeRoomPhoto,
│   │             storePDF, getPublicUrl, getDownloadResponse
│   └── PDFService.php (✓ new)
│       └── Methods: generateBookingReceipt, generateApprovalPDF,
│                 generateQRCode, getPDFDownload
│
├── Helpers/
│   └── SettingHelper.php (✓ new)
│       └── Global function: setting($key, $default)
│
├── Exceptions/
│   └── BookingException.php (✓ new)
│
└── Providers/
    └── AppServiceProvider.php (needs SettingHelper registration)

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php (✓)
│   ├── 0001_01_01_000001_create_cache_table.php
│   ├── 0001_01_01_000002_create_jobs_table.php
│   ├── 2026_04_30_045519_create_buildings_table.php (✓)
│   ├── 2026_04_30_045519_create_rooms_table.php (✓)
│   ├── 2026_04_30_045520_create_bookings_table.php (✓ updated)
│   ├── 2026_04_30_045520_create_room_facilities_table.php (✓)
│   ├── 2026_04_30_045520_create_room_photos_table.php (✓)
│   ├── 2026_04_30_045521_create_booking_documents_table.php (✓ updated)
│   ├── 2026_04_30_045521_create_booking_items_table.php (✓ updated)
│   ├── 2026_04_30_045521_create_waitlists_table.php (✓ updated)
│   ├── 2026_04_30_045522_create_booking_templates_table.php (✓ updated)
│   ├── 2026_04_30_045522_create_settings_table.php (✓ updated)
│   ├── 2026_04_30_045522_create_notifications_table.php (✓)
│   ├── 2026_04_30_045533_create_facility_room_table.php (✓)
│   ├── 2026_04_30_045601_create_activity_log_table.php (✓)
│   ├── 2026_04_30_045650_create_room_operating_hours_table.php (✓ new)
│   └── 2026_04_30_045651_create_blocked_dates_table.php (✓ new)
│
└── seeders/
    ├── DatabaseSeeder.php (✓ updated - calls all seeders)
    ├── UserSeeder.php (✓ new)
    ├── BuildingSeeder.php (✓ new)
    ├── RoomFacilitySeeder.php (✓ new)
    ├── RoomSeeder.php (✓ new - with operating hours)
    └── SettingSeeder.php (✓ new)

config/
└── filesystems.php (✓ updated)
    ├── disk 'public' - untuk file public
    ├── disk 'bookings' - untuk PDF (public)
    └── disk 'uploads' - untuk KTP, surat (private)
```

---

## 🛠️ Cara Menggunakan

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Database
```bash
php artisan db:seed
```

**Data yang dibuat:**
- 3 users (1 admin, 1 pimpinan, 1 test admin)
- 3 buildings (Gedung A, B, C)
- 10 room facilities
- 5 rooms dengan operating hours
- System settings (24 jam deadline, max 5 items per booking, dll)

### 3. Model Relationships (Eloquent)

```php
// Get room dengan semua fasilitas
$room = Room::with('facilities', 'photos', 'bookingItems')->find($id);

// Check availability
$isAvailable = $room->isAvailable($date, $startTime, $endTime);

// Get all active bookings
$activeBookings = Booking::active()->get();

// Get overdue bookings
$overdueBookings = Booking::overdue()->get();
```

### 4. Booking Service Usage

```php
use App\Services\BookingService;

$service = new BookingService();

// Create booking
$booking = $service->createBooking([
    'borrower_name' => 'John Doe',
    'borrower_id_number' => '123456',
    'borrower_whatsapp' => '081234567890',
    'purpose' => 'Rapat tim',
    'items' => [
        [
            'room_id' => 1,
            'booking_date' => '2026-05-10',
            'start_time' => '09:00',
            'end_time' => '11:00',
        ]
    ]
]);

// Approve booking
$service->approveBooking($booking, auth()->id());

// Auto-expire overdue
$count = $service->expireOverdueBookings();
```

### 5. File Storage Usage

```php
use App\Services\FileStorageService;

$fileService = new FileStorageService();

// Store KTP
$path = $fileService->storeKTP($request->file('ktp'));

// Store PDF
$path = $fileService->storePDF($pdfContent, 'filename.pdf');

// Download file
return $fileService->getDownloadResponse('bookings', $path, 'bukti.pdf');
```

### 6. PDF Generation

```php
use App\Services\PDFService;

$pdfService = new PDFService();

// Generate receipt PDF
$receiptPath = $pdfService->generateBookingReceipt($booking);

// Generate approval PDF (after approval)
$approvalPath = $pdfService->generateApprovalPDF($booking);
```

### 7. Settings

```php
// Get setting
$deadlineHours = setting('booking.deadline_hours', 24);
$maxItems = setting('booking.max_items_per_cart', 5);

// Set setting
setting()->set('booking.deadline_hours', 48);
```

---

## 📋 State Machine: Booking Status

```
pending_upload (Waiting for approval letter upload)
    ↓ (upload surat)
pending_review (Waiting for admin approval)
    ↓ (admin review)
    ├→ approved (Diterima, dapat download PDF final)
    └→ rejected (Ditolak, items cancelled)

(Auto) expired (Melewati 24 jam tanpa upload)
(Manual) cancelled (Dibatalkan pengguna)
checked_in (QR sudah di-scan)
completed (Waktu peminjaman sudah terlewat)
```

---

## 🔍 Conflict Detection

Validasi dilakukan pada 3 level:

1. **Database Level:** Composite index `(room_id, booking_date, start_time, end_time)`
2. **Application Level:** `BookingItem::withConflict()` scope
3. **Locking:** Use `SELECT ... FOR UPDATE` saat checkout untuk prevent race condition

```php
// Scope example
$conflicts = BookingItem::withConflict(
    roomId: 1,
    date: '2026-05-10',
    startTime: '09:00',
    endTime: '11:00'
)->exists();
```

---

## 📦 Dependencies Yang Dibutuhkan

Tambahkan ke `composer.json`:

```json
{
    "require": {
        "barryvdh/laravel-dompdf": "^2.0",
        "spatie/laravel-activitylog": "^4.7",
        "spatie/laravel-permission": "^5.10"
    }
}
```

Install:
```bash
composer require barryvdh/laravel-dompdf spatie/laravel-activitylog spatie/laravel-permission
```

---

## 🎯 Next Steps

1. **Controllers & Routes**
   - Buat API routes untuk bookings, rooms, files
   - Implement validation rules (FormRequest)

2. **Frontend Views**
   - Booking form dengan multi-select
   - Calendar view untuk availability
   - Tracking page dengan countdown timer

3. **Admin Panel (Filament)**
   - Manage rooms, buildings, facilities
   - Approve/reject bookings
   - View audit logs

4. **Scheduled Jobs**
   - Auto-expire overdue bookings (Kernel schedule)
   - Send reminder notifications
   - Process waitlist offers

5. **PDF Templates**
   - Create views: `resources/views/pdfs/booking-receipt.blade.php`
   - Create views: `resources/views/pdfs/approval-letter.blade.php`

6. **QR Code Integration**
   - Generate QR codes di PDF
   - Create QR verification endpoint

---

## ✅ Checklist Development

- [x] Database migrations
- [x] Eloquent models dengan relasi
- [x] Model casts & constants
- [x] Database seeders
- [x] Filesystem configuration
- [x] Core services (Booking, File, PDF)
- [x] Helper functions
- [x] Custom exceptions
- [ ] API routes & controllers
- [ ] Frontend views & components
- [ ] Filament admin panel
- [ ] Scheduled jobs & queues
- [ ] PDF templates
- [ ] QR code generation
- [ ] Testing (unit & feature tests)
- [ ] API documentation

---

**Last Updated:** April 30, 2026
**Version:** 1.0 (Database & Core Services)
