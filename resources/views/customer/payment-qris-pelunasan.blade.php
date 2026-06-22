<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelConnect - Pembayaran QRIS Pelunasan</title>
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
        .title-section h1 { font-size:24px; font-weight:800; color:#0f172a; margin-bottom:6px; }
        .title-section p { font-size:13px; color:#64748b; }

        .card {
            background:#fff; border-radius:20px; padding:24px;
            box-shadow:0 10px 25px rgba(15,23,42,0.04);
            border:1px solid rgba(148,163,184,0.15);
        }

        .qris-wrapper {
            background:#111827; border-radius:16px; padding:16px; text-align:center; color:#fff;
        }
        .qris-static-img {
            width:100%; max-width:260px; height:auto; display:block; margin:0 auto 12px;
            border-radius:12px; background:#ffffff; padding:6px;
        }

        .meta {
            font-size:11px; color:#9ca3af; font-weight:500; line-height:1.4;
        }

        .instruction-list { margin-top:16px; padding-left:18px; font-size:12px; color:#475569; line-height:1.6; }
        .instruction-list li { margin-bottom:6px; }

        .btn-row { display:flex; gap:12px; flex-wrap:wrap; margin-top:18px; }
        .btn {
            flex:1; min-width:220px;
            padding:12px 14px; border-radius:12px; border:none; cursor:pointer;
            font-size:13px; font-weight:800; display:inline-flex; align-items:center; justify-content:center; gap:8px;
            transition:opacity 0.2s;
        }
        .btn-primary { background:#16a34a; color:#fff; } /* Diubah ke warna hijau sukses sesuai request */
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
        
        /* Style tambahan saat file di-drag diatas dropzone */
        .dragover { background: #e2e8f0 !important; border-color: #16a34a !important; }
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
        @if(session('error'))
            <div style="background:#fee2e2; border:1px solid #fca5a5; color:#b91c1c; padding:12px; border-radius:12px; margin-bottom:16px; font-size:13px; font-weight:600;">
                ⚠ {{ session('error') }}
            </div>
        @endif

        <div class="title-section">
            <h1>QRIS Pelunasan</h1>
            <p>Scan QRIS di bawah untuk membayar total akhir, lalu upload bukti pelunasan.</p>
        </div>

        <div class="card">
            <div class="qris-wrapper">
                <img class="qris-static-img" src="{{ asset('images/qris.png') }}" alt="QRIS Pelunasan">
                <div class="meta">
                    <strong style="color:#fff; font-size:12px; display:block; margin-bottom:2px;">BENGKEL CONNECT</strong>
                    <span>NMID: ID102026142890</span>
                    @if(isset($booking->kode_booking))
                        <span style="font-size:9px; color:#6b7280; display:block; margin-top:2px;">ID Booking: {{ $booking->kode_booking }}</span>
                    @endif
                </div>
            </div>

            @if(isset($booking->kode_booking))
                <div class="chip">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Kode Booking:</span> {{ $booking->kode_booking }}
                </div>
            @endif

            <ul class="instruction-list">
                <li>Scan QRIS menggunakan Mobile Banking / E-Wallet pilihan Anda.</li>
                <li>Pastikan nominal transfer sesuai dengan total tagihan pelunasan nota.</li>
                <li>Setelah transaksi sukses, silakan unggah bukti pelunasan melalui form di bawah.</li>
            </ul>

            <div style="margin-top:18px; border-top:1px solid rgba(148,163,184,0.18); padding-top:18px;">
                
                <form id="pelunasan-upload-form" action="{{ route('customer.pelunasan.submit', $booking->kode_booking) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="display:grid; grid-template-columns:1fr; gap:12px;">
                        <div style="background:#f1f5f9; border:1px solid rgba(148,163,184,0.18); border-radius:16px; padding:14px;">
                            <div style="font-size:12px; font-weight:700; color:#0f172a; margin-bottom:10px;">
                                <i class="bi bi-camera"></i> FOTO / SCREENSHOT BUKTI TRANSFER VIA QRIS
                            </div>

                            <label id="upload-drop" style="display:block; border:2px dashed rgba(148,163,184,0.55); border-radius:16px; padding:24px; cursor:pointer; text-align:center; color:#334155; font-weight:600; background:#fff; transition: all 0.2s;">
                                <div style="font-size:32px; margin-bottom:8px;">📸</div>
                                <div style="font-size:13px;">Tarik foto struk ke sini atau klik untuk pilih file</div>
                                <div style="margin-top:6px; font-size:11px; font-weight:500; color:#64748b;">
                                    (Format: JPG, PNG, JPEG, PDF - Maks 5MB)
                                </div>
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*,application/pdf" style="display:none;" required>
                            </label>

                            <div id="preview-wrap" style="margin-top:14px; display:none;">
                                <div style="font-size:12px; font-weight:700; color:#0f172a; margin-bottom:8px;">Pratinjau File:</div>
                                <div style="border:1px solid rgba(148,163,184,0.25); border-radius:14px; background:#fff; padding:12px;">
                                    <div style="display:flex; gap:12px; align-items:flex-start;">
                                        <div style="width:48px; height:48px; border-radius:10px; background:#f8fafc; display:flex; align-items:center; justify-content:center; border:1px solid rgba(148,163,184,0.2); font-size:20px;">
                                            📄
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div id="preview-filename" style="font-weight:700; color:#0f172a; font-size:12px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Nama_File.jpg</div>
                                            <div style="font-size:11px; color:#64748b; margin-top:2px;" id="preview-subtext">0 KB</div>
                                            <img id="preview-img" style="margin-top:10px; max-width:100%; max-height:250px; border-radius:12px; display:none; border:1px solid #e2e8f0;" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="background:#fff; border:1px solid rgba(148,163,184,0.18); border-radius:16px; padding:14px;">
                            <div style="font-size:12px; font-weight:700; color:#0f172a; margin-bottom:8px;">Catatan Pembayaran (Opsional):</div>
                            <input type="text" name="catatan" placeholder="Contoh: Transfer Mandiri an. Irma" style="width:100%; padding:12px; border-radius:12px; border:1px solid rgba(148,163,184,0.25); background:#f8fafc; font-size:13px; outline:none; font-family:inherit;">
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:16px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send-check"></i> Kirim Bukti Pembayaran
                        </button>
                        <button type="button" class="btn btn-back" onclick="window.history.back()">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('bukti_pembayaran');
        const dropZone = document.getElementById('upload-drop');
        const previewWrap = document.getElementById('preview-wrap');
        const previewImg = document.getElementById('preview-img');
        const previewFilename = document.getElementById('preview-filename');
        const previewSubtext = document.getElementById('preview-subtext');

        // Fungsi memproses file yang masuk
        function handleFile(file) {
            if (!file) return;

            previewFilename.textContent = file.name;
            previewSubtext.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            previewWrap.style.display = 'block';

            // Jika file berupa gambar, tampilkan preview gambarnya
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                // Jika PDF, sembunyikan tag img (hanya nama file saja)
                previewImg.style.display = 'none';
            }
        }

        // Event saat input file manual berubah
        fileInput.addEventListener('change', function() {
            handleFile(this.files[0]);
        });

        // Event Efek Drag & Drop
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
            }, false);
        });

        // Saat file dilepas (dropped) ke area
        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if(files.length > 0) {
                fileInput.files = files; // Pasang file ke input bawaan form
                handleFile(files[0]);
            }
        });
    </script>
</body>
</html>