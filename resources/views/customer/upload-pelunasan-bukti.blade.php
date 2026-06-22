<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Upload Pelunasan Pembayaran</title>

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
            background: #f8fafc;
            min-height: 100vh;
            color: #0f172a;
            display: flex;
            flex-direction: column;
        }

        .header-nav {
            width: 100%;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border-bottom: 1px solid rgba(148, 163, 184, 0.15);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            background: #fff;
            border: 1px solid rgba(15,23,42,.12);
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .back-button:hover {
            background: #f1f5f9;
        }

        .logout-button {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 10px;
            background-color: #fee2e2;
            transition: opacity 0.2s;
        }

        .logout-button:hover {
            opacity: 0.9;
        }

        .main-container {
            max-width: 840px;
            width: 100%;
            margin: 0 auto;
            padding: 24px 18px 50px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 24px;
        }

        .title-section h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .title-section p {
            font-size: 13px;
            color: #64748b;
        }

        /* Alert Notification */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }

        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.15);
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 24px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-box {
            background: #f8fafc;
            border-radius: 14px;
            padding: 16px;
            border: 1px solid rgba(148, 163, 184, 0.15);
            margin-bottom: 16px;
        }

        .info-box.highlight {
            background: #fffbeb;
            border-color: #fde68a;
        }

        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .instruction-list {
            padding-left: 18px;
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }

        .instruction-list li {
            margin-bottom: 6px;
        }

        /* QRIS Card Capture Wrapper */
        .qris-wrapper {
            background: #111827;
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            color: #ffffff;
        }

        .qris-static-img {
            width: 100%;
            max-width: 220px;
            height: auto;
            display: block;
            margin: 0 auto 12px;
            border-radius: 12px;
            background: #ffffff;
            padding: 6px;
        }

        .qris-meta {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 500;
            line-height: 1.4;
        }

        /* File Upload Zone styling */
        .upload-zone {
            margin-top: 20px;
            padding: 20px;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .upload-zone:hover {
            background: #f1f5f9;
            border-color: #cc3a2b;
        }

        .upload-zone i {
            font-size: 28px;
            color: #64748b;
            display: block;
            margin-bottom: 6px;
        }

        .upload-zone p {
            font-size: 12px;
            color: #475569;
        }

        .form-control-file {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Preview Thumbnail */
        .preview-container {
            margin-top: 12px;
            display: none;
            text-align: center;
            background: #f1f5f9;
            padding: 14px;
            border-radius: 10px;
        }

        .preview-container img {
            max-height: 160px;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .btn {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            margin-top: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #cc3a2b;
            color: white;
            box-shadow: 0 4px 12px rgba(204, 58, 43, 0.2);
        }

        .btn-primary:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .btn-primary:disabled {
            background: #94a3b8;
            box-shadow: none;
            cursor: not-allowed;
        }

        .btn-back {
            background: #e2e8f0;
            color: #475569;
            margin-top: 10px;
        }

        .btn-back:hover {
            background: #cbd5e1;
        }

        @media (max-width: 768px) {
            .grid-2 { grid-template-columns: 1fr; gap: 20px; }
            .main-container { padding: 12px 14px 40px; }
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
            <h1>Upload Pelunasan Pembayaran</h1>
            <p>Kirim bukti pembayaran akhir agar status pengerjaan unit Anda diselesaikan & diverifikasi admin.</p>
        </div>

        {{-- Flash Session Message Laravel --}}
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="grid-2">
                <div>
                    <div class="section-title"><i class="bi bi-info-circle"></i> Ringkasan Transaksi</div>
                    
                    <div class="info-box highlight">
                        <div class="info-label">Total Pelunasan Wajib Dibayar</div>
                        <div class="info-value" style="color: #cc3a2b; font-size: 20px;">
                            {{ isset($booking->total_biaya_perbaikan) ? 'Rp ' . number_format($booking->total_biaya_perbaikan, 0, ',', '.') : 'Lihat Dokumen Invoice' }}
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-label">Kode Registrasi Booking</div>
                        <div class="info-value" style="font-size: 14px; font-family: monospace; letter-spacing: 0.5px;">
                            {{ $booking->kode_booking }}
                        </div>
                    </div>

                    <div class="section-title"><i class="bi bi-journal-text"></i> Instruksi Pelunasan</div>
                    <ul class="instruction-list">
                        <li>Pindai/Scan barcode QRIS di samping kanan menggunakan Mobile Banking atau Aplikasi E-Wallet pilihan Anda.</li>
                        <li>Pastikan jumlah nominal transfer sudah sesuai dengan **Total Pelunasan** di atas.</li>
                        <li>Ambil tangkapan layar (*screenshot*) atau foto struk bukti transaksi yang sah.</li>
                        <li>Unggah dokumen tersebut pada form di bawah ini dan tekan kirim.</li>
                    </ul>
                </div>

                <div>
                    <div class="section-title"><i class="bi bi-qr-code-scan"></i> QRIS Statis Toko</div>
                    
                    <div class="qris-wrapper" id="qris-capture-box">
                        <img id="qris-img" class="qris-static-img" src="{{ asset('images/qris.png') }}" alt="QRIS Pelunasan">
                        <div class="qris-meta">
                            <strong style="color: #fff; font-size: 12px; display:block; margin-bottom:2px;">BENGKEL CONNECT</strong>
                            <span>NMID: ID102026142890</span><br>
                            <span style="font-size: 9px; color: #6b7280;">ID Booking: {{ $booking->kode_booking }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <form id="upload-form" method="POST" action="{{ route('pelunasan.upload-bukti') }}" enctype="multipart/form-data" style="margin-top:24px; border-top: 1px solid rgba(148, 163, 184, 0.15);">
                @csrf

                <input type="hidden" name="kodeBooking" value="{{ $booking->kode_booking }}">
                <input type="hidden" id="qris_data" name="qris_data" value="">
                {{-- Pastikan kembali ke halaman invoice perbaikan setelah admin mengonfirmasi final payment --}}
                <input type="hidden" name="redirect_invoice" value="1">


                <div class="upload-zone">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p id="upload-text">Klik area ini untuk memilih atau drop gambar bukti pelunasan</p>
                    <span style="font-size: 11px; color:#94a3b8;">Format didukung: JPG, PNG, PDF (Maks. 2MB)</span>
                    <input id="bukti_pembayaran" type="file" name="bukti_pembayaran" class="form-control-file" accept="image/*,application/pdf" required>
                </div>

                @error('bukti_pembayaran')
                    <span style="color: #dc2626; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                @enderror

                <div class="preview-container" id="preview-box">
                    <p style="font-size: 11px; color: #64748b; margin-bottom: 8px; font-weight: 600;">Pratinjau Berkas:</p>
                    <div id="preview-render-area">
                        <img id="preview-img" src="#" alt="Pratinjau Bukti">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="btn-submit-form">
                    <i class="bi bi-send-check"></i> Kirim Bukti Pelunasan
                </button>
                <button type="button" class="btn btn-back" onclick="history.back()">Batalkan Transaksi</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        const fileInput = document.getElementById('bukti_pembayaran');
        const previewBox = document.getElementById('preview-box');
        const previewRenderArea = document.getElementById('preview-render-area');
        const uploadText = document.getElementById('upload-text');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                uploadText.textContent = `Berkas dipilih: ${file.name}`;
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewRenderArea.innerHTML = `<img id="preview-img" src="${e.target.result}" alt="Pratinjau Bukti">`;
                        previewBox.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    // Berikan visualisasi representatif jika yang diunggah file PDF
                    previewRenderArea.innerHTML = `
                        <div style="padding: 10px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-flex; align-items: center; gap: 10px;">
                            <i class="bi bi-file-earmark-pdf-fill" style="font-size: 28px; color: #dc2626; margin: 0;"></i>
                            <span style="font-size: 13px; font-weight: 600; color: #334155;">Dokumen PDF Terlampir</span>
                        </div>`;
                    previewBox.style.display = 'block';
                } else {
                    previewBox.style.display = 'none';
                }
            }
        });

        // Interseptor Submit Form & html2canvas Otomatis Pasca Pemrosesan dataURL
        document.getElementById('upload-form').addEventListener('submit', async function(e) {
            // Hentikan proses submit native bawaan sementara waktu
            e.preventDefault();
            
            const formElement = this;
            const submitBtn = document.getElementById('btn-submit-form');
            
            try {
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses & Mengirim...';
                submitBtn.disabled = true;

                // Tangkap area penampung QRIS secara dinamis
                const qrisContainer = document.getElementById('qris-capture-box');
                const canvas = await html2canvas(qrisContainer, {
                    backgroundColor: '#111827',
                    scale: 2,
                    useCORS: true
                });

                const qrisDataUrl = canvas.toDataURL('image/png');
                document.getElementById('qris_data').value = qrisDataUrl;

                // PENTING: Gunakan prototype asli HTMLFormElement untuk memotong bypass loop preventDefault
                HTMLFormElement.prototype.submit.call(formElement);

            } catch (error) {
                console.error('Error capturing QRIS area:', error);
                alert('Terjadi kesalahan saat memvalidasi modul kirim. Silakan coba klik kirim ulang.');
                
                submitBtn.innerHTML = '<i class="bi bi-send-check"></i> Kirim Bukti Pelunasan';
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>