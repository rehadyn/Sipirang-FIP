# SIPIRANG: Sistem Peminjaman Ruangan FIP UNM

**SIPIRANG** adalah platform digital untuk mengelola peminjaman ruangan di Fakultas Ilmu Pendidikan, Universitas Negeri Makassar secara transparan dan efisien.

## 🚀 Fitur Utama
- **Dashboard Mahasiswa & Dosen**: Melihat status peminjaman dan riwayat.
- **Manajemen Ruangan**: Katalog ruangan dengan info fasilitas dan foto.
- **Kalender Ketersediaan**: Cek jadwal kosong secara real-time.
- **Upload Berkas**: Unggah surat permohonan atau berkas persetujuan.
- **E-Receipt (PDF)**: Generate bukti peminjaman otomatis setelah di-acc oleh admin.
- **QR Code Validation**: Scan QR pada bukti PDF untuk verifikasi keaslian oleh petugas gedung.

## 👥 Hak Akses (Roles)
1. **Mahasiswa/Dosen (Peminjam)**:
   - Mencari ruangan tersedia.
   - Mengajukan peminjaman & upload berkas.
   - Mendownload bukti PDF.
2. **Admin Fakultas**:
   - Verifikasi berkas & approval.
   - Kelola data ruangan & fasilitas.
   - Monitoring laporan peminjaman.
3. **Pimpinan/Dekanat**:
   - Melihat laporan statistik penggunaan ruangan.

## 🛠️ Tech Stack
- **Framework**: Laravel 11
- **Admin UI**: FilamentPHP (TALL Stack: Tailwind, Alpine.js, Laravel, Livewire)
- **PDF Engine**: Laravel DomPDF / Browsershot
- **Database**: MySQL / PostgreSQL

## � Jenis Dokumen yang Dihasilkan Sistem (System-Generated PDF)

Sepanjang alur peminjaman, sistem SIPIRANG bertugas untuk me-generate (menghasilkan) dua jenis berkas utama bagi pengguna:

1. **Dokumen 1: Bukti Booking Sementara (Generated Sebelum ACC Murni)**
   - **Waktu Didapatkan:** Keluar otomatis sesaat setelah mahasiswa berhasil submit form peminjaman pertama kali.
   - **Fungsi:** Sebagai bukti *booking* sementara yang berisi Nomor Tiket (pengingat). *(Catatan: Lembar persetujuan/surat permohonan ke pimpinan dibuat sendiri oleh mahasiswa secara mandiri di luar sistem)*.
   - **Isi Dokumen:** Data detail pengaju (Nama, NIM, dll), detail rencana peminjaman ruangan, Nomor Tiket, dan informasi batas waktu (1x24 jam) untuk meng-upload surat persetujuan WD 2.

2. **Dokumen 2: Berkas ACC Peminjaman / Surat Izin Penggunaan (Generated Setelah ACC Admin/Final)**
   - **Waktu Didapatkan:** Keluar otomatis setelah admin menyetujui (ACC) peminjaman di sistem (pasca-upload ulang form tanda tangan WD 2).
   - **Fungsi:** Sebagai tiket final (E-Receipt) yang ditunjukkan ke petugas gedung, tanda bahwa ruangan SAH digunakan.
   - **Isi Dokumen Wajib:**
     - **Informasi Ruangan:** Nama/Kode ruang & gedung.
     - **Waktu Pemakaian:** Rentang waktu (Tanggal, Dari Jam X sampai Jam Y).
     - **Identitas Approver:** Nama dan jabatan pihak/petugas yang meng-ACC di sistem.
     - **Catatan & Aturan:** Himbauan penggunaan ruang (misal dilarang merokok, menjaga kebersihan, dll).
     - **QR Code:** Kode akses untuk validasi oleh petugas lapangan.

---

## �🔄 Alur Peminjaman (Flow)
1. **Pilih Ruangan & Waktu (Multi-booking)**: User memilih ruangan dan waktu melalui kalender. *Support multi-slot*: User dapat memilih **lebih dari satu ruangan** atau **satu ruangan untuk beberapa hari/waktu yang berbeda** dalam satu kali sesi peminjaman (menggunakan sistem mirip "keranjang/cart").
2. **Isi Form (Satu Pintu)**: Setelah seluruh jadwal dipilih, user mengisi form keperluan, mengunggah berkas syarat (apabila ada), serta **wajib mengunggah KTP** jika salah satu ruangan di keranjang membutuhkan verifikasi identitas tambahan. Semua jadwal ini akan digabung ke dalam 1 (satu) Nomor Tiket/Booking ID.
3. **Download Berkas Bukti Booking**: Setelah form berhasil di-submit, sistem menghasilkan **Berkas Bukti Booking Sementara** (Dokumen 1) secara otomatis beserta Nomor Tiket/Booking ID.
4. **Tanda Tangan WD 2 & Upload Surat (Time Limit)**:
   - User **membuat sendiri** surat permohonan/persetujuan secara mandiri dan meminta tanda tangan Wakil Dekan 2 (WD 2).
   - User diberikan batas waktu maksimal **1 hari (24 Jam)** untuk mengunggah (*upload*) surat yang sudah ditandatangani tersebut menggunakan Nomor Tiket pada menu "Cek Status".
   - *Sistem Auto-Cancel*: Jika melewati 1 hari tanpa mengunggah surat tersebut, peminjaman akan otomatis dibatalkan, dan ruangan berstatus *tersedia* kembali di kalender.
5. **Review Admin**: Admin memverifikasi surat persetujuan (yang memuat tanda tangan WD 2) yang diunggah mahasiswa.
6. **Keputusan**: 
   - Jika **Ditolak**: Status dibatalkan, ruangan kembali kosong.
   - Jika **Diterima**: Status berubah menjadi 'Approved'.
7. **Cetak Berkas ACC Ruangan**: User mendownload **Berkas ACC Peminjaman / Surat Izin Penggunaan** (Dokumen 2) yang berisi detail waktu, informasi penyetuju (approver), catatan, dan QR Code.
8. **Selesai**: User menunjukkan PDF final (Dokumen 2) ke petugas gedung saat hari-H.

## ⏰ Sesi Waktu Peminjaman Ruangan

SIPIRANG menggunakan **3 sesi waktu tetap** yang telah ditetapkan untuk semua peminjaman ruangan:

| Sesi | Waktu | Durasi | Deskripsi |
|---|---|---|---|
| **Pagi** | 07:00 – 12:00 | 5 jam | Sesi pagi untuk kegiatan pukul 7 pagi hingga siang hari |
| **Siang** | 13:00 – 17:00 | 4 jam | Sesi siang untuk kegiatan setelah istirahat siang hingga sore |
| **Fullday** | 07:00 – 17:00 | 10 jam | Sesi seharian penuh untuk kegiatan sepanjang hari |

**Catatan Implementasi:**
- User **hanya dapat memilih salah satu sesi** per ruangan per hari (tidak ada kombinasi pagi + siang).
- Sistem akan **otomatis mendeteksi konflik** jika ruangan yang sama dipilih dengan sesi yang sama pada tanggal yang sama.
- Untuk ruangan yang digunakan lebih dari satu hari dengan sesi berbeda, user dapat menambahkan ke keranjang secara terpisah (misal: Ruang A Pagi 1 Mei + Ruang A Siang 2 Mei = 2 item cart).

---
*Dibuat untuk pengembangan sistem informasi FIP UNM.*

## Fitur Lanjutan (Advanced)

Untuk membuat SIPIRANG lebih seamless dan ramah mahasiswa, tambahkan fitur-fitur advanced berikut (prioritas diurutkan untuk MVP mahasiswa):

- **MVP Prioritas (Mahasiswa):**
   - **PWA / Mobile-first UI:** Aplikasi Progressive Web App agar mahasiswa dapat memasang SIPIRANG di ponsel, offline caching, dan akses cepat.
   - **In-app Notifications Only (Laravel Notifications + WebSockets):** Notifikasi real-time hanya melalui aplikasi (in-app notification center) untuk status approval, pengingat hari-H, dan perubahan jadwal. Tidak menggunakan FCM, push eksternal, atau email otomatis. Gunakan WebSockets untuk live update dan job scheduler (cron/queues) untuk membuat pengingat sebagai notifikasi in-app.
   - **One-Click E-Receipt PDF dengan QR Code:** Generate PDF bukti otomatis (Browsershot/DomPDF) dan sertakan QR untuk verifikasi cepat.
   - **Integrasi Kalender (iCal / Google Calendar):** Sinkronisasi jadwal peminjaman ke kalender pribadi mahasiswa.
   - **Smart Search & Auto-suggestion:** Pencarian cepat ruangan + autosuggest berdasarkan riwayat pengguna dan preferensi waktu.

- **Fitur Real-time & Otomasi:**
   - **Ketersediaan Real-time (WebSocket / Laravel WebSockets):** Update kalender dan slot kosong secara real-time tanpa refresh.
   - **Waitlist & Auto-fill:** Jika ada pembatalan, sistem otomatis menawarkan slot ke pengguna di waitlist.
   - **Conflict Detection & Smart Scheduling (AI-assisted):** Saran jadwal alternatif otomatis saat terjadi konflik; gunakan rule-based + ML ringan.

- **Verifikasi & Keamanan:**
   - **QR Check-in On-site:** Scan QR bukti untuk check-in dan validasi kehadiran pada hari-H.
   - **Audit Log & History:** Catatan lengkap perubahan booking, approval, dan file yang diunggah.

- **Pengalaman Pengguna & Aksesibilitas:**
   - **Form Pintas & Booking Template:** Simpan template peminjaman (kegiatan rutin) untuk pengisian cepat.
   - **Multibahasa & A11y:** Dukungan bahasa dan perbaikan aksesibilitas (WCAG) untuk memudahkan semua mahasiswa.
   - **Smart File Validation (OCR):** Validasi surat/berkas via OCR (opsional) untuk mengekstrak metadata.

- **Analytics & Admin Tools:**
   - **Dashboard Pemakaian & Laporan Kustom:** Statistik penggunaan ruangan, peak times, dan laporan mendownload PDF.
   - **Two-step Approval Workflow:** Opsional: admin + pimpinan untuk approval acara besar.

## Rekomendasi Teknis Singkat

- Frontend: `Tailwind CSS`, `Alpine.js`, `Livewire` (sesuai stack Filament).
- Real-time: `beyondcode/laravel-websockets` atau `Pusher`.
- Notifikasi: `Laravel Notifications` + WebSockets (in-app only). Gunakan `queues`/`scheduler` untuk reminders.
- PDF: `Browsershot` untuk layout konsisten, fallback `DomPDF`.
- OCR / AI: `Tesseract` untuk OCR lokal atau Google Vision API; saran jadwal dapat menggunakan model embedding sederhana / heuristics.

## Langkah Implementasi Awal (MVP)

1. Implement PWA + responsive calendar view.
2. Tambah one-click PDF + QR generation untuk tiap booking.
3. Notifikasi in-app (Laravel Notifications + WebSockets) untuk status booking dan pengingat; sertakan notification center (read/unread) dan scheduled reminders via queues/cron.
4. Integrasi iCal export / Google Calendar sync.
5. WebSocket realtime untuk kalender dan waitlist.

Jika setuju, saya bisa mulai membuat wireframe PWA dan membuat tugas implementasi terperinci untuk langkah-langkah MVP.

## 🎨 Arsitektur UI & UX Menyeluruh (Guest Flow)

Mempertimbangkan alur tanpa login (*Guest Mode*), fitur *multi-booking*, dan batas waktu 24 jam, berikut adalah rancangan UI/UX yang dioptimalkan untuk meminimalkan kebingungan mahasiswa dan mempercepat proses secara keseluruhan.

### 1. Prinsip Desain Utama
- **Frictionless (Minim Hambatan):** Tidak ada keharusan membuat akun. Semua fitur utama (Live Board, Form, Tracker) langsung dapat diakses.
- **Visual State Awareness:** Menggunakan warna untuk melambangkan *status* (Hijau = Kosong, Merah = Terpakai) agar ketersediaan ruangan langsung dipahami alam bawah sadar dalam 1 detik.
- **Mobile-First & PWA-Ready:** Karena mahasiswa mayoritas mengakses via HP, navigasi menggunakan pola *Bottom Sheet* (panel yang muncul dari bawah), *Floating Action Button*, dan *Card* responsif.
- **Modern & Clean:** Mengabungkan ruang putih (*whitespace*), tipografi sans-serif modern, dan *icon set* minimalis (seperti Heroicons/Phosphor) tanpa menggunakan karakter emoji pada antarmuka.

### 2. Rencana Halaman 1: Beranda & Live Board
- **Header:** Logo Sipirang & Tombol dengan Ikon Pencarian **"Cek Tiket / Status"** yang mencolok.
- **Live Status Grid (Hero Section):** 
  - Tampilan visual kotak-kotak (grid) daftar ruangan fakultas (mirip UI pemilihan kursi).
  - **Warna Real-time:** Menyesuaikan jam saat ini. Hijau jika kosong, Merah jika sedang terpakai (disertai label "Digunakan s/d 14:00").
- **Filter Waktu Interaktif:**
  - *Date/Time Picker* di atas grid. Jika pengguna mengubah filter ke hari esok, warna *grid* otomatis menyesuaikan tanpa perlu proses *loading/refresh* halaman (didukung oleh Livewire).
- **Proses Masuk Keranjang:**
  - Jika kotak ruangan berwana Hijau ditekan, muncul *Bottom Sheet* berisi foto, fasilitas (ikon AC, dsb), dan tombol **"+ Tambah ke Peminjaman"**.
- **Floating Cart (Keranjang Ruangan):** Ikon keranjang melayang di sudut layar bawah, menunjukkan angka jadwal terpilih (misal: "2 Jadwal"). Mengklik ikon ini akan membuka laci untuk melihat ringkasan pesanan dan lanjut ke *Checkout*.

### 3. Rencana Halaman 2: Formulir Checkout Peminjaman
- **Sistem Satu Pintu:** Seluruh proses pengisian identitas untuk semua jadwal dalam keranjang dilakukan di satu halaman (*single-page form*).
- **Order Summary:** Bagian atas menampilkan daftar ruangan & jam yang dipilih, lengkap dengan ikon "Hapus" (tong sampah) jika ada jadwal yang batal dipesan.
- **Dynamic Field System:**
  - *Field* dasar: Nama Identitas, NIM, Organisasi/Keperluan, No. WA aktif.
  - *Field* dinamis: Kolom **Upload KTP** otomatis hanya akan muncul (dan menjadi wajib) apabila di dalam keranjang terdapat ruangan khusus yang telah diatur oleh sistem Admin sebagai "Wajib KTP".
- **Sukses & Hand-off:** Layar sukses akan memunculkan Nomor Tiket secara besar, tombol "Salin Nomor", instruksi tenggat waktu, dan tombol **"Unduh Bukti Booking Sementara"**.

### 4. Rencana Halaman 3: Pusat Pelacakan & Masa Tenggang (Tracking Page)
- **Akses:** Dari tombol "Cek Tiket" di header Beranda, menggunakan Nomor Tiket (Booking ID).
- **Visual Progress Bar (Stepper UI):** Menampilkan status visual yang transparan, misalnya:
  1. *Booking Masuk* (Centang)
  2. *Upload Surat Mandiri* (Sedang Aktif)
  3. *Verifikasi Admin* (Menunggu)
  4. *Disetujui / Selesai*
- **Urgency Indicator (UI Hitung Mundur):** Pada tahap "Upload Surat", disediakan *Countdown Timer* besar (misal: **23:45:10 Tersisa**). Angka akan berubah menjadi warna merah peringatan jika waktu tersisa kurang dari 3 jam, mendorong mahasiswa untuk segera mengunggah surat WD 2.
- **Upload & Feedback Area:** Kotak khusus bertipe *Drag-and-Drop* untuk mengunggah PDF surat. Setelah diunggah, otomatis mengubah stepper ke tahap Verifikasi Admin.
- **Final Action:** Jika status Stepper menjadi "Selesai/Disetujui", layar akan memunculkan ilustrasi/ikon sukses dan tombol besar untuk mendownload **Berkas ACC Peminjaman (PDF Final ber-QR)**.

---

## 🗄️ Rencana Database (Database Schema Plan)

Skema database dirancang untuk Laravel 11 + FilamentPHP, menggunakan konvensi Laravel (snake_case, timestamps, soft deletes). Mendukung fitur inti (guest flow, multi-booking/cart, countdown 24 jam, dual-PDF, QR validation) dan fitur lanjutan (waitlist, audit log, notifikasi in-app, template booking).

### Konvensi Umum
- Primary key: `id` (BIGINT UNSIGNED, auto-increment).
- Semua tabel memiliki `created_at` dan `updated_at` (Laravel timestamps).
- Tabel yang memerlukan penghapusan lunak menggunakan `deleted_at` (SoftDeletes).
- UUID digunakan untuk Nomor Tiket (`ticket_number`) dan QR token agar tidak mudah ditebak.
- Enum disimpan sebagai `VARCHAR` dengan validasi di level aplikasi (Laravel Enum casting).

---

### Tabel 1: `users`

Pengguna internal sistem (Admin Fakultas, Pimpinan/Dekanat). Peminjam (mahasiswa/dosen) **tidak wajib punya akun** karena menggunakan Guest Flow.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | Auto-increment |
| `name` | VARCHAR(255) | Nama lengkap |
| `email` | VARCHAR(255) UNIQUE | Email login |
| `email_verified_at` | TIMESTAMP NULL | Verifikasi email |
| `password` | VARCHAR(255) | Hashed password |
| `role` | VARCHAR(30) | `admin`, `pimpinan` |
| `avatar_path` | VARCHAR(500) NULL | Foto profil |
| `is_active` | BOOLEAN DEFAULT true | Status aktif |
| `remember_token` | VARCHAR(100) NULL | Laravel default |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP NULL | Soft delete |

**Index:** `email` (unique), `role`.

---

### Tabel 2: `buildings`

Master data gedung di lingkungan FIP UNM.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `name` | VARCHAR(255) | Nama gedung (Gedung A, B, dll) |
| `code` | VARCHAR(20) UNIQUE | Kode gedung |
| `address` | TEXT NULL | Alamat/lokasi spesifik |
| `floor_count` | TINYINT UNSIGNED DEFAULT 1 | Jumlah lantai |
| `description` | TEXT NULL | Deskripsi umum |
| `is_active` | BOOLEAN DEFAULT true | |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

---

### Tabel 3: `rooms`

Master data ruangan, termasuk konfigurasi apakah memerlukan KTP.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `building_id` | BIGINT FK → buildings | Relasi ke gedung |
| `name` | VARCHAR(255) | Nama ruangan (Aula Lt.3, Lab Micro, dll) |
| `code` | VARCHAR(30) UNIQUE | Kode ruangan (FIP-A-301) |
| `floor` | TINYINT UNSIGNED DEFAULT 1 | Lantai ke berapa |
| `capacity` | SMALLINT UNSIGNED NULL | Kapasitas orang |
| `room_type` | VARCHAR(50) | `aula`, `kelas`, `lab`, `rapat`, `seminar`, `lainnya` |
| `requires_ktp` | BOOLEAN DEFAULT false | Wajib upload KTP? (Dynamic Field trigger) |
| `description` | TEXT NULL | Deskripsi/catatan ruangan |
| `rules` | TEXT NULL | Aturan khusus ruangan (muncul di PDF final) |
| `is_active` | BOOLEAN DEFAULT true | Ruangan bisa dipinjam? |
| `sort_order` | SMALLINT DEFAULT 0 | Urutan tampil di Live Board |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP NULL | |

**Index:** `building_id`, `room_type`, `is_active`.

---

### Tabel 4: `room_facilities`

Fasilitas per ruangan (AC, proyektor, mic, dll). Relasi many-to-many via pivot.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `name` | VARCHAR(100) | Nama fasilitas (AC, Proyektor, Sound System) |
| `icon` | VARCHAR(100) NULL | Nama ikon (Heroicons/Phosphor identifier) |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

---

### Tabel 5: `facility_room` (Pivot)

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `room_id` | BIGINT FK → rooms | |
| `facility_id` | BIGINT FK → room_facilities | |
| `quantity` | TINYINT DEFAULT 1 | Jumlah unit fasilitas |
| `notes` | VARCHAR(255) NULL | Catatan (misal: "AC 2 PK") |

**Index:** `room_id, facility_id` (unique composite).

---

### Tabel 6: `room_photos`

Galeri foto per ruangan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `room_id` | BIGINT FK → rooms | |
| `file_path` | VARCHAR(500) | Path di storage |
| `caption` | VARCHAR(255) NULL | |
| `sort_order` | TINYINT DEFAULT 0 | Urutan tampil |
| `created_at` | TIMESTAMP | |

---

### Tabel 7: `bookings`

Tabel utama peminjaman. Satu booking = satu Nomor Tiket, bisa memiliki banyak jadwal (multi-booking via `booking_items`).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `ticket_number` | CHAR(36) UNIQUE | UUID sebagai Nomor Tiket (ditampilkan ke user) |
| `access_token` | CHAR(64) UNIQUE | Token rahasia untuk akses halaman tracking (hashed) |
| `borrower_name` | VARCHAR(255) | Nama lengkap peminjam |
| `borrower_id_number` | VARCHAR(30) | NIM / NIP |
| `borrower_type` | VARCHAR(20) | `mahasiswa`, `dosen`, `organisasi`, `lainnya` |
| `borrower_organization` | VARCHAR(255) NULL | Organisasi / UKM / Prodi |
| `borrower_whatsapp` | VARCHAR(20) | No. WA aktif |
| `purpose` | TEXT | Keperluan peminjaman |
| `ktp_file_path` | VARCHAR(500) NULL | Path file KTP (jika ada ruangan requires_ktp) |
| `approval_letter_path` | VARCHAR(500) NULL | Path surat persetujuan WD2 yang di-upload |
| `approval_letter_uploaded_at` | TIMESTAMP NULL | Waktu upload surat WD2 |
| `status` | VARCHAR(30) | Status keseluruhan booking (lihat State Machine) |
| `deadline_at` | TIMESTAMP NULL | Batas waktu upload surat WD2 (created_at + 24 jam) |
| `reviewed_by` | BIGINT FK → users NULL | Admin yang me-review |
| `reviewed_at` | TIMESTAMP NULL | Waktu review |
| `rejection_reason` | TEXT NULL | Alasan penolakan (jika ditolak) |
| `qr_token` | CHAR(36) UNIQUE NULL | UUID untuk QR Code pada PDF final |
| `qr_verified_at` | TIMESTAMP NULL | Waktu QR di-scan/check-in oleh petugas |
| `qr_verified_by` | VARCHAR(255) NULL | Nama petugas yang scan QR |
| `notes_admin` | TEXT NULL | Catatan internal admin |
| `booking_pdf_path` | VARCHAR(500) NULL | Path Dokumen 1 (bukti booking sementara) |
| `approval_pdf_path` | VARCHAR(500) NULL | Path Dokumen 2 (surat izin final ber-QR) |
| `cancelled_at` | TIMESTAMP NULL | Waktu pembatalan (manual atau auto) |
| `cancellation_type` | VARCHAR(20) NULL | `auto_expired`, `admin_rejected`, `user_cancelled` |
| `created_at` | TIMESTAMP | Waktu submit booking |
| `updated_at` | TIMESTAMP | |
| `deleted_at` | TIMESTAMP NULL | |

**State Machine `status`:**
- `pending_upload` → Booking masuk, menunggu upload surat WD2 (countdown 24 jam aktif).
- `pending_review` → Surat WD2 sudah di-upload, menunggu verifikasi admin.
- `approved` → Disetujui admin, PDF final bisa diunduh.
- `rejected` → Ditolak admin.
- `expired` → Auto-cancel karena melewati batas 24 jam tanpa upload.
- `cancelled` → Dibatalkan manual oleh user.
- `checked_in` → QR sudah di-scan pada hari-H (opsional, untuk fitur QR Check-in).
- `completed` → Peminjaman selesai (melewati waktu akhir).

**Index:** `ticket_number` (unique), `access_token` (unique), `status`, `deadline_at`, `qr_token` (unique), `reviewed_by`, `borrower_id_number`.

---

### Tabel 8: `booking_items`

Detail jadwal per ruangan di dalam satu booking. Inilah yang mewakili konsep "keranjang/cart" multi-slot.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `booking_id` | BIGINT FK → bookings | Relasi ke booking induk |
| `room_id` | BIGINT FK → rooms | Ruangan yang dipinjam |
| `booking_date` | DATE | Tanggal pemakaian |
| `start_time` | TIME | Jam mulai |
| `end_time` | TIME | Jam selesai |
| `status` | VARCHAR(30) DEFAULT 'active' | `active`, `cancelled` (per-item cancel jika perlu) |
| `notes` | TEXT NULL | Catatan khusus untuk slot ini |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

**Index:** `booking_id`, `room_id`, composite `(room_id, booking_date, start_time, end_time)` untuk conflict detection.

**Constraint penting (aplikasi-level):** Tidak boleh ada overlap waktu untuk `room_id` + `booking_date` yang sama pada booking dengan status aktif (`pending_upload`, `pending_review`, `approved`). Implementasi via database UNIQUE partial index atau validasi di Laravel FormRequest + DB transaction lock.

---

### Tabel 9: `booking_documents`

Menyimpan semua file yang di-upload atau di-generate terkait satu booking (extensible).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `booking_id` | BIGINT FK → bookings | |
| `document_type` | VARCHAR(50) | `ktp`, `approval_letter`, `booking_receipt_pdf`, `final_approval_pdf`, `other` |
| `file_path` | VARCHAR(500) | Path di storage |
| `original_filename` | VARCHAR(255) NULL | Nama file asli saat upload |
| `file_size` | INT UNSIGNED NULL | Ukuran file dalam bytes |
| `mime_type` | VARCHAR(100) NULL | |
| `uploaded_by` | VARCHAR(50) NULL | `borrower`, `admin`, `system` |
| `created_at` | TIMESTAMP | |

**Index:** `booking_id`, `document_type`.

> **Catatan:** Tabel ini bersifat **opsional/pelengkap**. Field `ktp_file_path`, `approval_letter_path`, `booking_pdf_path`, dan `approval_pdf_path` di tabel `bookings` sudah cukup untuk MVP. Tabel `booking_documents` berguna jika ke depan ada kebutuhan multi-file atau audit trail dokumen yang lebih rinci.

---

### Tabel 10: `notifications`

Notifikasi in-app (menggunakan Laravel Notifications dengan driver `database`).

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | CHAR(36) PK | UUID (Laravel default) |
| `type` | VARCHAR(255) | Notification class (fully qualified) |
| `notifiable_type` | VARCHAR(255) | Polymorphic: model target |
| `notifiable_id` | BIGINT UNSIGNED | ID target |
| `data` | JSON | Payload notifikasi |
| `read_at` | TIMESTAMP NULL | Waktu dibaca |
| `created_at` | TIMESTAMP | |

> Ini adalah tabel standar `php artisan notifications:table`. Untuk notifikasi ke peminjam (guest), gunakan `notifiable` berbasis `booking_id` atau buat model `Borrower` ringan.

---

### Tabel 11: `audit_logs`

Catatan lengkap aktivitas untuk transparansi dan keamanan.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `auditable_type` | VARCHAR(255) | Polymorphic: model yang berubah |
| `auditable_id` | BIGINT UNSIGNED | ID record |
| `event` | VARCHAR(50) | `created`, `updated`, `deleted`, `status_changed`, `file_uploaded`, `qr_scanned` |
| `old_values` | JSON NULL | Nilai sebelum perubahan |
| `new_values` | JSON NULL | Nilai sesudah perubahan |
| `user_id` | BIGINT FK → users NULL | Pelaku (NULL jika guest/system) |
| `user_agent` | VARCHAR(500) NULL | Browser/device info |
| `ip_address` | VARCHAR(45) NULL | IPv4/IPv6 |
| `metadata` | JSON NULL | Data tambahan (ticket_number, dll) |
| `created_at` | TIMESTAMP | |

**Index:** `auditable_type, auditable_id` (composite), `event`, `user_id`, `created_at`.

> **Rekomendasi:** Gunakan package `owen-it/laravel-auditing` atau `spatie/laravel-activitylog` agar tidak perlu menulis observer manual.

---

### Tabel 12: `waitlists`

Antrian otomatis jika slot yang diinginkan sedang terisi.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `room_id` | BIGINT FK → rooms | Ruangan yang diinginkan |
| `desired_date` | DATE | Tanggal yang diinginkan |
| `desired_start_time` | TIME | Jam mulai |
| `desired_end_time` | TIME | Jam selesai |
| `borrower_name` | VARCHAR(255) | Nama peminjam |
| `borrower_whatsapp` | VARCHAR(20) | No. WA untuk notifikasi |
| `borrower_id_number` | VARCHAR(30) NULL | NIM/NIP |
| `is_notified` | BOOLEAN DEFAULT false | Sudah diberitahu slot kosong? |
| `notified_at` | TIMESTAMP NULL | Waktu notifikasi dikirim |
| `expires_at` | TIMESTAMP NULL | Batas waktu respon waitlist |
| `status` | VARCHAR(20) DEFAULT 'waiting' | `waiting`, `notified`, `converted`, `expired` |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

**Index:** `room_id, desired_date` (composite), `status`.

---

### Tabel 13: `booking_templates`

Template peminjaman rutin (misal: rapat mingguan, kuliah tetap) agar mahasiswa tidak mengisi ulang form dari nol.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `borrower_id_number` | VARCHAR(30) | NIM/NIP pemilik template |
| `template_name` | VARCHAR(255) | Nama template ("Rapat BEM Senin") |
| `borrower_name` | VARCHAR(255) | Nama default |
| `borrower_organization` | VARCHAR(255) NULL | Organisasi default |
| `purpose` | TEXT NULL | Keperluan default |
| `template_items` | JSON | Array of {room_id, day_of_week, start_time, end_time} |
| `usage_count` | INT UNSIGNED DEFAULT 0 | Berapa kali dipakai |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

---

### Tabel 14: `settings`

Konfigurasi sistem yang bisa diubah oleh admin via Filament.

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | BIGINT PK | |
| `key` | VARCHAR(100) UNIQUE | Nama setting |
| `value` | TEXT NULL | Nilai |
| `type` | VARCHAR(20) DEFAULT 'string' | `string`, `integer`, `boolean`, `json` |
| `group` | VARCHAR(50) DEFAULT 'general' | Grup setting (general, booking, pdf, notification) |
| `description` | VARCHAR(500) NULL | Penjelasan untuk admin |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

**Contoh isi:**
- `booking.deadline_hours` = `24` (batas waktu upload surat, dalam jam)
- `booking.max_items_per_cart` = `5` (maks slot per booking)
- `pdf.header_logo_path` = path logo
- `pdf.footer_text` = teks footer PDF
- `general.faculty_name` = "Fakultas Ilmu Pendidikan"
- `general.university_name` = "Universitas Negeri Makassar"
- `notification.reminder_before_hours` = `3` (kirim reminder H-3 jam)

---

### Entity-Relationship Diagram (Ringkas)

```
buildings 1──M rooms 1──M room_photos
                │
                ├──M facility_room M──1 room_facilities
                │
                ├──M booking_items M──1 bookings 1──M booking_documents
                │                         │
                ├──M waitlists             ├──1 users (reviewed_by)
                │                         ├──M audit_logs
                                          ├──M notifications
                                          
booking_templates (standalone, linked by borrower_id_number)
settings (standalone config)
```

---

### Saran & Rekomendasi Tambahan

Berikut beberapa saran yang perlu dipertimbangkan berdasarkan analisis spesifikasi:

#### 1. Keamanan Akses Guest (Kritis)
Karena peminjam tidak login, akses ke halaman tracking harus diamankan dengan **access_token** (bukan hanya ticket_number). Ticket number bisa ditampilkan secara ringkas (misal 8 karakter), tapi URL tracking harus menggunakan token yang lebih panjang dan tidak bisa di-brute-force. Contoh URL: `/tracking/{ticket_number}?token={access_token}`. Pertimbangkan juga rate limiting pada endpoint ini.

#### 2. Overlap/Conflict Detection yang Kuat
Validasi overlap waktu adalah titik krusial. Jangan hanya mengandalkan validasi di level aplikasi — gunakan **database-level locking** (SELECT ... FOR UPDATE) saat proses checkout untuk mencegah race condition ketika dua mahasiswa booking ruangan yang sama pada waktu bersamaan. Buat juga **composite index** pada `booking_items (room_id, booking_date, start_time, end_time)` dan query overlap yang tepat.

#### 3. Pisahkan `cancelled` dan `expired` di State Machine
Spesifikasi sudah menyebutkan auto-cancel 24 jam, tapi pastikan `expired` dan `cancelled` adalah status terpisah (sudah diimplementasi di skema ini). Ini penting untuk reporting: admin perlu tahu berapa banyak booking yang gagal karena kelalaian mahasiswa (expired) vs. pembatalan sadar (cancelled) vs. penolakan (rejected).

#### 4. Scheduler untuk Auto-Expiry
Implementasi auto-cancel batas 24 jam harus menggunakan **Laravel Task Scheduler** (`php artisan schedule:run`) yang dijalankan setiap menit, bukan event-driven murni. Buat command `booking:expire-overdue` yang meng-query `WHERE status = 'pending_upload' AND deadline_at < NOW()` lalu update ke `expired`. Ini lebih reliable daripada mengandalkan delayed job.

#### 5. Pertimbangkan Tabel `operating_hours`
Jika ruangan memiliki jam operasional berbeda (misal: Lab hanya bisa dipinjam jam 08:00–16:00, Aula sampai 21:00), tambahkan tabel `room_operating_hours` dengan kolom `day_of_week`, `open_time`, `close_time`. Ini akan mempermudah validasi otomatis di form dan kalender. Tanpa ini, admin harus menolak secara manual jika mahasiswa booking di luar jam operasional.

#### 6. Pertimbangkan Tabel `blocked_dates`
Untuk hari libur nasional, kegiatan fakultas, atau maintenance gedung di mana ruangan tidak bisa dipinjam, buat tabel `blocked_dates` dengan kolom `room_id` (nullable = semua ruangan), `blocked_date`, `reason`. Ini mencegah mahasiswa booking di tanggal yang memang tidak tersedia.

#### 7. File Storage Strategy
Gunakan `Laravel Storage` dengan disk `local` atau `s3`. Untuk file sensitif (KTP, surat), simpan di **private disk** (tidak bisa diakses langsung via URL) dan serve melalui controller dengan authorization check. File PDF yang di-generate (Dokumen 1 & 2) bisa di-cache dan di-regenerate jika diperlukan.

#### 8. QR Code: Signed URL vs Static Token
Untuk QR Code di PDF final, pertimbangkan menggunakan **Laravel Signed URL** (`URL::signedRoute(...)`) dengan expiry, bukan hanya static UUID. Ini memberikan lapisan keamanan tambahan: QR yang sudah kedaluwarsa tidak bisa disalahgunakan untuk booking lain.

#### 9. Soft Delete Strategy
Terapkan `SoftDeletes` pada `bookings` dan `rooms`, tapi **jangan** pada `booking_items` (karena item selalu mengikuti parent booking). Untuk `users` (admin), soft delete penting agar relasi `reviewed_by` tidak broken.

#### 10. Migrasi Tambahan untuk Performa
Untuk fitur Live Board (real-time grid status ruangan), buat **database view** atau **materialized query** yang menggabungkan `rooms` + `booking_items` aktif pada rentang waktu tertentu. Ini menghindari N+1 query saat menampilkan grid seluruh ruangan sekaligus. Alternatif: cache Redis per-slot dengan TTL pendek.

---

