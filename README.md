# SIPIRANG — Sistem Peminjaman Ruangan FIP UNM

Aplikasi web untuk pengelolaan peminjaman ruangan di Fakultas Ilmu Pendidikan, Universitas Negeri Makassar. Mahasiswa dan dosen dapat mengajukan peminjaman tanpa akun, mengunggah surat persetujuan WD 2, dan mengunduh surat izin resmi ber-QR Code setelah disetujui admin.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Stack Teknologi](#stack-teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Tambahan](#konfigurasi-tambahan)
- [Akun Default](#akun-default)
- [Menjalankan Scheduler (Auto-expire Booking)](#menjalankan-scheduler-auto-expire-booking)
- [Operasional Harian](#operasional-harian)
- [Update dari GitHub](#update-dari-github)
- [Troubleshooting](#troubleshooting)
- [Struktur Proyek](#struktur-proyek)

---

## Fitur Utama

- **Pengajuan tanpa akun** — Mahasiswa/dosen langsung pilih ruangan dan ajukan tanpa registrasi.
- **Multi-jadwal** — Satu pengajuan dapat memuat beberapa ruangan pada tanggal berbeda.
- **Live status ruangan** — Tampilan real-time slot Pagi/Siang/Fullday per tanggal.
- **Filter & pencarian** — Cari ruangan berdasarkan nama, kode, atau gedung.
- **Auto-expire** — Booking yang tidak meng-upload surat dalam 5 jam dibatalkan otomatis.
- **Dokumen digital** — Tanda terima dan surat izin resmi ber-QR Code di-generate otomatis dalam PDF.
- **QR aman** — QR di surat izin terikat token unik, tidak bisa dipalsukan untuk tiket lain.
- **Tracking real-time** — Peminjam pantau status melalui nomor tiket + WhatsApp atau scan QR.
- **Panel admin** — Dashboard, kelola booking, ruangan, gedung, laporan bulanan (Excel).

---

## Stack Teknologi

| Komponen | Versi |
|---|---|
| PHP | ≥ 8.3 |
| Laravel | 13.x |
| Livewire | 3.x |
| Filament | 5.x (admin panel) |
| Database | SQLite (default) atau MySQL/PostgreSQL |
| Frontend | Tailwind CSS 4, Alpine.js, Vite |
| PDF | barryvdh/laravel-dompdf |
| QR Code | simplesoftwareio/simple-qrcode |
| Excel | phpoffice/phpspreadsheet |

---

## Persyaratan Sistem

Sebelum instalasi, pastikan tersedia:

- **PHP** 8.3 atau lebih baru, dengan ekstensi: `BCMath`, `Ctype`, `cURL`, `DOM`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PCRE`, `PDO`, `Tokenizer`, `XML`, `GD`, `Zip`, `SQLite3` (atau driver database pilihan)
- **Composer** 2.x — [getcomposer.org](https://getcomposer.org/)
- **Node.js** 20 LTS atau lebih baru, dengan **npm**
- **Git**
- (Opsional) **Laravel Herd** — direkomendasikan untuk Windows/macOS karena sudah membundle PHP, Nginx, dan custom domain `*.test`

---

## Instalasi

### 1. Clone repository

```bash
git clone https://github.com/rehadyn/Sipirang-FIP.git sipirang
cd sipirang
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Salin file environment

```bash
# Linux / macOS
cp .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Atur konfigurasi `.env`

Buka file `.env`, sesuaikan minimal:

```env
APP_NAME=SIPIRANG
APP_ENV=production            # gunakan "local" saat development
APP_DEBUG=false               # gunakan "true" saat development
APP_URL=http://sipirang.test  # samakan dengan URL Anda akan akses

APP_LOCALE=id
APP_TIMEZONE=Asia/Makassar
```

> **Penting:** `APP_URL` harus sesuai dengan URL aktual karena dipakai untuk men-generate QR Code di surat izin. Jika salah, QR yang di-scan dari handphone tidak akan terbuka.

### 6. Siapkan database

**Pilihan A — SQLite (default, paling mudah):**

```bash
# Linux / macOS
touch database/database.sqlite

# Windows (PowerShell)
New-Item -ItemType File -Path database/database.sqlite -Force
```

`.env` sudah default `DB_CONNECTION=sqlite`, tidak perlu diubah.

**Pilihan B — MySQL/MariaDB:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipirang
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `sipirang` di MySQL terlebih dahulu sebelum lanjut.

### 7. Jalankan migrasi & seeder

```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat seluruh tabel
- Membuat akun **sysadmin** dan **admin** default
- Mengisi data awal: gedung, ruangan, fasilitas, dan setting sistem

### 8. Buat symbolic link untuk storage publik

```bash
php artisan storage:link
```

### 9. Install dependency frontend & build asset

```bash
npm install
npm run build
```

### 10. Jalankan aplikasi

**Development (built-in server):**

```bash
php artisan serve
```

Akses di `http://127.0.0.1:8000`.

**Laravel Herd:**

Pastikan domain `sipirang.test` sudah ditautkan ke folder proyek di Herd, lalu akses langsung di `http://sipirang.test`.

---

## Konfigurasi Tambahan

### Pengaturan Sistem

Setelah login admin, atur konfigurasi berikut di **Admin → Pengaturan**:

- **Nama Fakultas** dan **Universitas** (muncul di header PDF surat izin)
- **Nomor WhatsApp Admin** (untuk tombol "Hubungi Admin")
- **Deadline upload** (default 5 jam)

### Email & Notifikasi

Aplikasi tidak mengirim email/notifikasi keluar. Komunikasi dengan peminjam dilakukan via WhatsApp manual oleh admin.

---

## Akun Default

Setelah seeder dijalankan, dua akun ini tersedia:

| Role | Email | Password |
|---|---|---|
| **Sysadmin** | `sysadmin@sipirang.local` | `sipirang123` |
| **Admin** | `admin@sipirang.local` | `sipirang123` |

**Wajib ganti password setelah login pertama** melalui menu profil admin.

Login admin di: `<APP_URL>/admin/login`

---

## Menjalankan Scheduler (Auto-expire Booking)

Booking yang tidak diunggahi surat dalam 5 jam akan **otomatis dibatalkan** oleh scheduler. Scheduler ini wajib aktif agar slot ruangan tidak tertahan oleh peminjam yang menelantarkan pengajuannya.

Pilih salah satu cara di bawah sesuai lingkungan server:

### Server Linux (production)

Tambahkan ke crontab user yang menjalankan aplikasi:

```bash
crontab -e
```

Lalu tambahkan baris:

```cron
* * * * * cd /path/ke/sipirang && php artisan schedule:run >> /dev/null 2>&1
```

### Laravel Herd (Windows / macOS)

Buka aplikasi Herd → tab **Services / Scheduled Tasks** → enable scheduler untuk site `sipirang`.

### Windows Server (Task Scheduler)

Buat task baru yang berjalan setiap menit dengan action:

```
Program: C:\path\to\php.exe
Arguments: artisan schedule:run
Start in: C:\path\to\sipirang
```

### Development / testing manual

Jalankan di terminal terpisah (proses ini berjalan terus selama terminal terbuka):

```bash
php artisan schedule:work
```

### Verifikasi scheduler berjalan

```bash
php artisan schedule:list
```

Harus menampilkan task `bookings:expire` dengan jadwal `* * * * *` (setiap menit).

---

## Operasional Harian

### Cek booking aktif

`<APP_URL>/admin/bookings` — daftar semua pengajuan dengan filter status.

### Approve / reject booking

Buka detail booking → tombol **Setujui** atau **Tolak**. Saat disetujui, surat izin PDF + QR akan otomatis di-generate.

### Laporan bulanan

`<APP_URL>/admin/reports` — unduh laporan bulanan dalam format Excel.

### Mengelola ruangan & gedung

- Ruangan: `<APP_URL>/admin/rooms`
- Gedung: `<APP_URL>/admin/buildings`

Atur nama, kode, lantai, kapasitas, tipe, dan apakah ruangan **memerlukan upload KTP**.

### Memblokir tanggal tertentu

Untuk libur atau acara fakultas, admin dapat memblokir tanggal tertentu sehingga ruangan tidak bisa di-booking pada hari tersebut. Atur via halaman detail ruangan.

---

## Update dari GitHub

Saat ada perubahan baru:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
npm install
npm run build
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Setelah deploy production, jangan lupa cache ulang:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Troubleshooting

### QR Code di surat izin mengarah ke `localhost`

Penyebab: `APP_URL` di `.env` masih default. Solusi:

1. Edit `.env` → set `APP_URL` ke domain produksi (mis. `https://sipirang.fip.unm.ac.id`).
2. `php artisan config:clear`
3. Generate ulang surat izin yang sudah ada (approve ulang booking, atau jalankan tinker untuk regenerate).

### "404 Not Found" saat unduh PDF dari halaman tracking

PDF di-stream lewat route `/tracking/{tiket}/pdf/{type}`, bukan link langsung ke storage. Pastikan:

- `php artisan storage:link` sudah dijalankan.
- File ada di `storage/app/bookings/...` (cek lewat file manager).
- Booking memang sudah punya `booking_pdf_path` / `approval_pdf_path` di database.

### Booking tidak otomatis expired

Cek scheduler aktif: `php artisan schedule:list`. Jika tidak ada task `bookings:expire`, lihat bagian [Menjalankan Scheduler](#menjalankan-scheduler-auto-expire-booking).

### Asset CSS/JS tidak muncul setelah deploy

Asset belum di-build:

```bash
npm install
npm run build
```

Untuk development jalankan `npm run dev` di terminal terpisah.

### Error "permission denied" pada `storage/` atau `bootstrap/cache/`

Linux:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

(Sesuaikan user dengan owner web server Anda — `nginx`, `apache`, dll.)

### Database SQLite "database is locked"

Pastikan hanya satu proses menulis ke `database/database.sqlite`. Untuk produksi dengan banyak user bersamaan, sebaiknya migrasi ke MySQL/PostgreSQL.

---

## Struktur Proyek

```
sipirang/
├── app/
│   ├── Console/Commands/      # bookings:expire (auto-cancel)
│   ├── Http/Controllers/      # Controller admin
│   ├── Livewire/              # Komponen Livewire (guest & admin)
│   ├── Models/                # Booking, Room, Building, dll
│   └── Services/              # BookingService, PDFService, dll
├── database/
│   ├── migrations/
│   └── seeders/               # Data awal
├── resources/
│   └── views/
│       ├── livewire/          # View komponen Livewire
│       ├── pdfs/              # Template PDF (booking-receipt, approval-letter)
│       ├── home.blade.php     # Landing page
│       └── layouts/
├── routes/
│   ├── web.php                # Route guest & admin
│   └── console.php            # Schedule tasks
└── storage/app/
    ├── bookings/              # PDF tanda terima & surat izin
    └── uploads/                # KTP & surat WD 2 yang di-upload
```

---

## Lisensi

Proyek internal Fakultas Ilmu Pendidikan, Universitas Negeri Makassar.
Dikembangkan oleh [REHAD](https://edumc.id).
