# Panduan & Alur Peminjaman Ruangan — FIP UNM
### Sistem Peminjaman Ruangan (SIPIRANG)

---

## Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Alur Peminjam (Mahasiswa / Dosen / Organisasi)](#2-alur-peminjam)
3. [Alur Admin (Pengelola Fasilitas)](#3-alur-admin)
4. [Diagram Alur Lengkap](#4-diagram-alur-lengkap)
5. [Status Booking & Artinya](#5-status-booking--artinya)
6. [Ketentuan & Batas Waktu](#6-ketentuan--batas-waktu)
7. [Best Practice untuk Peminjam](#7-best-practice-untuk-peminjam)
8. [Best Practice untuk Admin](#8-best-practice-untuk-admin)
9. [Pertanyaan Umum (FAQ)](#9-pertanyaan-umum-faq)

---

## 1. Gambaran Umum

SIPIRANG adalah sistem peminjaman ruangan berbasis web untuk Fakultas Ilmu Pendidikan, Universitas Negeri Makassar. Peminjam tidak perlu membuat akun — cukup ajukan melalui situs, upload surat persetujuan, dan unduh surat izin resmi setelah disetujui admin.

**Jenis peminjam yang dapat mengajukan:**
- Mahasiswa (individu atau kelompok)
- Dosen
- Organisasi kemahasiswaan (BEM, UKM, Himpunan, dll.)
- Pihak lainnya

**Ruangan yang dapat dipinjam:**
- Kelas, lab, ruang rapat, aula, dan ruang seminar

**Sesi waktu yang tersedia:**
| Sesi | Jam |
|---|---|
| Pagi | 07.00 – 12.00 |
| Siang | 13.00 – 17.00 |
| Fullday | 07.00 – 17.00 |

---

## 2. Alur Peminjam

### Langkah 1 — Cek Ketersediaan Ruangan

Sebelum mengajukan, periksa terlebih dahulu apakah ruangan tersedia pada tanggal yang diinginkan.

- Buka halaman **Kalender Ruangan** untuk melihat jadwal penggunaan yang sudah disetujui
- Atau buka halaman **Pilih Ruangan** untuk melihat status slot real-time (Pagi / Siang / Fullday)

> **Tips:** Ajukan minimal **H-2** (dua hari sebelum penggunaan) agar admin punya waktu untuk mereview.

---

### Langkah 2 — Pilih Ruangan & Tambah ke Keranjang

1. Buka **Pilih Ruangan** di menu navigasi
2. Tentukan tanggal peminjaman (hanya bisa memilih tanggal mulai hari ini hingga maksimal 30 hari ke depan)
3. Klik **+ Pilih** pada sesi yang tersedia (hijau)
4. Ulangi untuk ruangan atau tanggal lain jika diperlukan — satu pengajuan bisa memuat beberapa ruangan sekaligus
5. Pantau **Keranjang** di sisi kanan; klik **Lanjut Checkout** jika sudah selesai

> **Batas maksimal:** Satu pengajuan hanya dapat memuat **5 slot** (ruangan × sesi).

---

### Langkah 3 — Isi Data & Konfirmasi Booking

1. Lengkapi formulir identitas:
   - **Nama lengkap** (sesuai KTP/KTM)
   - **NIM / NIP / No. Identitas**
   - **Tipe peminjam** (Mahasiswa / Dosen / Organisasi)
   - **Jurusan / Prodi** (jika mahasiswa/dosen)
   - **Nama organisasi** (jika organisasi)
   - **No. WhatsApp aktif** ← penting, digunakan untuk mengakses halaman tracking
   - **Keperluan peminjaman** (singkat dan jelas)
2. Jika ada ruangan yang mewajibkan **KTP**, upload file KTP/KTM (JPG, PNG, atau PDF)
3. Klik **Konfirmasi Booking**

Setelah konfirmasi:
- Sistem mengunci slot ruangan yang dipilih
- **PDF Tanda Terima** otomatis diunduh dan terbuka di tab baru
- Anda diarahkan ke halaman **Tracking**
- **Catat nomor tiket dan simpan nomor WhatsApp** yang digunakan

---

### Langkah 4 — Upload Surat Persetujuan WD 2

> ⚠️ **WAJIB dilakukan sebelum batas waktu yang tertera di PDF Tanda Terima.**
> Jika terlewat, booking dibatalkan otomatis dan slot ruangan dilepas kembali.

**Cara mendapatkan Surat Persetujuan WD 2:**
- Buat surat permohonan peminjaman ruangan secara resmi
- Minta tanda tangan Wakil Dekan 2 Bidang Kemahasiswaan
- Scan atau foto surat yang sudah ditandatangani (format PDF)

**Cara upload:**
1. Buka halaman Tracking: `sipirang.test/tracking/{NOMOR_TIKET}?phone={NO_WA}`
   *(atau scan QR di PDF Tanda Terima)*
2. Klik **Pilih File PDF** di bagian "Unggah Surat WD 2"
3. Pilih file PDF surat yang sudah ditandatangani
4. Klik **Kirim Persetujuan WD**
5. Status berubah menjadi **Menunggu Review**

---

### Langkah 5 — Tunggu Verifikasi Admin

Admin akan mereview dokumen yang diunggah dalam waktu maksimal **2 hari kerja**. Pantau status di halaman Tracking.

| Hasil Review | Status | Tindakan |
|---|---|---|
| Disetujui | **Disetujui** | Unduh Surat Izin Resmi (lihat Langkah 6) |
| Ditolak | **Ditolak** | Baca alasan penolakan, hubungi admin WA |

---

### Langkah 6 — Unduh & Tunjukkan Surat Izin Resmi

Jika disetujui:
1. Buka halaman Tracking
2. Klik **Unduh Surat Izin Resmi** (tombol biru)
3. **Tunjukkan surat izin** beserta **QR Code** kepada petugas keamanan / gedung pada hari pelaksanaan
4. Petugas akan scan QR untuk memverifikasi keaslian surat

> Surat Izin Resmi dilengkapi:
> - Detail peminjam dan jadwal
> - Catatan tambahan dari admin (jika ada)
> - QR Code terenkripsi untuk verifikasi
> - Syarat, ketentuan, dan sanksi penggunaan (halaman 2)

---

## 3. Alur Admin

### Tugas Harian

#### Pagi (mulai kerja)

1. **Buka Dashboard** → cek ringkasan:
   - Jumlah booking **Menunggu Review** (amber)
   - Jumlah booking **Menunggu Upload** (kemungkinan akan expired)
   - Alert **SLA 2 Hari** — booking yang belum diproses lebih dari 2 hari

2. **Proses booking Menunggu Review** (prioritas utama):
   - Klik nama peminjam di dashboard atau masuk ke menu **Booking**
   - Buka detail booking
   - Preview **Surat WD 2** secara inline (tombol "Preview" di kartu dokumen)
   - Verifikasi keaslian dan kelengkapan surat:
     - Tanda tangan WD 2 ada?
     - Nama peminjam sesuai?
     - Tanggal surat relevan?
     - Keperluan sesuai dengan yang diajukan?

3. **Ambil keputusan:**
   - ✅ **Setujui** → klik tombol hijau "Setujui Booking"
     - Tambahkan catatan jika perlu (contoh: "Pastikan kebersihan ruangan dijaga")
     - Surat Izin Resmi + QR Code dibuat otomatis
   - ❌ **Tolak** → klik tombol merah "Tolak Booking"
     - **Wajib** isi alasan penolakan yang jelas dan sopan
     - Contoh: *"Surat WD 2 belum ditandatangani. Mohon lengkapi dan ajukan ulang."*

#### Siang / Sore

4. **Pantau booking expired** — booking yang melewati batas waktu upload sudah otomatis dibatalkan oleh sistem. Tidak perlu aksi manual.

5. **Cek kalender** → pastikan tidak ada konflik jadwal yang tidak terdeteksi sistem.

---

### Alur Verifikasi Dokumen

```
Terima notifikasi "Menunggu Review"
          ↓
Buka Detail Booking
          ↓
Preview Surat WD 2 (inline, tanpa download)
          ↓
  Lengkap & Valid?
   ↙         ↘
 YA           TIDAK
  ↓              ↓
Setujui    Tolak + isi alasan
  ↓              ↓
PDF Surat   Status = Ditolak
Izin + QR   Slot dilepas kembali
dibuat
otomatis
```

---

### Pengelolaan Ruangan

**Menonaktifkan ruangan sementara:**
- Masuk ke **Manajemen → Ruangan**
- Klik toggle slide di kolom Status → ruangan tidak bisa dipesan hingga diaktifkan kembali

**Memblokir tanggal tertentu (hari libur, acara khusus):**
- Masuk ke **Manajemen → Ruangan** → klik **Edit** pada ruangan
- Scroll ke bagian **Tanggal Diblokir**
- Isi tanggal dan keterangan (contoh: "Libur Nasional", "Acara Wisuda")
- Klik **Blokir Tanggal**

---

## 4. Diagram Alur Lengkap

```
PEMINJAM                                    SISTEM                         ADMIN
   │                                          │                               │
   ├─ Pilih ruangan & slot ────────────────► Kunci slot sementara             │
   │                                          │                               │
   ├─ Isi data & konfirmasi ─────────────── ► Buat Booking (pending_upload)   │
   │                                          │                               │
   │◄─ PDF Tanda Terima (auto-download) ────  │                               │
   │   (berisi batas waktu upload)            │                               │
   │                                          │                               │
   ├─ Upload Surat WD 2 ─────────────────── ► Status → pending_review         │
   │                                          │                               │
   │                                          ├─ Notif dashboard ────────────►│
   │                                          │                               ├─ Preview surat
   │                                          │                               ├─ Verifikasi
   │                                          │                      Setujui ─┤
   │                                          │◄─ Status → approved ──────────┤
   │                                          │                               │
   │◄─ Surat Izin + QR (auto-generate) ─────  │                               │
   │                                          │                               │
   ├─ Tunjukkan surat ke petugas              │                               │
   │                                          │                               │
PETUGAS GEDUNG                                │                               │
   ├─ Scan QR ──────────────────────────── ► Halaman tracking terbuka         │
   └─ Verifikasi status booking               │                               │
```

---

## 5. Status Booking & Artinya

| Status | Warna | Artinya | Aksi yang Diperlukan |
|---|---|---|---|
| **Menunggu Upload** | Abu-abu | Booking dibuat, surat belum diunggah | Upload Surat WD 2 sebelum batas waktu |
| **Menunggu Review** | Amber | Surat sudah diunggah, menunggu admin | Tunggu keputusan admin (maks. 2 hari kerja) |
| **Disetujui** | Hijau | Booking disetujui, surat izin tersedia | Unduh dan simpan Surat Izin Resmi |
| **Ditolak** | Merah | Dokumen tidak memenuhi syarat | Baca alasan, hubungi admin jika perlu ajukan ulang |
| **Kedaluwarsa** | Abu-abu | Batas waktu upload terlewati | Ajukan ulang booking baru |
| **Dibatalkan** | Abu-abu | Dibatalkan oleh sistem atau admin | — |

---

## 6. Ketentuan & Batas Waktu

| Ketentuan | Nilai | Keterangan |
|---|---|---|
| **Batas waktu upload** | 5 jam* | Setelah booking dibuat, surat WD 2 wajib diupload |
| **SLA verifikasi admin** | 2 hari kerja | Target penyelesaian review oleh admin |
| **Maks. slot per booking** | 5 slot* | Batas ruangan × sesi dalam satu pengajuan |
| **Maks. hari ke depan** | 30 hari* | Paling jauh tanggal yang bisa dipesan |

*) Nilai dapat berubah sesuai konfigurasi admin di **Pengaturan Sistem**.

**Sanksi kerusakan ruangan** (lihat halaman 2 Surat Izin Resmi):
- Ringan: teguran tertulis + perbaikan mandiri
- Sedang: ganti biaya perbaikan
- Berat: ganti penuh + laporan ke pimpinan
- Penyalahgunaan: black list min. 6 bulan + sanksi akademik

---

## 7. Best Practice untuk Peminjam

### ✅ Lakukan

- **Upload surat sesegera mungkin** setelah booking dikonfirmasi — jangan tunggu batas waktu
- **Simpan nomor tiket** dan pastikan nomor WhatsApp yang didaftarkan masih aktif
- **Gunakan PDF surat yang jelas** — tanda tangan terlihat, tidak buram, tidak terpotong
- **Isi keperluan dengan spesifik** — "Rapat koordinasi program kerja BEM FIP 2026" lebih baik dari "rapat"
- **Booking minimal H-2** agar admin punya waktu review
- **Simpan Surat Izin Resmi** di handphone sebelum hari H — jangan mengandalkan koneksi internet di lokasi
- **Bawa hard copy Surat Izin** sebagai cadangan jika HP bermasalah

### ❌ Hindari

- Mendaftar dengan nomor WhatsApp yang tidak aktif
- Upload foto surat yang blur, gelap, atau terpotong
- Mengirim surat WD 2 dalam format Word/Excel — harus **PDF**
- Meminjam ruangan untuk keperluan yang berbeda dari yang diajukan
- Memindahtangankan izin peminjaman kepada pihak lain
- Booking terlalu mepet (H-0 atau H-1) karena berisiko tidak cukup waktu review

---

## 8. Best Practice untuk Admin

### Pengelolaan Booking

- **Review booking dalam urutan upload** (FIFO) — yang paling lama menunggu diprioritaskan
- **Periksa 3 hal utama saat review:**
  1. Tanda tangan WD 2 asli dan jelas
  2. Nama peminjam sesuai dengan yang di-input
  3. Tanggal surat tidak terlalu jauh dari tanggal booking
- **Selalu isi catatan saat menolak** — alasan yang jelas membantu peminjam perbaiki pengajuan
- **Gunakan tombol Tracking** di detail booking untuk memantau status dari sisi peminjam

### Komunikasi

- **Gunakan link WA** di halaman detail booking untuk menghubungi peminjam langsung jika ada pertanyaan sebelum approve/reject
- **Tulis catatan approval yang bermanfaat** (contoh: "Harap kembalikan kursi ke posisi semula setelah selesai") — catatan ini muncul di Surat Izin Resmi

### Pemeliharaan Sistem

- **Nonaktifkan ruangan** via toggle di halaman Ruangan jika sedang dalam perbaikan/renovasi
- **Blokir tanggal** di halaman edit ruangan untuk hari libur nasional, Dies Natalis, atau acara khusus fakultas yang tidak bisa di-booking
- **Cek laporan bulanan** di akhir bulan untuk evaluasi penggunaan ruangan
- **Update pengaturan** (deadline, maks. slot) di **Pengaturan Sistem** sesuai kebijakan terbaru

### Regenerasi PDF

Jika perlu menerbitkan ulang Surat Izin setelah perubahan data (misalnya setelah update nama fakultas di pengaturan):
1. Buka Detail Booking yang bersangkutan
2. Klik **Tolak** lalu **Setujui** ulang *(hanya jika memang diperlukan)*

---

## 9. Pertanyaan Umum (FAQ)

**Q: Booking saya expired padahal saya sudah upload surat.**
A: Pastikan upload dilakukan lewat halaman **Tracking** (bukan email atau cara lain), dan klik tombol **Kirim Persetujuan WD**. Jika sudah dan masih expired, hubungi admin via WhatsApp.

---

**Q: Saya lupa nomor tiket, bagaimana cara mengaksesnya?**
A: Nomor tiket ada di PDF **Tanda Terima Booking** yang otomatis diunduh saat booking dibuat. Cek folder unduhan di perangkat Anda. Nomor WhatsApp yang didaftarkan juga diperlukan untuk membuka halaman tracking.

---

**Q: Saya mendaftar dengan nomor WA yang salah. Bisa diperbaiki?**
A: Tidak bisa diperbaiki sendiri. Hubungi admin FIP via WhatsApp resmi dengan menyebutkan nomor tiket untuk verifikasi manual.

---

**Q: Apakah bisa mengajukan peminjaman untuk beberapa tanggal berbeda sekaligus?**
A: Ya. Pilih ruangan untuk tanggal pertama, lalu ubah tanggal di picker dan pilih ruangan lagi. Semua masuk ke satu keranjang dan dikonfirmasi dalam satu tiket.

---

**Q: Admin belum memproses lebih dari 2 hari kerja. Apa yang harus dilakukan?**
A: Hubungi admin FIP via WhatsApp resmi atau datang langsung ke Tata Usaha FIP dengan membawa nomor tiket booking.

---

**Q: Surat izin saya sudah expired (booking sudah lewat tanggal penggunaan). Apakah masih valid?**
A: Surat izin valid hanya pada tanggal dan sesi yang tertera. Untuk penggunaan di lain waktu, ajukan booking baru.

---

**Q: Saya sudah setujui booking tapi perlu membatalkan. Bagaimana?**
A: Saat ini pembatalan oleh admin dilakukan manual via menu detail booking (klik Tolak dengan alasan pembatalan). Hubungi tim teknis jika dibutuhkan fitur pembatalan khusus.

---

*Dokumen ini berlaku untuk SIPIRANG v1.0 — Fakultas Ilmu Pendidikan, Universitas Negeri Makassar.*
*Dibuat dengan ❤️ oleh REHAD — Clavis Ignoti Profundi Arcanorum*
