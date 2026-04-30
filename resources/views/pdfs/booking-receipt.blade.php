<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Booking</title>
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
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; border-top: 1px dashed #ccc; padding-top: 20px; }
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
        <h3 style="text-align: center; text-decoration: underline;">TANDA TERIMA BOOKING SEMENTARA</h3>
        <p style="text-align: center;">Nomor Tiket: <strong>{{ $booking->ticket_number }}</strong></p>

        <p>Telah diterima data pendaftaran peminjaman ruangan dari:</p>
        <table style="border: none;">
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
            <tr>
                <td style="border: none; padding: 5px 0;">Keperluan</td>
                <td style="border: none; padding: 5px 0;">: {{ $booking->purpose }}</td>
            </tr>
        </table>

        <p>Daftar ruangan dan jadwal yang di-booking:</p>

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

        <p style="margin-top: 20px;"><strong>Peringatan:</strong> Ini hanyalah tanda terima bahwa slot ruangan Anda telah dikunci sementara. Anda wajib mengunggah (upload) Surat Permohonan / Persetujuan resmi (contoh: Surat WD 2) di sistem pelacakan (tracking) sebelum batas waktu habis.</p>
    </div>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Peminjaman Ruangan (SIPIRANG) pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>
