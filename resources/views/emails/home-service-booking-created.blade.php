<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Home Service Baru</title>
</head>
<body>
    <h2>Booking Home Service Baru Diterima</h2>
    
    <p>Ada booking home service baru yang perlu diproses:</p>
    
    <h3>Informasi Pelanggan</h3>
    <ul>
        <li><strong>Nama:</strong> {{ $booking->nama }}</li>
        <li><strong>Telepon:</strong> {{ $booking->telepon }}</li>
        <li><strong>Alamat:</strong> {{ $booking->alamat }}</li>
    </ul>

    <h3>Detail Kendaraan</h3>
    <ul>
        <li><strong>Kendaraan:</strong> {{ $booking->kendaraan }}</li>
        <li><strong>Nomor Polisi:</strong> {{ $booking->nopol }}</li>
    </ul>

    <h3>Detail Layanan</h3>
    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</li>
        <li><strong>Jenis Layanan:</strong> {{ $booking->layanan }}</li>
        <li><strong>Tanggal Layanan:</strong> {{ $booking->tanggal }}</li>
        <li><strong>Waktu Layanan:</strong> {{ $booking->waktu }}</li>
        <li><strong>Total Pembayaran:</strong> {{ $totalPembayaran }}</li>
        <li><strong>Status:</strong> {{ $booking->status }}</li>
    </ul>

    @if($booking->catatan)
    <h3>Catatan Pelanggan</h3>
    <p>{{ $booking->catatan }}</p>
    @endif

    <p>Silakan login ke dashboard admin untuk memproses booking ini.</p>
</body>
</html>
