<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0; font-size: 9pt; line-height: 1.3; color: #555; }
        .title-section { text-align: center; margin-bottom: 20px; }
        .status-badge { display: inline-block; padding: 5px 18px; border-radius: 5px; font-weight: bold; font-size: 11pt; margin-bottom: 8px; }
        .badge-approved { background-color: #10b981; color: white; }
        h3 { margin: 4px 0; font-size: 13pt; text-decoration: underline; }
        .ticket-number { margin: 4px 0 0; font-size: 11pt; }
        .content { margin-bottom: 20px; }
        .info-table { width: 100%; border: none; margin: 8px 0 15px; }
        .info-table td { border: none; padding: 4px 0; font-size: 12px; }
        .info-table td:first-child { width: 160px; }
        table.schedule { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.schedule th, table.schedule td { border: 1px solid #ddd; padding: 9px 10px; text-align: left; font-size: 12px; }
        table.schedule th { background-color: #f5f5f5; font-weight: bold; }
        .note-section { margin-top: 18px; font-size: 12px; }
        .footer { margin-top: 40px; position: relative; }
        .qr-box { float: right; text-align: center; border: 2px solid #10b981; padding: 10px; border-radius: 10px; margin-bottom: 10px; }
        .qr-box p { margin: 5px 0 0; font-size: 11px; font-weight: bold; color: #10b981; }
        .qr-box .qr-date { font-size: 9px; color: #666; font-weight: normal; }
        .footer-stamp { border-top: 1px dashed #ccc; padding-top: 15px; text-align: center; font-size: 10px; color: #666; clear: both; }

        /* Page 2: Terms & Conditions */
        .page-break { page-break-before: always; }
        .terms-title { text-align: center; margin: 15px 0 25px; }
        .terms-title h3 { font-size: 13pt; text-decoration: underline; margin: 0; }
        .terms-title p { margin: 6px 0 0; font-size: 10pt; color: #555; }
        .article { margin-bottom: 18px; }
        .article-title { font-size: 12pt; font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 8px; }
        .article ol { margin: 0; padding-left: 22px; }
        .article ol li { margin-bottom: 5px; line-height: 1.5; }
        .sanction-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 11px; }
        .sanction-table th, .sanction-table td { border: 1px solid #999; padding: 7px 9px; text-align: left; vertical-align: top; }
        .sanction-table th { background-color: #f5f5f5; font-weight: bold; }
        .sanction-table td:first-child { width: 35%; }
        .declaration { margin-top: 25px; padding: 12px; border: 1px solid #999; background-color: #fafafa; font-size: 11px; line-height: 1.5; }
        .signature-section { margin-top: 30px; width: 100%; }
        .signature-section table { width: 100%; }
        .signature-section td { width: 50%; text-align: center; vertical-align: top; padding: 5px; font-size: 11px; }
        .signature-space { height: 65px; }
        .signature-name { font-weight: bold; text-decoration: underline; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ strtoupper($university ?? 'UNIVERSITAS NEGERI MAKASSAR') }}</h2>
        <h1>{{ strtoupper($faculty ?? 'FAKULTAS ILMU PENDIDIKAN') }}</h1>
        <p>Alamat: Kampus UNM Tidung, Jl. Tamalate 1, Makassar. Telp: (0411) 883076</p>
        <p>Laman: <span style="color: blue; text-decoration: underline;">fip.unm.ac.id</span> | Email: fip@unm.ac.id</p>
    </div>

    <div class="title-section">
        <div class="status-badge badge-approved">DISETUJUI (APPROVED)</div>
        <h3>SURAT IZIN PENGGUNAAN RUANGAN</h3>
        <p class="ticket-number">Nomor Tiket: <strong>{{ $booking->ticket_number }}</strong></p>
    </div>

    <div class="content">
        <p>Berdasarkan permohonan yang diajukan oleh:</p>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>: {{ $booking->borrower_name }}</td>
            </tr>
            <tr>
                <td>NIM / NIP</td>
                <td>: {{ $booking->borrower_id_number }}</td>
            </tr>
            <tr>
                <td>Organisasi/Prodi</td>
                <td>: {{ $booking->borrower_organization ?? '-' }}</td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>: {{ $booking->purpose }}</td>
            </tr>
        </table>

        <p>Dengan ini <strong>MEMBERIKAN IZIN</strong> untuk menggunakan ruangan berikut:</p>
        <table class="schedule">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Ruangan</th>
                    <th>Sesi / Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($booking->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->booking_date)->translatedFormat('d F Y') }}</td>
                        <td>{{ $item->room->name }} ({{ $item->room->building->name ?? '' }})</td>
                        <td>{{ $item->session_label }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="note-section">
            <strong>Catatan Tambahan dari Admin:</strong><br>
            {{ $booking->notes_admin ?? 'Tidak ada catatan.' }}
        </div>

        <div class="note-section">
            Demikian surat izin ini diberikan untuk dapat dipergunakan sebagaimana mestinya. Harap tunjukkan surat ini beserta QR Code kepada petugas keamanan/gedung pada hari pelaksanaan.
        </div>
    </div>

    <div class="footer">
        <div class="qr-box">
            <img src="{{ $qrCodeUrl }}" alt="QR Code Validasi" width="120" height="120">
            <p>Scan untuk Validasi</p>
            <p class="qr-date">{{ \Carbon\Carbon::parse($booking->reviewed_at)->translatedFormat('d M Y H:i') }}</p>
        </div>
        <div class="footer-stamp">
            Dicetak secara otomatis oleh Sistem Peminjaman Ruangan (SIPIRANG) pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>

    {{-- Halaman 2: Syarat, Ketentuan & Sanksi --}}
    <div class="page-break"></div>

    <div class="header">
        <h2>{{ strtoupper($university ?? 'UNIVERSITAS NEGERI MAKASSAR') }}</h2>
        <h1>{{ strtoupper($faculty ?? 'FAKULTAS ILMU PENDIDIKAN') }}</h1>
        <p>Alamat: Kampus UNM Tidung, Jl. Tamalate 1, Makassar. Telp: (0411) 883076</p>
        <p>Laman: <span style="color: blue; text-decoration: underline;">fip.unm.ac.id</span> | Email: fip@unm.ac.id</p>
    </div>

    <div class="terms-title">
        <h3>SYARAT, KETENTUAN, DAN SANKSI PENGGUNAAN RUANGAN</h3>
        <p>Lampiran Surat Izin Nomor Tiket: <strong>{{ $booking->ticket_number }}</strong></p>
    </div>

    <div class="article">
        <div class="article-title">Pasal 1 — Kewajiban Peminjam</div>
        <ol>
            <li>Menggunakan ruangan sesuai dengan keperluan dan jadwal yang telah disetujui pada surat izin ini.</li>
            <li>Menjaga kebersihan, kerapian, dan ketertiban ruangan selama dan setelah penggunaan.</li>
            <li>Memeriksa kondisi ruangan beserta seluruh fasilitas sebelum dan sesudah penggunaan, serta melaporkan setiap kerusakan kepada petugas/admin.</li>
            <li>Mengembalikan ruangan dalam kondisi semula, termasuk merapikan kursi, meja, dan peralatan yang digunakan.</li>
            <li>Mematikan AC, lampu, dan peralatan elektronik lainnya setelah selesai digunakan.</li>
            <li>Menunjukkan surat izin ini beserta QR Code kepada petugas keamanan/gedung pada hari pelaksanaan.</li>
        </ol>
    </div>

    <div class="article">
        <div class="article-title">Pasal 2 — Larangan</div>
        <ol>
            <li>Memindahtangankan izin penggunaan ruangan kepada pihak lain tanpa persetujuan tertulis dari pengelola.</li>
            <li>Mengubah tata letak ruangan secara permanen atau memodifikasi fasilitas tanpa izin.</li>
            <li>Menggunakan ruangan untuk kegiatan di luar keperluan yang disetujui.</li>
            <li>Membawa makanan, minuman, atau benda berbahaya ke dalam ruangan yang dilarang (sesuai jenis ruangan).</li>
            <li>Merokok, menggunakan zat terlarang, atau melakukan tindakan yang melanggar tata tertib kampus di dalam ruangan.</li>
        </ol>
    </div>

    <div class="article">
        <div class="article-title">Pasal 3 — Sanksi atas Kerusakan & Pelanggaran</div>
        <p style="margin: 0 0 6px;">Apabila terjadi kerusakan, kehilangan, atau pelanggaran ketentuan, peminjam wajib bertanggung jawab dengan ketentuan sebagai berikut:</p>
        <table class="sanction-table">
            <thead>
                <tr>
                    <th>Jenis Pelanggaran / Kerusakan</th>
                    <th>Sanksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Kerusakan ringan (cat tergores, kabel terlepas, kursi/meja bergeser tanpa dirapikan)</td>
                    <td>Teguran tertulis dan kewajiban merapikan/memperbaiki kembali sebelum meninggalkan ruangan.</td>
                </tr>
                <tr>
                    <td>Kerusakan sedang (lampu pecah, AC bermasalah karena salah pemakaian, proyektor/peralatan rusak)</td>
                    <td>Mengganti biaya perbaikan atau penggantian sesuai nilai kerusakan berdasarkan estimasi pengelola fasilitas.</td>
                </tr>
                <tr>
                    <td>Kerusakan berat (struktur ruangan, instalasi listrik/jaringan, fasilitas utama)</td>
                    <td>Mengganti biaya perbaikan/penggantian penuh dan dilaporkan kepada pimpinan Fakultas untuk tindak lanjut.</td>
                </tr>
                <tr>
                    <td>Kehilangan inventaris ruangan</td>
                    <td>Mengganti dengan barang yang sama (jenis, merek, dan spesifikasi setara) atau mengganti senilai harga barang.</td>
                </tr>
                <tr>
                    <td>Penyalahgunaan izin / pelanggaran tata tertib</td>
                    <td>Pembatalan izin penggunaan, pemblokiran (black list) hak peminjaman ruangan minimal 6 bulan, dan/atau sanksi akademik sesuai aturan kampus.</td>
                </tr>
            </tbody>
        </table>
        <p style="margin: 8px 0 0; font-size: 10px; font-style: italic; color: #555;">*Penggantian biaya kerusakan dilakukan paling lambat 14 (empat belas) hari kerja sejak surat pemberitahuan diterima oleh peminjam.</p>
    </div>

    <div class="declaration">
        <strong>PERNYATAAN PEMINJAM:</strong> Dengan menerima dan menggunakan surat izin ini, peminjam (atas nama <strong>{{ $booking->borrower_name }}</strong>) menyatakan telah membaca, memahami, dan menyetujui seluruh syarat, ketentuan, dan sanksi sebagaimana tercantum di atas, serta bersedia menanggung segala konsekuensi atas pelanggaran yang dilakukan.
    </div>

    <div class="signature-section">
        <table>
            <tr>
                <td>
                    Peminjam,
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $booking->borrower_name }}</div>
                    <div style="font-size: 10px; color: #555;">{{ $booking->borrower_id_number }}</div>
                </td>
                <td>
                    Mengetahui,<br>Pengelola Fasilitas FIP UNM
                    <div class="signature-space"></div>
                    <div class="signature-name">_______________________</div>
                    <div style="font-size: 10px; color: #555;">NIP. _______________________</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-stamp" style="margin-top: 25px;">
        Halaman 2 dari 2 — SIPIRANG · {{ $booking->ticket_number }}
    </div>
</body>
</html>
