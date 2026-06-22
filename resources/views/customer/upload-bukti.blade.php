<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Upload Bukti Pembayaran</title>

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
        }

        .header-nav {
            width: 100%;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 68px;
        }

        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #334155;
            font-size: 14px;
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
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 10px;
            background-color: #fee2e2;
            transition: opacity 0.2s;
        }

        .main-container {
            max-width: 760px;
            width: 100%;
            margin: auto;
            padding: 0px 18px 40px;
        }

        .title-section {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .title-section h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .title-section p {
            font-size: 13px;
            color: #64748b;
        }

        .card {
            background: #ffffff;
            border-radius: 18px;
            padding: 22px;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .note {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 14px;
            line-height: 1.5;
        }

        .qris-static-img {
            width: 100%;
            max-width: 300px;
            height: auto;
            display: block;
            margin: 0 auto 16px;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: #f8fafc;
            font-size: 13px;
            color: #212529;
            outline: none;
        }

        textarea.form-control {
            resize: none;
            min-height: 80px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            margin-top: 12px;
            transition: opacity 0.2s;
        }

        .btn-primary {
            background: #cc3a2b;
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.92;
        }

        .btn-back {
            background: #e2e8f0;
            color: #475569;
            margin-top: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 768px) {
            .header-nav { padding: 15px 20px; }
            .main-container { padding: 0px 20px 40px; }
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
    <div class="header-nav">
        <a class="back-button" href="{{ route('booking.payment-qris-pelunasan', ['kode_booking' => $booking->kode_booking]) }}">
            <i class="bi bi-arrow-left"></i> Kembali ke Halaman QRIS
        </a>

        <a href="/logout" class="logout-button">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>


        <div class="main-container">
            @php
                $isFinalPayment = in_array($booking->status, ['menunggu_pembayaran_final', 'menunggu_konfirmasi_bukti_final']);
                // Pastikan nominal pelunasan sesuai total biaya perbaikan booking
                $totalNominalPelunasan = (float) ($booking->total_biaya_perbaikan ?? $booking->total_biaya ?? 0);
                $formatted = fn($value) => 'Rp ' . number_format((float)$value, 0, ',', '.');
            @endphp

            <div class="title-section">
                <h1>KONFIRMASI BUKTI PEMBAYARAN</h1>
                <p>Silakan unggah foto struk transfer atau tangkapan layar (screenshot) hasil scan QRIS Anda.</p>
            </div>


        <div class="card">
            <div class="grid-2">
                <div>
                    <div class="section-title">Instruksi Pembayaran</div>
                    <div class="note">
                        1) Scan / gunakan QRIS di bawah untuk melakukan pembayaran {{ $isFinalPayment ? 'total akhir' : 'DP' }}.<br>
                        2) Setelah pembayaran selesai, upload bukti pembayaran (foto/scan/struk).<br>
                        3) Admin akan memverifikasi dan update status booking Anda.
                    </div>

                    <div class="section-title">Kode Booking</div>
                    <div class="note" style="margin-bottom:0; color:#0f172a; font-weight:600;">
                        {{ $booking->kode_booking }}
                    </div>
                </div>

                <div>
                    <div class="section-title">QRIS (Statis)</div>
                    <img id="qris-img" class="qris-static-img"
                        src="{{ asset('images/qris.png') }}"
                        alt="QRIS Pembayaran">
                    <div class="note" style="margin-bottom:0;">
                        Gunakan QR di atas untuk melakukan pembayaran {{ $isFinalPayment ? 'total akhir' : 'DP' }}.
                    </div>
                </div>
            </div>

            <form id="upload-form" method="POST" action="{{ route('booking.upload-bukti') }}" enctype="multipart/form-data" style="margin-top:18px;">
                @csrf

                <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
                <input type="hidden" id="qris_data" name="qris_data" value="">

                <div class="form-group">
                    <label class="section-title" for="bukti_pembayaran">Upload Bukti Pembayaran</label>
                    <input id="bukti_pembayaran" type="file" name="bukti_pembayaran" class="form-control" accept="image/*,application/pdf" required>
                </div>

                <button type="submit" class="btn btn-primary">Kirim Bukti</button>
                <button type="button" class="btn btn-back" onclick="history.back()">Batal</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Capture QRIS and attach to form before submit
        document.getElementById('upload-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Memproses...';
                submitBtn.disabled = true;
                
                // Capture QRIS image
                const qrisContainer = document.getElementById('qris-img');
                const canvas = await html2canvas(qrisContainer, {
                    backgroundColor: '#ffffff',
                    scale: 2
                });
                
                // Get canvas as data URL
                const qrisDataUrl = canvas.toDataURL('image/png');
                
                // Set hidden field with QRIS data
                document.getElementById('qris_data').value = qrisDataUrl;
                
                // Submit form
                this.submit();
                
            } catch (error) {
                console.error('Error capturing QRIS:', error);
                alert('Terjadi kesalahan saat memproses QRIS. Silakan coba lagi.');
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Kirim Bukti';
                submitBtn.disabled = false;
            }
        });
    </script>
</body>

</html>

