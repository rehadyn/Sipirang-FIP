# Panduan Aturan SLA Booking SIPIRANG

Dokumen ini menjelaskan alur waktu (Service Level Agreement) yang berlaku untuk peminjaman ruangan di platform SIPIRANG.

## 1. Batas Waktu Unggah Persyaratan (Mahasiswa)
*   **Durasi:** 5 Jam sejak nomor tiket diterbitkan.
*   **Kewajiban:** Mahasiswa wajib mengunggah Surat Persetujuan dari Wakil Dekan 2 (WD 2).
*   **Konsekuensi:** Jika dokumen tidak diunggah dalam waktu 5 jam, sistem akan secara otomatis membatalkan booking (*Auto-Expired*) dan melepaskan ketersediaan ruangan untuk peminjam lain.
*   **UI Indikator:** Di halaman tracking, akan muncul countdown **"Sisa Waktu Lengkapi Persyaratan"** berwarna amber/orange.

## 2. Batas Waktu Verifikasi Admin (SLA Admin)
*   **Durasi:** Maksimal 2 Hari Kerja setelah dokumen diunggah.
*   **Kewajiban:** Admin wajib meninjau dan memberikan keputusan (Setuju/Tolak) terhadap permohonan.
*   **UI Indikator:** Setelah dokumen diunggah, halaman tracking akan menampilkan countdown estimasi **"SLA Estimasi Verifikasi Admin"** berwarna indigo.

## 3. Alur Status & Tampilan
*   **Menunggu Upload:** Menampilkan sisa waktu 5 jam.
*   **Menunggu Review:** Menampilkan sisa waktu 2 hari SLA admin.
*   **Disetujui (Approved):** 
    *   Countdown hilang.
    *   Halaman menampilkan detail **"Detail Booking Terkonfirmasi"** dengan centang hijau.
    *   Menampilkan tanggal review dan daftar ruangan/sesi yang dipesan secara permanen.
    *   Link download Surat Izin Resmi muncul.

## 4. Ketentuan Lain
*   SLA Admin dihitung berdasarkan waktu unggah dokumen terakhir.
*   Jika Admin menolak permohonan, peminjam akan menerima alasan penolakan dan status menjadi *Rejected*.
