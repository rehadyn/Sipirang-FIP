<div align="center">

# SIPIRANG
### Sistem Peminjaman Ruangan — Fakultas Ilmu Pendidikan, UNM

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-FB70A9?style=flat-square)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)

Platform peminjaman ruangan berbasis web untuk civitas akademika FIP UNM —
tanpa akun, tanpa kerumitan, dengan surat izin resmi ber-QR Code.

</div>

---

## Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Stack Teknologi](#-stack-teknologi)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi Cepat](#-instalasi-cepat)
- [Konfigurasi](#-konfigurasi)
- [Akun Default](#-akun-default)
- [Menjalankan Scheduler](#-menjalankan-scheduler)
- [Struktur Proyek](#-struktur-proyek)
- [Alur Penggunaan](#-alur-penggunaan)
- [Update & Deploy](#-update--deploy)
- [Troubleshooting](#-troubleshooting)
- [Panduan Best Practice](#-panduan-best-practice)

---

## ✨ Fitur Utama

### Untuk Peminjam (Mahasiswa / Dosen / Organisasi)
| Fitur | Keterangan |
|---|---|
| **Tanpa Akun** | Ajukan peminjaman langsung tanpa registrasi |
| **Live Status Ruangan** | Lihat ketersediaan slot Pagi / Siang / Fullday secara real-time |
| **Pencarian & Filter** | Cari ruangan berdasarkan nama, kode, atau gedung |
| **Multi-jadwal** | Satu pengajuan memuat banyak ruangan di berbagai tanggal |
| **PDF Tanda Terima** | Otomatis terunduh setelah konfirmasi booking |
| **Tracking Real-time** | Pantau status via nomor tiket + WhatsApp, atau scan QR |
| **Surat Izin Digital** | PDF resmi ber-QR Code diterbitkan otomatis saat disetujui |

### Untuk Admin / Pengelola
| Fitur | Keterangan |
|---|---|
| **Dashboard** | Ringkasan statistik, antrian review, alert SLA |
| **Review Inline** | Preview surat WD 2 langsung di halaman tanpa download |
| **Approve / Reject** | Satu klik dengan catatan; PDF surat izin + QR otomatis dibuat |
| **Kelola Ruangan** | Toggle aktif/nonaktif, blokir tanggal tertentu |
| **Laporan Excel** | Export laporan bulanan booking |
| **Pengaturan Sistem** | Konfigurasi nama institusi, deadline, maks slot (sysadmin only) |
| **Preview PDF Test** | Uji template PDF dengan data dummy dari halaman pengaturan |

### Keamanan & Teknis
- QR Code di surat izin terikat token unik per booking — tidak bisa dipalsukan
- Auto-expire booking: slot dilepas otomatis jika surat tidak diupload tepat waktu
- Scheduler `bookings:expire` berjalan setiap menit via Laravel Schedule
- Semua file dokumen disimpan di disk private (tidak bisa diakses langsung via URL)

---

## 🛠 Stack Teknologi

| Layer | Teknologi | Versi |
|---|---|---|
| Runtime | PHP | ≥ 8.3 |
| Framework | Laravel | 13.x |
| Reaktivitas UI | Livewire | 3.x |
| Admin Panel | Filament | 5.x |
| Frontend | Tailwind CSS + Alpine.js | 4.x / 3.x |
| Build Tool | Vite | latest |
| Database | SQLite *(default)* / MySQL / PostgreSQL | — |
| PDF | barryvdh/laravel-dompdf | ^3.1 |
| QR Code | simplesoftwareio/simple-qrcode | ^4.2 |
| Excel | phpoffice/phpspreadsheet | ^5.7 |

---

## 💻 Persyaratan Sistem

- **PHP** ≥ 8.3 dengan ekstensi: `BCMath` `Ctype` `cURL` `DOM` `Fileinfo` `GD` `JSON` `Mbstring` `OpenSSL` `PDO` `Tokenizer` `XML` `Zip` `SQLite3`
- **Composer** 2.x → [getcomposer.org](https://getcomposer.org)
- **Node.js** ≥ 20 LTS + npm
- **Git**
- *(Rekomendasi)* **Laravel Herd** untuk development di Windows/macOS

---

## 🚀 Instalasi Cepat

```bash
# 1. Clone
git clone https://github.com/rehadyn/Sipirang-FIP.git sipirang
cd sipirang

# 2. Dependency
composer install
npm install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database (SQLite — paling cepat)
# Windows:
New-Item -ItemType File -Path database/database.sqlite -Force
# Linux/macOS:
touch database/database.sqlite

# 5. Migrasi & seeder
php artisan migrate --seed

# 6. Storage link
php artisan storage:link

# 7. Build asset
npm run build

# 8. Jalankan (development)
php artisan serve
```

> Akses di [http://127.0.0.1:8000](http://127.0.0.1:8000)
> Admin panel di [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)

---

## ⚙️ Konfigurasi

### File `.env` — Wajib Disesuaikan

```env
APP_NAME=SIPIRANG
APP_ENV=production          # local saat development
APP_DEBUG=false             # true saat development
APP_URL=http://sipirang.test  # ⚠️ harus benar — dipakai untuk QR Code

APP_LOCALE=id
APP_TIMEZONE=Asia/Makassar
```

> **Peringatan `APP_URL`:** Nilai ini langsung masuk ke QR Code pada surat izin.
> Jika salah, QR Code yang discan tidak akan terbuka. Setelah ubah `.env`,
> jalankan `php artisan config:clear`.

### Pengaturan Sistem (via UI Admin)

Setelah login sebagai **sysadmin**, buka `Admin → Pengaturan`:

| Setting | Default | Keterangan |
|---|---|---|
| Nama Fakultas | — | Muncul di header semua PDF |
| Nama Universitas | — | Muncul di header semua PDF |
| No. Telepon Fakultas | — | Muncul di PDF & tombol WA admin |
| Email Fakultas | — | Muncul di header PDF |
| Batas Waktu Upload | `24` jam | Booking expired jika surat belum diupload |
| Maks. Slot per Booking | `5` | Maksimal ruangan×sesi per pengajuan |
| Maks. Hari ke Depan | `30` | Batas tanggal yang bisa dipesan |

---

## 👤 Akun Default

Seeder membuat dua akun siap pakai:

| Role | Email | Password | Akses |
|---|---|---|---|
| **Sysadmin** | `sysadmin@sipirang.local` | `sipirang123` | Semua fitur termasuk Pengaturan & Users |
| **Admin** | `admin@sipirang.local` | `sipirang123` | Kelola booking, ruangan, laporan |

> **Ganti password setelah login pertama** melalui menu profil.
>
> Login admin: `{APP_URL}/admin/login`

---

## ⏰ Menjalankan Scheduler

Scheduler **wajib aktif** agar booking yang melewati batas waktu upload otomatis dibatalkan dan slot ruangan dilepas kembali.

### Linux / Server Production
```bash
crontab -e
# Tambahkan:
* * * * * cd /path/ke/sipirang && php artisan schedule:run >> /dev/null 2>&1
```

### Laravel Herd (Windows / macOS)
Buka Herd → **Services** atau **Scheduled Tasks** → aktifkan scheduler untuk site `sipirang`.

### Windows Server (Task Scheduler)
```
Program : C:\path\php.exe
Argumen : artisan schedule:run
Start in: C:\path\sipirang
Interval: Setiap 1 menit
```

### Development / Testing
```bash
php artisan schedule:work   # berjalan terus di terminal
```

### Verifikasi
```bash
php artisan schedule:list
# Harus muncul: bookings:expire  *  *  *  *  *
```

---

## 📁 Struktur Proyek

```
sipirang/
├── app/
│   ├── Console/Commands/
│   │   └── ExpireBookings.php        # Auto-cancel booking kedaluwarsa
│   ├── Helpers/
│   │   └── SettingHelper.php         # Baca/tulis pengaturan sistem
│   ├── Http/Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── SysadminMiddleware.php
│   ├── Livewire/
│   │   ├── Admin/                    # Komponen panel admin
│   │   │   ├── Dashboard.php
│   │   │   ├── Settings.php          # Sysadmin only
│   │   │   ├── Bookings/
│   │   │   ├── Rooms/
│   │   │   ├── Buildings/
│   │   │   ├── Reports/
│   │   │   └── Users/
│   │   └── Guest/                    # Halaman publik
│   │       ├── LiveBoard.php         # Pilih ruangan real-time
│   │       ├── Checkout.php
│   │       ├── Tracking.php
│   │       └── Guide.php
│   ├── Models/
│   │   ├── Booking.php               # Status constants, deadline, QR token
│   │   ├── BookingItem.php           # Per-slot booking
│   │   ├── Room.php                  # Operating hours, blocked dates
│   │   ├── Building.php
│   │   ├── BlockedDate.php
│   │   └── Setting.php
│   └── Services/
│       ├── BookingService.php        # Create, approve, reject, expire
│       ├── PDFService.php            # Generate receipt & approval letter
│       └── FileStorageService.php
│
├── resources/views/
│   ├── layouts/
│   │   ├── guest.blade.php           # Layout halaman publik
│   │   └── admin.blade.php           # Layout panel admin (sidebar)
│   ├── livewire/
│   │   ├── guest/                    # View komponen guest
│   │   └── admin/                    # View komponen admin
│   └── pdfs/
│       ├── booking-receipt.blade.php # PDF Tanda Terima Booking
│       └── approval-letter.blade.php # PDF Surat Izin (2 halaman)
│
├── routes/
│   ├── web.php                       # Guest + Admin routes
│   └── console.php                   # Schedule: bookings:expire tiap menit
│
├── storage/app/
│   ├── bookings/                     # PDF receipt & surat izin (public disk)
│   └── uploads/                      # KTP & surat WD 2 (private disk)
│
├── PANDUAN_PEMINJAMAN.md             # SOP & best practice penggunaan
└── README.md
```

---

## 🔄 Alur Penggunaan

### Alur Peminjam (Singkat)

```
1. Pilih Ruangan  →  2. Checkout  →  3. Upload Surat WD 2  →  4. Tunggu Review  →  5. Unduh Surat Izin
```

### Status Booking

```
pending_upload  →  (upload surat)  →  pending_review
                                            ↓
                          approved  ←  [Admin review]  →  rejected
                              ↓
                     PDF Surat Izin + QR terbit otomatis
```

| Status | Artinya |
|---|---|
| `pending_upload` | Booking baru, menunggu upload surat WD 2 |
| `pending_review` | Surat sudah diupload, menunggu keputusan admin |
| `approved` | Disetujui — surat izin + QR tersedia |
| `rejected` | Ditolak — lihat alasan di halaman tracking |
| `expired` | Batas waktu upload terlewati, booking dibatalkan otomatis |

### Dokumen yang Dihasilkan

| Dokumen | Kapan Dibuat | Isi |
|---|---|---|
| **Tanda Terima Booking** | Saat booking dibuat | Nomor tiket, detail peminjam, jadwal ruangan, batas waktu upload |
| **Surat Izin Penggunaan Ruangan** | Saat admin approve | Detail booking, catatan admin, QR verifikasi + halaman syarat & sanksi |

---

## 🚢 Update & Deploy

```bash
# Tarik perubahan terbaru
git pull origin main

# Update dependency
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Jalankan migrasi jika ada
php artisan migrate --force

# Bersihkan cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Untuk production** — cache ulang setelah deploy:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 Troubleshooting

### QR Code di surat izin mengarah ke `localhost`
`APP_URL` di `.env` masih default. Perbaiki:
```bash
# Edit .env: APP_URL=https://domain-anda.ac.id
php artisan config:clear
```
Lalu approve ulang booking agar PDF baru ter-generate dengan URL yang benar.

### PDF tidak bisa dibuka / 404
File di-serve via route `/tracking/{tiket}/pdf/{type}` — tidak bergantung pada symlink.
Pastikan file ada di `storage/app/bookings/...` (cek file manager).

### Booking tidak expired otomatis
Scheduler tidak aktif. Cek: `php artisan schedule:list`
Lihat [Menjalankan Scheduler](#-menjalankan-scheduler) untuk setup.

### Pengaturan sistem tidak berpengaruh
Kemungkinan cache masih menyimpan nilai lama. Jalankan:
```bash
php artisan cache:clear
```

### Asset CSS/JS tidak tampil
```bash
npm install && npm run build
```

### Error permission `storage/` atau `bootstrap/cache/`
```bash
# Linux
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### SQLite "database is locked"
Terjadi saat banyak concurrent write. Untuk production dengan banyak pengguna, migrasi ke **MySQL** atau **PostgreSQL**.

---

## 📖 Panduan Best Practice

Lihat [PANDUAN_PEMINJAMAN.md](PANDUAN_PEMINJAMAN.md) untuk:
- Alur lengkap peminjam (6 langkah detail)
- Checklist harian admin
- Diagram alur sistem
- Tabel status & batas waktu
- Do & Don't untuk peminjam dan admin
- FAQ 9 pertanyaan umum

---

## 📄 Lisensi

Proyek internal — Fakultas Ilmu Pendidikan, Universitas Negeri Makassar.

<div align="center">

Made with ❤️ & ☕ by **[REHAD](https://edumc.id)**

*Clavis Ignoti Profundi Arcanorum*

</div>
