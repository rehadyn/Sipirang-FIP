<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Booking</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.6; color: #333; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16pt; text-transform: uppercase; }
        .header h2 { margin: 0; font-size: 13pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0; font-size: 9pt; line-height: 1.3; color: #555; }
        .title-section { text-align: center; margin-bottom: 20px; }
        .status-badge { display: inline-block; padding: 5px 18px; border-radius: 5px; font-weight: bold; font-size: 11pt; margin-bottom: 8px; }
        .badge-pending { background-color: #f59e0b; color: white; }
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
        .footer { margin-top: 40px; }
        .footer-stamp { border-top: 1px dashed #ccc; padding-top: 15px; text-align: center; font-size: 10px; color: #666; }
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
        <div class="status-badge badge-pending">MENUNGGU PERSETUJUAN</div>
        <h3>TANDA TERIMA BOOKING SEMENTARA</h3>
        <p class="ticket-number">Nomor Tiket: <strong>{{ $booking->ticket_number }}</strong></p>
    </div>

    <div class="content">
        <p>Telah diterima data pendaftaran peminjaman ruangan dari:</p>
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

        <p>Daftar ruangan dan jadwal yang di-booking:</p>
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
            <strong>Peringatan:</strong> Ini hanyalah tanda terima bahwa slot ruangan Anda telah dikunci sementara. Anda wajib mengunggah (upload) Surat Permohonan / Persetujuan resmi (contoh: Surat WD 2) di sistem pelacakan (tracking) dalam waktu maksimal <strong>{{ $deadline_hours ?? 5 }} jam</strong> setelah booking dibuat. Booking akan dibatalkan otomatis jika batas waktu terlewati.
        </div>
    </div>

    <div class="footer">
        <div class="footer-stamp">
            Dicetak secara otomatis oleh Sistem Peminjaman Ruangan (SIPIRANG) pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
        </div>
    </div>
</body>
</html>
