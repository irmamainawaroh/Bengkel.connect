<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Progress Selesai</title>
</head>
<body>
    <h2>Progress Selesai</h2>

    <p>Booking berikut telah diproses menjadi selesai (biaya dikonfirmasi dan invoice sudah dikirim ke customer):</p>

    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</li>
        <li><strong>Customer:</strong> {{ $booking->nama }}</li>
        <li><strong>Total Biaya Perbaikan:</strong> {{ $booking->total_biaya_perbaikan ?? '-' }}</li>
        <li><strong>Status Saat Ini:</strong> {{ $booking->status }}</li>
    </ul>
</body>
</html>

