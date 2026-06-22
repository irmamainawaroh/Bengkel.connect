<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Pembayaran QRIS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #fafafa;
            min-height: 100vh;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .header-nav {
            width: 100%;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 80px;
        }

        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #334155;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            background: none;
            border: none;
        }

        .logout-button {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #dc2626;
            font-size: 15px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 10px;
            background-color: #fee2e2;
            transition: opacity 0.2s;
        }

        .logout-button:hover,
        .back-button:hover {
            opacity: 0.8;
        }

        .main-container {
            max-width: 1100px;
            width: 100%;
            margin: auto;
            padding: 0px 40px 60px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .title-section h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .title-section p {
            font-size: 14px;
            color: #64748b;
        }

        .card {
            background: #ffffff;
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .qris-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 18px;
        }

        .qris-header i {
            color: #cc3a2b;
            font-size: 22px;
        }

        .qris-details-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px 20px;
            text-align: left;
            margin-bottom: 16px;
            font-size: 13px;
            border: 1px solid #f1f5f9;
        }

        .qris-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .qris-row:last-child {
            margin-bottom: 0;
        }

        .qris-label {
            color: #64748b;
        }

        .qris-value-price {
            color: #cc3a2b;
            font-weight: 700;
            font-size: 16px;
        }

        .qris-value-text {
            color: #1e293b;
            font-weight: 500;
        }

        .qris-instruction-text {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 15px;
        }

        .qris-qr-wrapper {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            display: inline-block;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .qris-qr-image {
            width: 190px;
            height: 190px;
            display: block;
        }

        .qris-code-string {
            background: #f1f5f9;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 11px;
            color: #64748b;
            font-family: monospace;
            word-break: break-all;
            margin-bottom: 18px;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            margin-bottom: 10px;
            transition: opacity 0.2s;
        }

        .btn-confirm {
            background: #cc3a2b;
            color: white;
        }

        .btn-cancel {
            background: #e2e8f0;
            color: #475569;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .qris-footer-note {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 10px;
        }

        .row-actions {
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="header-nav">
        <button id="backBtn" class="back-button" onclick="history.back()">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>

        <a href="/logout" class="logout-button">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <div class="main-container">
        <div class="title-section">
            <h1>Kode Bookingbooking</h1>
            <p>Berikut kode bookingbooking Anda</p>
        </div>

        <div class="card">
            <div class="qris-header">
                <i class="bi bi-receipt"></i>
                Kode Bookingbooking
            </div>

            @php
                // nilai default supaya view tetap jalan walau controller belum mengirim variabel
                $jenisLayanan = $jenisLayanan ?? (session('jenisLayanan') ?? '-');

            @endphp

            <div class="qris-details-box">
                <div class="qris-row">
                    <span class="qris-label">Kode Bookingbooking:</span>
                    <span class="qris-value-text"><?php echo e(session('kode_booking') ?? '-'); ?></span>
                </div>

                <div class="qris-row">
                    <span class="qris-label">Layanan:</span>
                    <span class="qris-value-text">{{ $jenisLayanan }}</span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Nama:</span>
                    <span class="qris-value-text"><?php echo e(session('nama_booking') ?? '-'); ?></span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Telepon:</span>
                    <span class="qris-value-text"><?php echo e(session('telepon_booking') ?? '-'); ?></span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Kendaraan:</span>
                    <span class="qris-value-text"><?php echo e(session('kendaraan_booking') ?? '-'); ?></span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Nopol:</span>
                    <span class="qris-value-text"><?php echo e(session('nopol_booking') ?? '-'); ?></span>
                </div>
            </div>


            <div class="qris-footer-note">
                Simpan kode bookingbooking ini. Setelah pembayaran, mohon tunggu konfirmasi dari admin.
            </div>


            <div class="row-actions" style="margin-top: 14px;">
                <button class="btn btn-cancel" onclick="history.back()">
                    Kembali
                </button>
            </div>
        </div>

    </div>
</body>

</html>

