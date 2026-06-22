<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Pembayaran QRIS Home Service</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8fafc;
            min-height: 100vh;
            color: #1e293b;
        }

        .header-nav {
            width: 100%;
            padding: 20px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-button,
        .logout-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            color: #334155;
        }

        .logout-button {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px 16px;
            border-radius: 12px;
        }

        .main-container {
            max-width: 980px;
            margin: auto;
            padding: 0px 24px 40px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .title-section h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .title-section p {
            color: #64748b;
            font-size: 14px;
        }

        .card {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(148, 163, 184, 0.12);
            margin-bottom: 24px;
        }

        .qris-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #0f172a;
        }

        .qris-header i {
            color: #ef4444;
            font-size: 24px;
        }

        .qris-details-box {
            background: #f8fafc;
            border-radius: 18px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .qris-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        }

        .qris-row:last-child {
            border-bottom: none;
        }

        .qris-label {
            color: #475569;
        }

        .qris-value-text {
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }

        .qris-value-price {
            color: #dc2626;
            font-weight: 700;
        }

        .qris-qr-wrapper {
            display: grid;
            place-items: center;
            background: white;
            padding: 24px;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .qris-qr-image {
            width: 280px;
            height: 280px;
            object-fit: contain;
            border-radius: 18px;
            display: block;
        }

        .qris-code-string {
            margin-top: 16px;
            padding: 12px 16px;
            background: #f1f5f9;
            border-radius: 12px;
            font-family: monospace;
            color: #475569;
            word-break: break-all;
            font-size: 13px;
        }

        .qris-instruction {
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
            margin-top: 12px;
        }

        .btn-primary {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            border: none;
            background: #0f172a;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary:hover {
            opacity: 0.95;
        }

        .alert-note {
            margin-top: 14px;
            padding: 16px;
            border-radius: 16px;
            background: #e2e8f0;
            color: #334155;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="header-nav">
        <button class="back-button" onclick="history.back()">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </button>
        <a href="/logout" class="logout-button">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <div class="main-container">
        @php
            $isFinalPayment = isset($booking) && $booking->status === 'menunggu_pembayaran_final';
            $jenisLayanan = isset($booking) ? $booking->layanan : session('jenisLayanan') ?? '-';
            $totalPembayaran = isset($booking) && $booking->total_biaya_perbaikan ?
                'Rp ' . number_format($booking->total_biaya_perbaikan, 0, ',', '.') :
                (session('totalPembayaran') ?? 'Rp 250.000');
            $kodeBooking = isset($booking) ? $booking->kode_booking : session('kode_booking') ?? '-';
            $namaBooking = isset($booking) ? $booking->nama : session('nama_booking') ?? '-';
            $teleponBooking = isset($booking) ? $booking->telepon : session('telepon_booking') ?? '-';
            $kendaraanBooking = isset($booking) ? $booking->kendaraan : session('kendaraan_booking') ?? '-';
            $nopolBooking = isset($booking) ? $booking->nopol : session('nopol_booking') ?? '-';
            $qrisData = $isFinalPayment ? 'BengkelConnect-HomeService-FINAL-' . $kodeBooking : 'BengkelConnect-HomeService-DP';
        @endphp

        <div class="title-section">
            <h1>Pembayaran QRIS Home Service</h1>
            <p>Silakan scan QRIS statis berikut untuk melakukan pembayaran {{ $isFinalPayment ? 'total akhir' : 'DP' }}.</p>
        </div>

        <div class="card">
            <div class="qris-header">
                <i class="bi bi-qr-code-scan"></i>
                QRIS Statis Home Service
            </div>

            <div class="qris-details-box">
                <div class="qris-row">
                    <span class="qris-label">Kode Booking</span>
                    <span class="qris-value-text">{{ $kodeBooking }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Layanan</span>
                    <span class="qris-value-text">{{ $jenisLayanan }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Nama</span>
                    <span class="qris-value-text">{{ $namaBooking }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Telepon</span>
                    <span class="qris-value-text">{{ $teleponBooking }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Kendaraan</span>
                    <span class="qris-value-text">{{ $kendaraanBooking }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Nopol</span>
                    <span class="qris-value-text">{{ $nopolBooking }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Total Pembayaran</span>
                    <span class="qris-value-text qris-value-price">{{ $totalPembayaran }}</span>
                </div>
            </div>

            <div class="qris-qr-wrapper">
                <img class="qris-qr-image"
                    src="{{ asset('images/qris.png') }}"
                    alt="QRIS Statis Home Service">
                <div class="qris-code-string">{{ $qrisData }}</div>
            </div>

            <p class="qris-instruction">
                QRIS ini statis. Gunakan scanner QRIS di aplikasi dompet digital Anda dan masukkan kode booking saat diminta.
                Setelah pembayaran, simpan bukti atau tangkapan layar untuk konfirmasi.
            </p>

            <div class="alert-note" style="background:#eef2ff; color:#1e3a8a; border:1px solid #c7d2fe;">
                Silakan scan QRIS untuk melakukan pembayaran. Setelah pembayaran selesai, mohon tunggu konfirmasi dari admin.
            </div>

            @php
                // Agar tombol selalu bisa membuka halaman upload bukti,
                // gunakan kode booking dari $booking bila ada, jika tidak fallback ke session.
                $uploadKodeBooking = isset($booking) && $booking ? $booking->kode_booking : (session('kode_booking') ?? null);
            @endphp

            @if(!empty($uploadKodeBooking))
                <a href="{{ route('booking.showUpload', ['kode_booking' => $uploadKodeBooking]) }}" class="btn-primary" style="margin-top: 18px; display: inline-block; text-align: center;">
                    Upload Bukti Pembayaran
                </a>
            @endif

            @if(session('bukti_uploaded'))
                <div class="alert-note" style="background:#dcfce7; color:#166534; border:1px solid #bbf7d0; margin-top: 12px;">
                    Pembayaran sudah diterima. Data booking akan diproses oleh admin.
                </div>
            @endif
        </div>
    </div>
</body>

</html>
