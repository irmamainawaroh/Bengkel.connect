<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Perbaikan - Bengkel Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        
        body { font-family:'Poppins', sans-serif; background:#f1f5f9; color:#0f172a; line-height:1.5; } 
        
        .page-wrap { max-width:660px; margin:20px auto; padding:0 14px; }
        .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .topbar h1 { font-size:18px; font-weight:800; color:#1e293b; }
        
        .btn-back { display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:7px 12px; border-radius:12px; border:1px solid rgba(148,163,184,.3); background:#e2e8f0; color:#334155; text-decoration:none; font-weight:700; font-size:12px; transition: background 0.2s; }
        .btn-back:hover { background:#cbd5e1; }
        
        .invoice-card { background:#faf9f5; border-radius:18px; box-shadow:0 16px 36px rgba(15,23,42,.05); border:1px solid #e2e4e9; overflow:hidden; }
        
        .invoice-header { padding:20px 22px; border-bottom:1px solid #e2e4e9; }
        .invoice-brand { display:flex; flex-wrap:wrap; align-items:flex-start; justify-content:space-between; gap:12px; }
        .invoice-brand h2 { font-size:17px; letter-spacing:1px; margin-bottom:5px; color:#1e293b; }
        .invoice-brand p { color:#64748b; font-size:12px; }
        
        .status-chip { display:inline-flex; align-items:center; gap:6px; padding:7px 12px; border-radius:999px; background:#fef3c7; color:#92400e; font-weight:800; font-size:11px; }
        .status-chip.status-warning { background:#fee2e2; color:#991b1b; border:1px solid rgba(239,68,68,.2); }
        
        .row { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; margin-top:20px; }
        .row-item { background:#f1f3f7; border-radius:14px; padding:16px; border:1px solid #e2e8f0; }
        .row-item span { display:block; color:#64748b; font-size:11px; margin-bottom:5px; font-weight:700; }
        .row-item strong { display:block; font-size:13px; color:#0f172a; font-weight:800; }
        
        .section { padding:20px 22px; }
        .section-title { font-size:14px; font-weight:900; margin-bottom:12px; color:#1e293b; }
        
        .invoice-table { width:100%; border-collapse:collapse; margin-bottom:14px; }
        .invoice-table th, .invoice-table td { padding:10px 12px; text-align:left; border-bottom:1px solid #e2e4e9; }
        .invoice-table th { font-size:12px; color:#475569; font-weight:700; }
        .invoice-table td { color:#0f172a; font-weight:700; }
        .invoice-table td.amount { text-align:right; font-feature-settings:'tnum'; }
        
        .invoice-total { display:flex; justify-content:space-between; align-items:center; padding:12px 0; font-size:14px; font-weight:900; border-top:2px solid #cbd5e1; color:#0f172a; }
        
        .payment-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-top:8px; }
        
        .payment-box { display:flex; flex-direction:column; justify-content:space-between; background:#f1f3f7; border-radius:14px; padding:16px; border:1px solid #e2e8f0; }
        .payment-box h3 { margin-bottom:10px; font-size:13px; color:#0f172a; }
        .payment-box p, .payment-box li { color:#475569; font-size:12px; margin-bottom:6px; }
        .payment-box ul { list-style:none; padding-left:0; margin:0 0 12px 0; }
        .payment-box li::before { content:'•'; margin-right:8px; color:#0f172a; }
        
        .btn-upload { display:inline-flex; align-items:center; justify-content:center; width:100%; padding:10px 12px; margin-top:auto; border-radius:12px; color:#fff; text-decoration:none; font-size:12px; font-weight:700; border:none; cursor:pointer; text-align:center; transition:opacity 0.2s; }
        .btn-upload:hover { opacity:0.9; }
        
        .qris-card { background:#111827; color:#f8fafc; border-radius:18px; padding:16px; display:grid; grid-template-columns:1fr auto; gap:14px; align-items:center; }
        .qris-image { width:120px; height:120px; object-fit:contain; border-radius:14px; border:2px solid rgba(255,255,255,.12); background:#fdfefe; }
        .qris-info { display:flex; flex-direction:column; gap:5px; }
        .qris-info span { font-size:11px; color:#e2e8f0; }
        
        .note-card { background:#f1f3f7; border-radius:14px; padding:16px; border:1px solid #e2e8f0; margin-top:14px; }
        .note-card p { white-space:pre-line; color:#475569; font-size:12px; }
        .note-card strong { color:#0f172a; font-weight:800; }
        
        .footer-note { padding:18px 22px 20px; background:#ecfdf5; color:#166534; font-size:13px; font-weight:800; border-top:1px solid rgba(16,185,129,.15); }
        @media (max-width:900px) { .row, .payment-grid { grid-template-columns:1fr; } .invoice-brand { flex-direction:column; } }
    </style>
</head>
<body>
    @php
        $recommendedParts = $booking->recommended_parts ?? [];
        if (is_string($recommendedParts)) {
            $decoded = json_decode($recommendedParts, true);
            $recommendedParts = is_array($decoded) ? $decoded : [];
        }

        $mainServiceFee = 100000;
        $parts = [];
        $partsTotal = 0;

        foreach ((array)$recommendedParts as $part) {
            if (is_array($part)) {
                $name = $part['name'] ?? ($part[0] ?? 'Item Tambahan');
                $qty = intval($part['qty'] ?? 1);
                $price = floatval($part['price'] ?? 0);
            } else {
                $name = (string)$part;
                $qty = 1;
                $price = 0;
            }

            $subtotal = $price * max($qty, 1);
            $parts[] = ['name' => $name, 'qty' => $qty, 'price' => $price, 'subtotal' => $subtotal];
            $partsTotal += $subtotal;
        }

        $invoiceNumber = 'INV/' . ($booking->created_at ? $booking->created_at->format('Ymd') : date('Ymd')) . '/' . strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $booking->kode_booking), -4));
        $invoiceDate = $booking->created_at ? $booking->created_at->format('d F Y') : date('d F Y');
        $customerName = $booking->nama ?? 'Pelanggan';
        $mechanicName = optional($booking->mechanic)->name ?? 'Agus Saputra';
        $vehicle = $booking->kendaraan ?? '-';
        $serviceLabel = $booking->layanan ?? 'Home Service';

        $rawStatus = strtolower(trim($booking->status ?? ''));
        
        if (empty($rawStatus) || $rawStatus === 'menunggu_pembayaran') {
            $statusText = 'MENUNGGU PEMBAYARAN FINAL';
        } elseif ($rawStatus === 'butuh_konfirmasi_biaya' || $rawStatus === 'menunggu_konfirmasi_biaya') {
            $statusText = 'BUTUH KONFIRMASI BIAYA';
        } else {
            $statusText = strtoupper(str_replace('_', ' ', $rawStatus));
        }

        $formatted = fn($value) => 'Rp ' . number_format($value, 0, ',', '.');
        
        $displayTotal = in_array($rawStatus, ['butuh_konfirmasi_biaya', 'menunggu_konfirmasi_biaya', 'menunggu_pembayaran_lunas', 'menunggu_konfirmasi_bukti_final']) 
            ? ($mainServiceFee + $partsTotal) 
            : 100000;

        $isDp = in_array($rawStatus, ['menunggu_pembayaran', 'menunggu_konfirmasi_bukti']);
        $qrisCode = $isDp
            ? ('BengkelConnect-HomeService-DP-' . ($booking->kode_booking ?? ''))
            : ('BengkelConnect-HomeService-FINAL-' . ($booking->kode_booking ?? ''));
    @endphp

    {{-- Logika Pemicu Notifikasi Pop-up --}}
    @php
        $rawStatusForNotify = strtolower(trim((string)($booking->status ?? '')));
        // Memastikan status estimasi baru dan pembayaran masuk ke dalam kondisi pemicu popup
        $shouldShowInvoicePopup = in_array($rawStatusForNotify, ['menunggu_pembayaran_lunas', 'selesai', 'butuh_konfirmasi_biaya', 'menunggu_konfirmasi_biaya']);
        $hasInvoiceData = !empty($booking->invoice_sent_at) || !empty($booking->total_biaya_perbaikan) || $partsTotal > 0;
    @endphp

    @if($shouldShowInvoicePopup && $hasInvoiceData)
        <div style="position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:99999; display:flex; align-items:center; justify-content:center; padding:16px;" id="invoice-popup-overlay">
            <div style="width:100%; max-width:520px; background:#fff; border-radius:18px; border:1px solid #e2e8f0; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden;">
                <div style="padding:16px 18px; background:#ecfdf5; border-bottom:1px solid rgba(16,185,129,.2); display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:900; color:#065f46; font-size:14px;">
                            📩 {{ in_array($rawStatusForNotify, ['butuh_konfirmasi_biaya', 'menunggu_konfirmasi_biaya']) ? 'Rincian Estimasi Biaya' : 'Nota Tagihan Masuk' }}
                        </div>
                        <div style="color:#047857; font-weight:800; font-size:12px; margin-top:2px;">Booking: {{ $booking->kode_booking }}</div>
                    </div>
                    <button type="button" id="invoice-popup-close" style="border:none; background:#e2e8f0; color:#334155; font-weight:900; padding:8px 12px; border-radius:10px; cursor:pointer;">Tutup</button>
                </div>
                <div style="padding:16px 18px;">
                    <div style="font-size:13px; color:#0f172a; font-weight:900; margin-bottom:8px;">
                        {{ in_array($rawStatusForNotify, ['butuh_konfirmasi_biaya', 'menunggu_konfirmasi_biaya']) ? 'Estimasi rincian biaya perbaikan kendaraan Anda telah diperbarui.' : 'Tagihan perbaikan Anda sudah dikirim oleh admin.' }}
                    </div>
                    <div style="font-size:12px; color:#475569; font-weight:700; line-height:1.5;">
                        Silakan cek rincian nota dan lakukan aksi konfirmasi atau pembayaran sesuai instruksi di bawah.
                    </div>
                </div>
                <div style="padding:16px 18px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px;">
                    <a href="#" id="invoice-popup-go" style="padding:10px 14px; border-radius:12px; background:#16a34a; color:#fff; font-weight:900; text-decoration:none; font-size:12px;">Lihat Rincian</a>
                </div>
            </div>
        </div>

        <script>
            (function(){
                const overlay = document.getElementById('invoice-popup-overlay');
                const closeBtn = document.getElementById('invoice-popup-close');
                const goBtn = document.getElementById('invoice-popup-go');
                if(!overlay) return;

                const key = 'invoice_popup_seen_{{ $booking->kode_booking }}_' + '{{ $rawStatusForNotify }}';
                try{
                    if(localStorage.getItem(key) === '1'){
                        overlay.style.display = 'none';
                    }
                }catch(e){}

                function closePopup(){
                    try{ localStorage.setItem(key,'1'); }catch(e){}
                    overlay.style.display='none';
                }

                closeBtn && closeBtn.addEventListener('click', closePopup);
                overlay && overlay.addEventListener('click', function(e){
                    if(e.target === overlay) closePopup();
                });
                goBtn && goBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    closePopup();
                    window.scrollTo({ top: document.querySelector('.payment-grid').offsetTop - 20, behavior: 'smooth' });
                });
            })();
        </script>
    @endif

    <div class="page-wrap">
        <div class="topbar">
            <a href="/customer/dashboard" class="btn-back">← Kembali ke Dashboard</a>
            <h1>Invoice Perbaikan</h1>
        </div>

        <div class="invoice-card">
            <div class="invoice-header">
                <div class="invoice-brand">
                    <div>
                        <h2>BENGKEL CONNECT</h2>
                        <p>Sistem Tata Kelola & Solusi Otomotif Terintegrasi</p>
                    </div>
                    <div class="status-chip {{ in_array($rawStatus, ['butuh_konfirmasi_biaya', 'menunggu_konfirmasi_biaya']) ? 'status-warning' : '' }}">
                        📢 {{ $statusText }}
                    </div>
                </div>

                <div class="row" style="margin-top:28px;">
                    <div class="row-item">
                        <span>No. Nota</span>
                        <strong>{{ $invoiceNumber }}</strong>
                    </div>
                    <div class="row-item">
                        <span>Tanggal</span>
                        <strong>{{ $invoiceDate }}</strong>
                    </div>
                    <div class="row-item">
                        <span>Pelanggan</span>
                        <strong>{{ $customerName }}</strong>
                    </div>
                    <div class="row-item">
                        <span>Mekanik</span>
                        <strong>{{ $mechanicName }}</strong>
                    </div>
                    <div class="row-item">
                        <span>Unit Mobil</span>
                        <strong>{{ $vehicle }}</strong>
                    </div>
                    <div class="row-item">
                        <span>Layanan</span>
                        <strong>{{ $serviceLabel }}</strong>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">RINCIAN JASA & SUKU CADANG</div>

                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th style="width:8%;">No.</th>
                            <th>Deskripsi</th>
                            <th style="width:18%;">Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td>Jasa {{ $serviceLabel }} (Layanan Utama)</td>
                            <td class="amount">{{ $formatted($mainServiceFee) }}</td>
                        </tr>
                        @foreach($parts as $index => $part)
                            <tr>
                                <td>{{ $index + 2 }}.</td>
                                <td>{{ $part['name'] }}{{ $part['qty'] > 1 ? ' x'.$part['qty'] : '' }} {{ strlen($part['name']) ? '(⚠️ Temuan Mekanik)' : '' }}</td>
                                <td class="amount">{{ $formatted($part['subtotal']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="invoice-total">
                    <span>TOTAL TAGIHAN AKHIR</span>
                    <span>{{ $formatted($displayTotal) }}</span>
                </div>
            </div>

            <div class="section">
                <div class="payment-grid">
                    @if(empty($booking->invoice_sent_at) && empty($booking->total_biaya_perbaikan) && !in_array($rawStatus, ['butuh_konfirmasi_biaya', 'menunggu_konfirmasi_biaya']))
                        <div style="grid-column: 1 / -1; background:#fef2f2; border:1px solid rgba(239,68,68,.25); padding:14px; border-radius:12px; margin-bottom:12px; font-weight:900; color:#991b1b;">
                            Nota invoice belum siap untuk ditampilkan.
                        </div>
                    @endif
                    <div class="payment-box">
                        <div>
                            <h3>💳 OPSI PEMBAYARAN RESMI BENGKEL</h3>
                            <ul>
                                <li>Pembayaran Tunai langsung ke kasir/mekanik.</li>
                                <li>Transfer Bank: Mandiri VA <strong>8877082606041122</strong> a.n Bengkel Connect.</li>
                                <li>QRIS Instan: Scan kode QR di sebelah kanan via E-Wallet/m-Banking.</li>
                            </ul>
                        </div>

                        @php
                            $status = strtolower(trim($booking->status ?? ''));
                            $showUploadPelunasan = (empty($status) || $status === 'menunggu_pembayaran_final' || $status === 'menunggu_pembayaran' || $status === 'menunggu_pembayaran_lunas');
                            $isVerifikasi = in_array($status, ['menunggu_konfirmasi_bukti', 'menunggu_konfirmasi_bukti_final']);
                        @endphp

                        @if($status === 'menunggu_konfirmasi_biaya' || $status === 'butuh_konfirmasi_biaya')
                            <form method="POST" action="{{ url('/customer/booking/'.$booking->kode_booking.'/setujui-biaya') }}">
                                @csrf
                                <button type="submit" class="btn-upload" style="background:#dc2626;">
                                    ✔️ Setujui Estimasi Biaya
                                </button>
                            </form>
                        @elseif($showUploadPelunasan)
                            <p style="margin-top:10px; margin-bottom:12px; color:#475569; font-size:12px; font-weight:800;">
                                Tahap: <span style="color:#166534;">Menunggu Pelunasan</span>
                            </p>
                            <a href="{{ route('booking.payment-qris-pelunasan', ['kode_booking' => $booking->kode_booking]) }}" class="btn-upload" style="background:#16a34a;">
                                📢 Bayar Pelunasan (QRIS) & Upload
                            </a>
                        @elseif($isVerifikasi)
                            <button class="btn-upload" style="background:#64748b; cursor:not-allowed;" disabled>
                                ⏳ Bukti Sedang Diverifikasi Admin
                            </button>
                        @else
                            <button class="btn-upload" style="background:#1e293b; cursor:not-allowed;" disabled>
                                ✔️ Transaksi Selesai
                            </button>
                        @endif
                    </div>

                    <div class="qris-card">
                        <div>
                            <img class="qris-image" src="{{ !empty($booking->qris_path) ? asset('storage/' . $booking->qris_path) : asset('images/qris.png') }}" alt="QRIS Pembayaran">
                            @if(($isDp || true) && empty($booking->qris_path))
                                <div style="margin-top:10px; font-size:12px; color:#e5e7eb; font-weight:800;">
                                    Kode Pelunasan: <span style="color:#fef3c7;">{{ $qrisCode }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="qris-info">
                            <span>GPN / NATIVE</span>
                            <span>BENGKEL CONNECT</span>
                            <span>NMID: ID102026142890</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="note-card">
                    <strong>CATATAN REKOMENDASI MEKANIK:</strong>
                    <p>{{ $booking->mechanic_note ?? 'Tidak ada catatan tambahan.' }}</p>
                </div>
            </div>

            <div class="footer-note">
                Terima kasih atas kepercayaan Anda menggunakan layanan prima Bengkel Connect.
            </div>
        </div>
    </div>
</body>
</html>