<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; line-height: 1.5; color: #333; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18pt; text-transform: uppercase; }
        .header h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header p { margin: 2px 0 0; font-size: 10pt; line-height: 1.2; }
        .content { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f5f5f5; }
        .footer { margin-top: 50px; position: relative; }
        .qr-box { float: right; text-align: center; border: 2px solid #10b981; padding: 10px; border-radius: 10px; }
        .qr-box p { margin: 5px 0 0; font-size: 12px; font-weight: bold; color: #10b981; }
        .status-badge { display: inline-block; background-color: #10b981; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>UNIVERSITAS NEGERI MAKASSAR</h2>
        <h1>FAKULTAS ILMU PENDIDIKAN</h1>
        <p>Alamat: Kampus UNM Tidung, Jl. Tamalate 1, Makassar. Telp: (0411) 883076</p>
        <p>Laman: <span style="color: blue; text-decoration: underline;">fip.unm.ac.id</span> | Email: fip@unm.ac.id</p>
    </div>

    <div class="content">
        <div style="text-align: center;">
            <div class="status-badge">DISETUJUI (APPROVED)</div>
            <h3 style="margin-top: 0; text-decoration: underline;">SURAT IZIN PENGGUNAAN RUANGAN</h3>
            <p>Nomor Tiket: <strong>{{ $booking->ticket_number }}</strong></p>
        </div>

        <p>Berdasarkan permohonan yang diajukan oleh:</p>
        <table style="border: none; margin-top: 5px;">
            <tr>
                <td style="border: none; width: 150px; padding: 5px 0;">Nama Lengkap</td>
                <td style="border: none; padding: 5px 0;">: {{ $booking->borrower_name }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;">NIM / NIP</td>
                <td style="border: none; padding: 5px 0;">: {{ $booking->borrower_id_number }}</td>
            </tr>
            <tr>
                <td style="border: none; padding: 5px 0;">Organisasi/Prodi</td>
                <td style="border: none; padding: 5px 0;">: {{ $booking->borrower_organization ?? '-' }}</td>
            </tr>
        </table>

        <p>Dengan ini <strong>MEMBERIKAN IZIN</strong> untuk menggunakan ruangan berikut untuk keperluan: <em>{{ $booking->purpose }}</em></p>

        <table>
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

        <p style="margin-top: 20px;">
            <strong>Catatan Tambahan dari Admin:</strong><br>
            {{ $booking->notes_admin ?? 'Tidak ada catatan.' }}
        </p>

        <p style="margin-top: 20px;">Demikian surat izin ini diberikan untuk dapat dipergunakan sebagaimana mestinya. Harap tunjukkan surat ini beserta QR Code kepada petugas keamanan/gedung pada hari pelaksanaan.</p>
    </div>

    <div class="footer">
        <div class="qr-box">
            <img src="{{ $qrCodeUrl }}" alt="QR Code Validasi" width="120" height="120">
            <p>Scan untuk Validasi</p>
            <p style="font-size: 10px; color: #666; font-weight: normal; margin-top: 2px;">{{ \Carbon\Carbon::parse($booking->reviewed_at)->translatedFormat('d M Y H:i') }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
