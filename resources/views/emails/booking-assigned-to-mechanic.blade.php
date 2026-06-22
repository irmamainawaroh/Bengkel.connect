<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Baru - Booking Diterima</title>
</head>
<body>
    <h2>Tugas Baru Diterima</h2>
    
    <p>Anda memiliki tugas baru (Home Service) yang perlu dikerjakan:</p>
    
    <h3>Detail Booking</h3>
    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</li>
        <li><strong>Pelanggan:</strong> {{ $booking->nama }}</li>
        <li><strong>Telepon:</strong> {{ $booking->telepon }}</li>
        <li><strong>Alamat:</strong> {{ $booking->alamat }}</li>
    </ul>

    <h3>Detail Kendaraan</h3>
    <ul>
        <li><strong>Jenis:</strong> {{ $booking->kendaraan }}</li>
        <li><strong>Nomor Polisi:</strong> {{ $booking->nopol }}</li>
    </ul>

    <h3>Layanan yang Diminta</h3>
    <ul>
        <li><strong>Jenis Layanan:</strong> {{ $booking->layanan }}</li>
        <li><strong>Tanggal:</strong> {{ $booking->tanggal }}</li>
        <li><strong>Waktu:</strong> {{ $booking->waktu }}</li>
    </ul>

    @if($booking->catatan)
    <h3>Catatan</h3>
    <p>{{ $booking->catatan }}</p>
    @endif

    <p>Silakan login ke dashboard mekanik untuk melihat detail lengkap dan update status pekerjaan.</p>
</body>
</html>
