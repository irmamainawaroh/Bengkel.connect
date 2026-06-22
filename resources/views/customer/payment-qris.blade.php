<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Payment QRIS</title>
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
        .logout-button {
            display:flex; align-items:center; gap:8px; text-decoration:none; color:#dc2626;
            font-size:13px; font-weight:600; padding:8px 14px; border-radius:10px;
            background-color:#fee2e2; transition:opacity 0.2s;
        }

        .main-container { max-width:840px; width:100%; margin:0 auto; padding:24px 18px 50px; }
        .title-section { text-align:center; margin-bottom:24px; }
        .title-section h1 { font-size:24px; font-weight:800; color:#0f172a; margin-bottom:6px; }
        .title-section p { font-size:13px; color:#64748b; }

        .card {
            background:#fff; border-radius:20px; padding:24px;
            box-shadow:0 10px 25px rgba(15,23,42,0.04);
            border:1px solid rgba(148,163,184,0.15);
        }

        .qris-wrapper { background:#111827; border-radius:16px; padding:16px; text-align:center; color:#fff; }
        .qris-static-img { width:100%; max-width:260px; height:auto; display:block; margin:0 auto 12px; border-radius:12px; background:#ffffff; padding:6px; }

        .meta { font-size:11px; color:#9ca3af; font-weight:500; line-height:1.4; }

        .instruction-list { margin-top:16px; padding-left:18px; font-size:12px; color:#475569; line-height:1.6; }
        .instruction-list li { margin-bottom:6px; }

        .btn-row { display:flex; gap:12px; flex-wrap:wrap; margin-top:18px; }
        .btn { flex:1; min-width:220px; padding:12px 14px; border-radius:12px; border:none; cursor:pointer;
              font-size:13px; font-weight:800; display:inline-flex; align-items:center; justify-content:center; gap:8px; transition:opacity 0.2s; }
        .btn-primary { background:#cc3a2b; color:#fff; }
        .btn-primary:hover { opacity:0.92; }
        .btn-back { background:#e2e8f0; color:#475569; }
        .btn-back:hover { opacity:0.95; }

        .chip {
            display:inline-flex; align-items:center; gap:8px;
            background:#f8fafc; border:1px solid rgba(148,163,184,.2);
            padding:10px 12px; border-radius:14px; margin-top:14px;
            color:#0f172a; font-weight:800; font-size:12px;
        }
        .chip span { color:#64748b; font-weight:700; }

        @media (max-width:768px) {
            .btn-row { flex-direction:column; }
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
            <h1>QRIS Pembayaran</h1>
            <p>Scan QRIS di bawah untuk melakukan pembayaran, lalu lanjut upload bukti.</p>
        </div>

        <div class="card">
            <div class="qris-wrapper">
                <img class="qris-static-img" src="{{ asset('images/qris.png') }}" alt="QRIS Pembayaran">
                <div class="meta">
                    <strong style="color:#fff; font-size:12px; display:block; margin-bottom:2px;">BENGKEL CONNECT</strong>
                    <span>NMID: ID102026142890</span>
                    @if(isset($kode_booking))
                        <span style="font-size:9px; color:#6b7280;">ID Booking: {{ $kode_booking }}</span>
                    @endif
                </div>
            </div>

            @if(isset($kode_booking))
                <div class="chip">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Kode Booking:</span> {{ $kode_booking }}
                </div>
            @endif

            <ul class="instruction-list">
                <li>Scan QRIS menggunakan Mobile Banking / E-Wallet.</li>
                <li>Pastikan nominal sesuai tagihan.</li>
                <li>Setelah bayar, lanjut upload bukti pembayaran di halaman upload.</li>
            </ul>

            <div class="btn-row">
                <a class="btn btn-primary" href="{{ isset($kode_booking) ? route('booking.showUploadPelunasan', ['kode_booking' => $kode_booking]) : '#' }}">
                    <i class="bi bi-cloud-arrow-up"></i> Upload Bukti
                </a>
                <button class="btn btn-back" type="button" onclick="history.back()">
                    <i class="bi bi-x-circle"></i> Nanti dulu
                </button>
            </div>
        </div>
    </div>
</body>
</html>

