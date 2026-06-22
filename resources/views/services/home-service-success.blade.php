<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Home Service Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .container {
            max-width: 840px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .card {
            border-radius: 24px;
            padding: 32px;
            border: 1px solid rgba(148, 163, 184, 0.18);
            background: #ffffff;
            box-shadow: 0 24px 40px rgba(15, 23, 42, 0.08);
        }
        .title-section {
            text-align: center;
            margin-bottom: 24px;
        }
        .title-section h1 {
            font-size: 30px;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .title-section p {
            color: #475569;
            font-size: 15px;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 18px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }
        .info-card {
            background: #f8fafc;
            border-radius: 18px;
            padding: 18px;
            border: 1px solid rgba(226, 232, 240, 1);
        }
        .info-card h3 {
            margin-bottom: 12px;
            font-size: 16px;
            color: #0f172a;
        }
        .info-item {
            margin-bottom: 10px;
            font-size: 14px;
            color: #475569;
        }
        .info-item span {
            display: block;
            margin-top: 4px;
            color: #1e293b;
            font-weight: 600;
        }
        .note-box {
            background: #eef2ff;
            color: #1e3a8a;
            padding: 20px;
            border-radius: 18px;
            border: 1px solid #c7d2fe;
            margin-top: 20px;
            font-size: 14px;
        }
        .btn-group {
            margin-top: 28px;
            display: grid;
            gap: 12px;
        }
        .btn-primary {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            border: none;
            background: #0f172a;
            color: white;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            justify-content: center;
        }
        .btn-secondary {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            border: 1px solid rgba(148, 163, 184, 0.8);
            background: white;
            color: #334155;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="title-section">
                <h1>Booking Home Service Berhasil</h1>
                <p>Terima kasih, permintaan layanan Anda telah diterima. Simpan informasi di bawah ini untuk referensi.</p>
            </div>

            <div class="status-badge">
                <i class="bi bi-check-circle-fill"></i>
                Booking terkirim, menunggu konfirmasi admin
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <h3>Detail Booking</h3>
                    <div class="info-item">Kode Booking<span>{{ $kodeBooking }}</span></div>
                    <div class="info-item">Layanan<span>{{ $jenisLayanan }}</span></div>
                    <div class="info-item">Total Pembayaran<span>{{ $totalPembayaran }}</span></div>
                </div>
                <div class="info-card">
                    <h3>Jadwal Home Service</h3>
                    <div class="info-item">Tanggal<span>{{ $tanggal }}</span></div>
                    <div class="info-item">Waktu<span>{{ $waktu }}</span></div>
                </div>
                <div class="info-card">
                    <h3>Kontak Pelanggan</h3>
                    <div class="info-item">Nama<span>{{ $nama }}</span></div>
                    <div class="info-item">Telepon<span>{{ $telepon }}</span></div>
                </div>
                <div class="info-card">
                    <h3>Alamat Home Service</h3>
                    <div class="info-item">Alamat<span>{{ $alamat }}</span></div>
                </div>

                <div class="info-card">
                    <h3>Kendaraan</h3>
                    <div class="info-item">Jenis Kendaraan<span>{{ $kendaraan }}</span></div>
                    <div class="info-item">Nomor Polisi<span>{{ $nopol }}</span></div>
                </div>

            </div>

            <div class="info-card">
                <h3>Catatan</h3>
                <p style="color: #334155; line-height: 1.7;">{{ $catatan }}</p>
            </div>

            <div class="note-box">
                Simpan kode booking ini dan tunggu konfirmasi lebih lanjut dari admin. Pastikan nomor telepon aktif untuk notifikasi.
            </div>

            <div class="btn-group">
                <a href="/payment/qris" class="btn-secondary">
                    Lanjut Pembayaran DP
                </a>

                <a href="/booking/upload-bukti?kode_booking={{ $kodeBooking }}" class="btn-primary">
                    Upload Bukti DP
                </a>
            </div>



        </div>
    </div>
</body>
</html>
