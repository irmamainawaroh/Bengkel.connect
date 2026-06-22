<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Booking Sukses</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body { background:#f8fafc; min-height:100vh; color:#0f172a; display:flex; flex-direction:column; }

        .header-nav {
            width:100%; padding:14px 24px; display:flex; align-items:center; justify-content:space-between;
            background:#fff; border-bottom:1px solid rgba(148,163,184,0.15);
        }

        .back-button {
            display:inline-flex; align-items:center; gap:8px; text-decoration:none;
            color:#334155; font-size:13px; font-weight:600; cursor:pointer;
            background:#fff; border:1px solid rgba(15,23,42,.12); padding:8px 14px; border-radius:10px;
        }
        .back-button:hover { background:#f1f5f9; }

        .logout-button {
            display:flex; align-items:center; gap:8px; text-decoration:none; color:#dc2626;
            font-size:13px; font-weight:600; padding:8px 14px; border-radius:10px;
            background-color:#fee2e2; transition:opacity 0.2s;
        }
        .logout-button:hover { opacity:0.9; }

        .main-container { max-width:840px; width:100%; margin:0 auto; padding:24px 18px 50px; }
        .title-section { text-align:center; margin-bottom:24px; }
        .title-section h1 { font-size:24px; font-weight:800; color:#0f172a; margin-bottom:8px; }
        .title-section p { font-size:13px; color:#64748b; }

        .card {
            background:#fff; border-radius:20px; padding:24px;
            box-shadow:0 10px 25px rgba(15,23,42,0.04);
            border:1px solid rgba(148,163,184,0.15);
        }

        .success-badge {
            width:64px; height:64px; border-radius:18px;
            background:#dcfce7; display:flex; align-items:center; justify-content:center;
            border:1px solid rgba(16,185,129,0.25);
            margin:0 auto 16px;
        }
        .success-badge i { color:#16a34a; font-size:32px; }

        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-top:18px; }
        .info {
            background:#f8fafc; border:1px solid rgba(148,163,184,0.18);
            border-radius:16px; padding:14px;
        }
        .info .label { font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:.3px; }
        .info .value { margin-top:6px; font-weight:900; color:#0f172a; font-size:14px; word-break:break-word; }

        .actions { display:flex; gap:12px; flex-wrap:wrap; margin-top:18px; }
        .btn {
            flex:1; min-width:220px;
            padding:12px 14px; border-radius:12px; border:none; cursor:pointer;
            font-size:13px; font-weight:800; display:inline-flex; align-items:center; justify-content:center; gap:8px;
            transition:opacity 0.2s;
        }
        .btn-primary { background:#16a34a; color:#fff; }
        .btn-primary:hover { opacity:0.92; }
        .btn-back { background:#e2e8f0; color:#475569; }
        .btn-back:hover { opacity:0.95; }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
            .main-container { padding:18px 14px 40px; }
        }
    </style>
</head>
<body>
    <div class="header-nav">
        <button class="back-button" onclick="history.back()">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>
        <a href="/logout" class="logout-button">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <div class="main-container">
        <div class="title-section">
            <h1>Booking Berhasil</h1>
            <p>Terima kasih. Data booking Anda berhasil dibuat.</p>
        </div>

        <div class="card">
            <div class="success-badge">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div class="info-grid">
                <div class="info">
                    <div class="label">Kode Booking</div>
                    <div class="value">{{ session('kode_booking', '-') }}</div>
                </div>
                <div class="info">
                    <div class="label">Nama</div>
                    <div class="value">{{ session('nama_booking', '-') }}</div>
                </div>
                <div class="info">
                    <div class="label">Tanggal</div>
                    <div class="value">{{ session('tanggal_booking', '-') }}</div>
                </div>
                <div class="info">
                    <div class="label">Waktu</div>
                    <div class="value">{{ session('waktu_booking', '-') }}</div>
                </div>
            </div>

            <div class="actions">
                <a class="btn btn-primary" href="{{ url('/customer/dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Buka Dashboard
                </a>
                <a class="btn btn-back" href="{{ url('/customer/riwayat-perbaikan') }}">
                    <i class="bi bi-clipboard-check"></i> Lihat Riwayat
                </a>
            </div>

            <p style="margin-top:14px; font-size:12px; color:#64748b; line-height:1.6;">
                Selanjutnya, Anda akan melihat status pengerjaan pada menu dashboard. Jika ada pembayaran yang diperlukan, sistem akan mengarahkan Anda ke halaman pembayaran.
            </p>
        </div>
    </div>
</body>
</html>

