<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tagihan Lunas - {{ $booking->kode_booking }}</title>
</head>
<body>
    <h2>Tagihan Anda Sudah Lunas ✅</h2>

    <p>Terima kasih. Pembayaran pelunasan untuk booking berikut telah dikonfirmasi oleh admin:</p>

    <ul>
        <li><strong>Kode Booking:</strong> {{ $booking->kode_booking }}</li>
        <li><strong>Nama:</strong> {{ $booking->nama }}</li>
        <li><strong>Layanan:</strong> {{ $booking->layanan }}</li>
        <li><strong>Status:</strong> {{ $booking->status }}</li>
    </ul>

@php
        $total = $booking->total_biaya_perbaikan ?? $booking->total_biaya ?? null;
    @endphp

    @if(!empty($total))
        <p><strong>Total Pembayaran:</strong> {{ $total }}</p>
    @endif

    <p>Jika Anda memiliki pertanyaan, silakan hubungi Bengkel Connect.</p>
</body>
</html>

