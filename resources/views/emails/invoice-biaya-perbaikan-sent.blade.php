<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Tagihan Biaya Perbaikan</title>
</head>
<body>
    <h2>Nota Tagihan Biaya Perbaikan</h2>

    <p>Halo {{ $booking->nama }},</p>

    <p>Berikut adalah ringkasan tagihan biaya perbaikan untuk booking Anda:</p>

    <h3>Detail Booking</h3>
    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</li>
        <li><strong>Nama:</strong> {{ $booking->nama }}</li>
        <li><strong>Telepon:</strong> {{ $booking->telepon }}</li>
        <li><strong>Kendaraan:</strong> {{ $booking->kendaraan }}</li>
        <li><strong>Nopol:</strong> {{ $booking->nopol }}</li>
        <li><strong>Layanan:</strong> {{ $booking->layanan }}</li>
        <li><strong>Alamat:</strong> {{ $booking->alamat ?? '-' }}</li>
    </ul>

    <h3>Ringkasan Biaya Perbaikan</h3>
    <ul>
        <li><strong>Total Biaya Perbaikan:</strong> {{ $booking->total_biaya_perbaikan ?? '-' }}</li>
    </ul>

    <h3>Laporan Perbaikan</h3>
    <p style="white-space:pre-wrap">{{ $booking->laporan_perbaikan ?? '-' }}</p>

    @if(!empty($booking->mechanic_note))
        <h3>Catatan Tambahan Mekanik</h3>
        <p style="white-space:pre-wrap">{{ $booking->mechanic_note }}</p>
    @endif

    <p>
        Status booking saat ini: <strong>{{ $booking->status }}</strong>.
        Silakan lanjutkan pembayaran sesuai instruksi di akun customer.
    </p>
</body>
</html>

