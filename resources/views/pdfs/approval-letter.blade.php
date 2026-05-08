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
    </style>
</head>
<body>
    <div class="header">
        <h2>UNIVERSITAS NEGERI MAKASSAR</h2>
        <h1>FAKULTAS ILMU PENDIDIKAN</h1>
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
</body>
</html>
