<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembayaran Booking Baru</title>
</head>
<body>
    <h2>Bukti Pembayaran Baru Diterima</h2>
    <p>Detail booking:</p>
    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</li>
        <li><strong>Nama:</strong> {{ $booking->nama }}</li>
        <li><strong>Telepon:</strong> {{ $booking->telepon }}</li>
        <li><strong>Kendaraan:</strong> {{ $booking->kendaraan }}</li>
        <li><strong>Nopol:</strong> {{ $booking->nopol }}</li>
        <li><strong>Layanan:</strong> {{ $booking->layanan }}</li>
        <li><strong>Tanggal:</strong> {{ $booking->tanggal }}</li>
        <li><strong>Waktu:</strong> {{ $booking->waktu }}</li>
        <li><strong>Alamat:</strong> {{ $booking->alamat ?? 'Tidak ada' }}</li>
        <li><strong>Status Saat Ini:</strong> {{ $booking->status }}</li>
    </ul>
    
    <h3>Lampiran:</h3>
    <ul>
        <li><strong>Bukti Pembayaran:</strong> Terlampir dalam email ini</li>
        @if($qrisPath)
        <li><strong>QRIS Referensi:</strong> Terlampir dalam email ini (file: QRIS-{{ $booking->kode_booking }}.png)</li>
        @endif
    </ul>
    
    <p>Silakan periksa lampiran bukti pembayaran dan QRIS referensi, kemudian verifikasi booking ini.</p>
</body>
</html>
