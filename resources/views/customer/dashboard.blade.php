<?php
// Menangani logic pasca form disubmit (Konfirmasi Booking)
$showPaymentModal = false;
$totalPembayaran = "Rp 250.000";
$jenisLayanan = "";
$serviceType = "";
$isHomeService = false;

if (!isset($showRepairComplete)) {
    $showRepairComplete = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data input
    $layanan = isset($_POST['layanan']) ? $_POST['layanan'] : '';
    $serviceType = isset($_POST['service_type']) ? $_POST['service_type'] : '';
    $isHomeService = $serviceType === 'home_service';

    // Skenario simulasi harga berdasarkan jenis layanan
    if (strpos($layanan, 'AC') !== false) {
        $totalPembayaran = "Rp 350.000";
    } elseif (strpos($layanan, 'Mesin') !== false) {
        $totalPembayaran = "Rp 500.000";
    } else {
        $totalPembayaran = "Rp 250.000"; // Default sesuai gambar
    }

    $jenisLayanan = htmlspecialchars($layanan);

    // Trigger variabel untuk memunculkan modal QRIS saat halaman memuat kembali
    $showPaymentModal = true;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Dashboard</title>

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

        /* =========================
            NAVIGATION HEADER
        ========================== */
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

        .logout-button:hover, .back-button:hover {
            opacity: 0.8;
        }

        /* =========================
            MAIN LAYOUT
        ========================== */
        .main-container {
            max-width: 1100px;
            width: 100%;
            margin: auto;
            padding: 0px 40px 60px;
        }

        .page-view {
            display: none;
        }

        .page-view.active {
            display: block;
        }

        .title-section {
            text-align: center;
            margin-bottom: 45px;
        }

        .title-section h1 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .title-section p {
            font-size: 16px;
            color: #64748b;
        }

        /* =========================
            CARDS & MENU GRID
        ========================== */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            max-width: 900px;
            margin: 0 auto;
        }

        .base-card {
            background: white;
            border-radius: 20px;
            padding: 40px 35px;
            text-align: left;
            text-decoration: none;
            color: #334155;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.01);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .base-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
        }

        .icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .bg-red { background-color: #fee2e2; color: #dc2626; }
        .bg-green { background-color: #dcfce7; color: #16a34a; }

        .base-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .base-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .card-features {
            list-style: none;
            margin-top: auto;
        }

        .card-features li {
            font-size: 14px;
            color: #334155;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-features li::before {
            content: "•";
            font-size: 20px;
            line-height: 1;
        }

        .list-red li::before { color: #dc2626; }
        .list-green li::before { color: #16a34a; }

        /* =========================
            FORM TEMPLATE STYLING
        ========================== */
        .form-layout-container {
            max-width: 520px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 28px;
            padding: 35px 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .form-header-box {
            text-align: left;
            margin-bottom: 25px;
        }

        .form-header-box h2 {
            font-size: 22px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 4px;
        }

        .form-header-box p {
            font-size: 13px;
            color: #6c757d;
        }

        .notification-banner {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-120%);
            background: linear-gradient(90deg, #fef3c7, #dcfce7);
            color: #0f172a;
            border: 1px solid #d9f99d;
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.18);
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            max-width: 700px;
            opacity: 0;
            transition: transform 0.35s ease, opacity 0.35s ease;
            z-index: 999;
        }

        .notification-banner.visible {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .notification-banner strong {
            font-weight: 800;
        }

        .notification-banner .close-banner {
            margin-left: auto;
            background: transparent;
            border: none;
            color: #334155;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .info-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.32);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 998;
        }

        .info-modal-overlay.active {
            display: flex;
        }

        .info-modal {
            width: min(520px, calc(100% - 40px));
            background: #ffffff;
            border-radius: 24px;
            padding: 28px 28px 24px;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.18);
            text-align: left;
        }

        .info-modal h2 {
            margin-bottom: 12px;
            font-size: 24px;
            color: #0f172a;
        }

        .info-modal p {
            margin-bottom: 20px;
            color: #475569;
            line-height: 1.7;
        }

        .info-modal button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            background: #16a34a;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 18px;
            text-align: left;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
        }

        .form-group label i {
            color: #6c757d;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            background: #f1f3f7;
            font-size: 13px;
            color: #212529;
            outline: none;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: white;
            border-color: #cbd5e1;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23495057'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 14px;
        }

        textarea.form-control {
            resize: none;
            min-height: 80px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: none;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .btn-red { background: #cc3a2b; }
        .btn-red:hover { background: #b22e20; }
        .btn-green { background: #16a34a; }
        .btn-green:hover { background: #11823b; }

        /* =========================================
            MODAL PEMBAYARAN QRIS (PRESISI IMAGE)
        =========================================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .qris-modal-card {
            background: #ffffff;
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            position: relative;
            text-align: center;
        }

        .qris-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
        }

        .qris-header i {
            color: #cc3a2b;
            font-size: 22px;
        }

        .btn-close-modal {
            position: absolute;
            top: 25px;
            right: 25px;
            background: none;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
        }

        .qris-details-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px 20px;
            text-align: left;
            margin-bottom: 25px;
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
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
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
            margin-bottom: 25px;
            max-height: 40px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-upload-group label {
            font-size: 12px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .file-upload-group .form-control {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.03);
            background: #f1f3f7;
        }

        .btn-modal-action {
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

        .btn-modal-confirm {
            background: #cc3a2b;
            color: white;
        }

        .btn-modal-cancel {
            background: #e2e8f0;
            color: #475569;
        }

        .btn-modal-action:hover {
            opacity: 0.9;
        }

        .qris-footer-note {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 15px;
        }

        /* =========================================
            MODAL KONFIRMASI LOGOUT
        ========================================= */
        .confirm-logout-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.40);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 20px;
        }

        .confirm-logout-overlay.active {
            display: flex;
        }

        .confirm-logout-card {
            width: min(520px, 100%);
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.25);
            border: 1px solid rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .confirm-logout-header {
            background: linear-gradient(90deg, rgba(220, 38, 38, 0.16), rgba(204, 58, 43, 0.10));
            padding: 22px 24px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .confirm-logout-badge {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fee2e2;
            color: #cc3a2b;
            font-size: 20px;
            flex: 0 0 auto;
        }

        .confirm-logout-header h2 {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
            font-weight: 800;
            line-height: 1.25;
        }

        .confirm-logout-header p {
            margin: 6px 0 0;
            color: #475569;
            font-size: 13px;
            line-height: 1.6;
        }

        .confirm-logout-body {
            padding: 18px 24px 22px;
        }

        .confirm-logout-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .btn-confirm-logout {
            border: none;
            cursor: pointer;
            border-radius: 14px;
            padding: 12px 16px;
            font-weight: 800;
            transition: transform 0.12s ease, opacity 0.2s ease;
            min-width: 140px;
        }

        .btn-confirm-logout:active {
            transform: translateY(1px);
        }

        .btn-logout-cancel {
            background: #e2e8f0;
            color: #0f172a;
        }

        .btn-logout-confirm {
            background: #cc3a2b;
            color: white;
        }

        .btn-confirm-logout:hover {
            opacity: 0.92;
        }

        @media (max-width: 768px) {
            .header-nav { padding: 15px 20px; }
            .main-container { padding: 10px 20px 40px; }
            .title-section h1 { font-size: 26px; }
            .menu-grid { grid-template-columns: 1fr; gap: 20px; }
            .form-layout-container, .qris-modal-card { padding: 25px 20px; }
        }
</style>
</head>

<body>

    @if($showRepairComplete)
        <div class="notification-banner visible" id="repairCompleteBanner">
            <div>
                <div><strong>🔔 Perbaikan Selesai!</strong></div>
                <div style="margin-top:6px; color:#0f172a; font-size:14px; line-height:1.5;">
                    Detail perbaikan dan tagihan sudah dikirim ke akun Anda. Silakan cek agar pembayaran dapat diproses segera.
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <button class="close-banner" onclick="dismissRepairCompleteNotification()">×</button>
                <button class="close-banner" style="background:#0f172a; color:#fff; padding:8px 14px; border-radius:12px; border:none; cursor:pointer; font-weight:700;" onclick="window.location.href='{{ route('customer.repair-invoice', $repairInvoiceBooking->kode_booking) }}'">Lihat Invoice</button>
@php $isFinal = in_array(($repairInvoiceBooking->status ?? ''), ['menunggu_pembayaran_final']); @endphp
                @if($isFinal)
                    <button class="close-banner" style="background:#16a34a; color:#fff; padding:8px 14px; border-radius:12px; border:none; cursor:pointer; font-weight:700;" onclick="window.location.href='{{ route('booking.showUploadPelunasan', ['kode_booking' => $repairInvoiceBooking->kode_booking]) }}'">Upload Pelunasan</button>
                @endif
            </div>
        </div>

        <div class="info-modal-overlay active" id="repairCompleteModal">
            <div class="info-modal">
                <h2>🔔 Perbaikan Selesai!</h2>
                <p>Admin sudah mengonfirmasi biaya perbaikan Anda. Tagihan telah dikirim ke akun customer, dan Anda dapat melihat detailnya pada dashboard ini.</p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <button type="button" onclick="window.location.href='{{ route('customer.repair-invoice', $repairInvoiceBooking->kode_booking) }}'">Lihat Invoice</button>
                    <button type="button" onclick="dismissRepairCompleteNotification()" style="background:#e2e8f0; color:#0f172a;">Tutup Notifikasi</button>
                </div>
            </div>
        </div>
    @endif

    <div class="header-nav">
        <button id="backBtn" class="back-button" style="visibility: hidden;" onclick="navigateTo('dashboardView')">
            <i class="bi bi-arrow-left"></i> Kembali
        </button>

        <a href="/logout" class="logout-button" id="logoutLink">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>

    <!-- Floating Riwayat Booking (pojok kanan bawah) -->
    <a href="{{ route('customer.riwayat-nota-lunas') }}" style="
        position: fixed;
        bottom: 24px;
        right: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #0f172a;
        color: #ffffff;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 13px;
        padding: 12px 18px;
        border-radius: 14px;
        text-decoration: none;
        box-shadow: 0 4px 18px rgba(15,23,42,0.20);
        z-index: 900;
        transition: opacity 0.2s, transform 0.15s;
        "
        onmouseover="this.style.opacity='0.88'; this.style.transform='translateY(-2px)';"
        onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';">
        <i class="bi bi-clipboard-check"></i>
        Riwayat Booking
    </a>

    <!-- Modal konfirmasi logout -->
    <div class="confirm-logout-overlay" id="logoutConfirmOverlay" aria-hidden="true">
        <div class="confirm-logout-card" role="dialog" aria-modal="true" aria-labelledby="logoutConfirmTitle">
            <div class="confirm-logout-header">
                <div class="confirm-logout-badge">🚪</div>
                <div>
                    <h2 id="logoutConfirmTitle">Konfirmasi Logout</h2>
                    <p>Anda akan keluar dari akun. Pastikan data Anda sudah benar.</p>
                </div>
            </div>
            <div class="confirm-logout-body">
                <div class="confirm-logout-actions">
                    <button type="button" class="btn-confirm-logout btn-logout-cancel" id="logoutCancelBtn">Batal</button>
                    <button type="button" class="btn-confirm-logout btn-logout-confirm" id="logoutConfirmBtn">Ya, Logout</button>
                </div>
            </div>
        </div>
    </div>

    @php
        // Nota invoice perbaikan: tampilkan pop-up jika admin sudah mengirim nota (menunggu_pembayaran_lunas)
        $customerInvoiceBooking = $showRepairComplete ? ($repairInvoiceBooking ?? null) : null;
        $notifyStatus = strtolower(trim((string)optional($customerInvoiceBooking)->status));
        // Notifikasi:
        // - menunggu_pembayaran_lunas / selesai: nota tagihan masuk
        // - lunas / paid (lunas): pemberitahuan pelunasan berhasil
        $notifyReady = in_array($notifyStatus, ['menunggu_pembayaran_lunas','selesai','lunas','paid (lunas)']);
        // Saat status lunas, field yang paling pasti terisi adalah lunas_at (sesuai konfirmasi).
        $notifyHasData = !empty(optional($customerInvoiceBooking)->invoice_sent_at)
            || !empty(optional($customerInvoiceBooking)->total_biaya_perbaikan)
            || !empty(optional($customerInvoiceBooking)->lunas_at);
    @endphp

    @if($notifyReady)
        <div style="position:fixed; inset:0; background:rgba(15,23,42,0.55); z-index:99999; display:flex; align-items:center; justify-content:center; padding:16px;" id="invoice-popup-overlay">
            <div style="width:100%; max-width:520px; background:#fff; border-radius:18px; border:1px solid #e2e8f0; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden;">

                <div style="padding:16px 18px; background:#ecfdf5; border-bottom:1px solid rgba(16,185,129,.2); display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:900; color:#065f46; font-size:14px;">📩 Nota Tagihan Masuk</div>
                        <div style="color:#047857; font-weight:800; font-size:12px; margin-top:2px;">Booking: {{ optional($customerInvoiceBooking)->kode_booking }}</div>
                    </div>
                    <button type="button" id="invoice-popup-close" style="border:none; background:#e2e8f0; color:#334155; font-weight:900; padding:8px 12px; border-radius:10px; cursor:pointer;">Tutup</button>
                </div>
                <div style="padding:16px 18px;">
                    <div style="font-size:13px; color:#0f172a; font-weight:900; margin-bottom:8px;">Tagihan perbaikan Anda sudah dikirim oleh admin.</div>
                    <div style="font-size:12px; color:#475569; font-weight:700; line-height:1.5;">Silakan cek dashboard ini dan buka menu invoice untuk melakukan pembayaran.</div>
                </div>
                <div style="padding:16px 18px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap;">
                    <a href="#" id="invoice-popup-go" style="padding:10px 14px; border-radius:12px; background:#16a34a; color:#fff; font-weight:900; text-decoration:none; font-size:12px;">Lihat Invoice</a>
                </div>

                <div style="position:fixed; right:16px; bottom:16px; z-index:100000; display:flex; align-items:center;">
                    <a href="{{ route('customer.showRepairHistory') }}" style="padding:12px 16px; border-radius:14px; background:#0f172a; color:#fff; font-weight:900; text-decoration:none; font-size:12px; box-shadow:0 12px 30px rgba(0,0,0,0.18);">
                        <i class="bi bi-journal-text" style="margin-right:8px;"></i> Riwayat Booking
                    </a>
                </div>
            </div>
        </div>

        <script>
            (function(){
                const overlay = document.getElementById('invoice-popup-overlay');
                const closeBtn = document.getElementById('invoice-popup-close');
                const goBtn = document.getElementById('invoice-popup-go');
                if(!overlay) return;

                const key = 'invoice_popup_seen_' + '{{ optional($customerInvoiceBooking)->kode_booking }}';
                try{
                    if(sessionStorage.getItem(key) === '1') overlay.style.display='none';
                }catch(e){}

                function closePopup(){
                    try{ sessionStorage.setItem(key,'1'); }catch(e){}
                    overlay.style.display='none';
                }

                closeBtn && closeBtn.addEventListener('click', closePopup);
                overlay && overlay.addEventListener('click', function(e){ if(e.target === overlay) closePopup(); });
                goBtn && goBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    closePopup();
                    window.location.href = '{{ route('customer.repair-invoice', ['kodeBooking' => optional($customerInvoiceBooking)->kode_booking]) }}';
                });
            })();
        </script>
    @endif

    <div class="main-container">

        <div id="dashboardView" class="page-view active">
            <div class="title-section">
                <h1>Pilih Jenis Layanan</h1>
                <p>Apakah Anda ingin datang ke bengkel atau panggil teknisi ke rumah?</p>
            </div>

            <div class="menu-grid">
                <div class="base-card" onclick="navigateTo('kunjungiBengkelView')">
                    <div class="icon-circle bg-red"><i class="bi bi-building"></i></div>
                    <h3>Kunjungi Bengkel</h3>
                    <p>Datang langsung ke bengkel kami dengan peralatan lengkap dan teknisi berpengalaman</p>
                    <ul class="card-features list-red">
                        <li>Fasilitas lengkap</li>
                        <li>Ruang tunggu nyaman</li>
                        <li>Harga lebih ekonomis</li>
                    </ul>
                </div>

                <div class="base-card" onclick="navigateTo('homeServiceView')">
                    <div class="icon-circle bg-green"><i class="bi bi-house-door"></i></div>
                    <h3>Home Service</h3>
                    <p>Teknisi kami datang ke lokasi Anda dengan peralatan portable dan suku cadang</p>
                    <ul class="card-features list-green">
                        <li>Hemat waktu</li>
                        <li>Lebih praktis</li>
                        <li>Layanan di tempat</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="kunjungiBengkelView" class="page-view">
            <div class="form-layout-container">
                <div class="form-header-box">
                    <h2>Booking Kunjungan Bengkel</h2>
                    <p>Isi formulir di bawah untuk melanjutkan booking</p>
                </div>

                @include('customer.partials.booking-kunjungan-bengkel')

            </div>
        </div>

        <div id="homeServiceView" class="page-view">
            <div class="form-layout-container">
                <div class="form-header-box">
                    <h2>Booking Home Service</h2>
                    <p>Mekanik kami akan mendatangi kediaman atau lokasi Anda</p>
                </div>
                @include('customer.partials.booking-home-service')
            </div>
        </div>

    </div>

    <?php if ($showPaymentModal): ?>
    <div id="qrisModal" class="modal-overlay">
        <div class="qris-modal-card">
            <button class="btn-close-modal" onclick="closeQrisModal()">&times;</button>

            <div class="qris-header">
                <i class="bi bi-qr-code-scan"></i>
                Pembayaran QRIS
            </div>

            <div class="qris-details-box">
                <div class="qris-row">
                    <span class="qris-label">Total Pembayaran:</span>
                    <span class="qris-value-price"><?php echo $totalPembayaran; ?></span>
                </div>
                <div class="qris-row">
                    <span class="qris-label">Layanan:</span>
                    <span class="qris-value-text"><?php echo $jenisLayanan; ?></span>
                </div>
                <?php if ($isHomeService): ?>
                <div class="qris-row">
                    <span class="qris-label">Jenis Pembayaran:</span>
                    <span class="qris-value-text">DP Home Service</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="qris-instruction-text">
                <?php if ($isHomeService): ?>
                Bayar DP minimal 30% melalui QRIS di bawah.
                <?php else: ?>
                Scan QR Code di bawah ini untuk melakukan pembayaran.
                <?php endif; ?>
            </div>

            <div class="qris-qr-wrapper">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BengkelConnect-HomeService-DP" alt="QRIS DP" class="qris-qr-image">
            </div>

            <?php if ($isHomeService): ?>
            <div class="qris-code-string">
                Scan dan bayar DP.
            </div>
            <?php else: ?>
            <div class="qris-code-string">
                0002010102122625000755A9735A4F48EFA9W45NW6N88P8Q5Y7U5W53PECT0BCM8A
            </div>
            <?php endif; ?>

            <button class="btn-modal-action btn-modal-confirm" onclick="alert('Pembayaran sukses diverifikasi! Mohon cek WhatsApp untuk update berkala.'); closeQrisModal();">
                Konfirmasi Sudah Bayar
            </button>
            <button class="btn-modal-action btn-modal-cancel" onclick="closeQrisModal()">
                Batal
            </button>

            <div class="qris-footer-note">
                Setelah pembayaran, mohon tunggu konfirmasi dari admin.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        let navigationHistory = ['dashboardView'];

        function navigateTo(viewId) {
            const pages = document.querySelectorAll('.page-view');
            pages.forEach(page => page.classList.remove('active'));

            const targetPage = document.getElementById(viewId);
            if (targetPage) {
                targetPage.classList.add('active');
            }

            const backBtn = document.getElementById('backBtn');
            if (viewId === 'dashboardView') {
                navigationHistory = ['dashboardView'];
                backBtn.style.visibility = 'hidden';
            } else {
                if (navigationHistory[navigationHistory.length - 1] !== viewId) {
                    navigationHistory.push(viewId);
                }
                backBtn.style.visibility = 'visible';

                backBtn.onclick = function() {
                    navigationHistory.pop();
                    const previousPage = navigationHistory[navigationHistory.length - 1] || 'dashboardView';
                    navigateTo(previousPage);
                };
            }
        }

        function closeQrisModal() {
            const modal = document.getElementById('qrisModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function dismissRepairCompleteNotification() {
            const banner = document.getElementById('repairCompleteBanner');
            const modal = document.getElementById('repairCompleteModal');
            if (banner) {
                banner.classList.remove('visible');
            }
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function showLogoutConfirm() {
            const overlay = document.getElementById('logoutConfirmOverlay');
            if (overlay) overlay.classList.add('active');
        }

        function hideLogoutConfirm() {
            const overlay = document.getElementById('logoutConfirmOverlay');
            if (overlay) overlay.classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const logoutLink = document.getElementById('logoutLink');
            if (!logoutLink) return;

            logoutLink.addEventListener('click', (e) => {
                e.preventDefault();
                showLogoutConfirm();
            });

            const logoutCancelBtn = document.getElementById('logoutCancelBtn');
            if (logoutCancelBtn) {
                logoutCancelBtn.addEventListener('click', () => hideLogoutConfirm());
            }

            const logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
            if (logoutConfirmBtn) {
                logoutConfirmBtn.addEventListener('click', () => {
                    window.location.href = logoutLink.href;
                });
            }

            const overlay = document.getElementById('logoutConfirmOverlay');
            if (overlay) {
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) hideLogoutConfirm();
                });
            }

            // Keyboard UX
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') hideLogoutConfirm();
            });
        });
    </script>
</body>
</html>