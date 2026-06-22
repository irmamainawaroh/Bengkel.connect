@extends('admin.dashboard-layout')

@section('title','Konfirmasi Pembayaran')

@section('heading','Konfirmasi Pembayaran')

@section('subheading','Daftar transaksi pelunasan yang menunggu konfirmasi admin')

@section('content')

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap; border-bottom:2px solid #f1f5f9; padding-bottom:14px;">
        <div>
            <h2 style="font-size:18px; font-weight:900; color:#0f172a; margin:0 0 4px 0; text-transform:uppercase; letter-spacing:0.05em;">Manajemen Pembayaran Pelunasan</h2>
            <div style="font-size:13px; color:#64748b;">Daftar bukti transfer masuk dari layanan Home Service & Workshop</div>
        </div>
    </div>

    <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; font-size:13px;">
        <span style="color:#64748b; font-weight:800; align-self:center; margin-right:4px;">Filter Status:</span>

        <button type="button" class="btn-filter" onclick="filterStatus(this, 'all')"
                style="padding:6px 14px; background:#0f172a; border:1px solid #0f172a; border-radius:8px; cursor:pointer; font-weight:700; color:#fff; box-shadow:0 2px 4px rgba(0,0,0,0.05); transition:all 0.2s;">
            All
        </button>

        <button type="button" class="btn-filter" onclick="filterStatus(this, 'lunas')"
                style="padding:6px 14px; background:#fff; border:1px solid #cbd5e1; border-radius:8px; cursor:pointer; font-weight:700; color:#334155; transition:all 0.2s;">
            🟢 Lunas
        </button>

        <button type="button" class="btn-filter" onclick="filterStatus(this, 'menunggu')"
                style="padding:6px 14px; background:#fff; border:1px solid #cbd5e1; border-radius:8px; cursor:pointer; font-weight:700; color:#334155; transition:all 0.2s;">
            ⚠️ Menunggu
        </button>

        <button type="button" class="btn-filter" onclick="filterStatus(this, 'ditolak')"
                style="padding:6px 14px; background:#fff; border:1px solid #cbd5e1; border-radius:8px; cursor:pointer; font-weight:700; color:#334155; transition:all 0.2s;">
            🔴 Ditolak
        </button>
    </div>

    <div class="table-wrap" style="overflow-x:auto; background:#fff; border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
        <table style="width:100%; min-width:800px; border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:2px solid #e2e8f0;">
                    <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:800; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.05em; width:60px;">No</th>
                    <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:800; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.05em;">Kode Booking</th>
                    <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:800; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.05em;">Pelanggan</th>
                    <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:800; text-align:left; font-size:12px; text-transform:uppercase; letter-spacing:0.05em;">Total</th>
                    <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:800; text-align:center; font-size:12px; text-transform:uppercase; letter-spacing:0.05em; width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($bookings as $index => $booking)
                @php
                    $total = $booking->total_biaya_perbaikan ?? $booking->total_biaya ?? 0;
                    $totalFormatted = number_format((float)$total, 0, ',', '.');
                    $pathBukti = $booking->bukti_total_pembayaran_path ? asset('storage/' . $booking->bukti_total_pembayaran_path) : '';
                @endphp
                <tr class="konfirmasi-row"
                    style="border-bottom:1px solid #e2e8f0; transition:all 0.2s;"
                    onmouseover="this.style.background='#f8fafc'"
                    onmouseout="this.style.background='transparent'"
                    data-kode="{{ $booking->kode_booking }}"
                    data-nama="{{ $booking->nama_pelanggan ?? $booking->nama ?? 'Pelanggan' }}"
                    data-status="{{ strtoupper(str_replace('_', ' ', $booking->status)) }}"
                    data-catatan="{{ $booking->catatan ?? 'Tidak ada catatan.' }}"
                    data-total="{{ $totalFormatted }}"
                    data-jasa='@json($booking->list_jasa ?? [])'
                    data-sparepart='@json($booking->list_sparepart ?? [])'
                    data-bukti="{{ $pathBukti }}"
                >
                    <td style="padding:14px; font-size:13px; color:#64748b;">{{ $index + 1 }}.</td>
                    <td style="padding:14px; font-size:13px; font-weight:800; color:#0f172a;">{{ $booking->kode_booking }}</td>
                    <td style="padding:14px; font-size:13px; color:#334155;">{{ $booking->nama_pelanggan ?? $booking->nama ?? '-' }}</td>
                    <td style="padding:14px; font-size:13px; font-weight:800; color:#10b981;">Rp {{ $totalFormatted }}</td>
                    <td style="padding:14px; text-align:center;">
                        <button type="button" class="btn btn-sm btn-primary btn-verifikasi" style="padding:8px 14px; background:#2563eb; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:12px; font-weight:800; transition:all 0.2s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                            👁️ Periksa Pelunasan
                        </button>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="5" style="padding:24px; font-size:13px; color:#64748b; text-align:center; font-weight:700;">Tidak ada transaksi pelunasan yang sesuai filter.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:14px; font-size:12px; color:#64748b; font-weight:700;">Showing 1 to {{ count($bookings) }} of {{ count($bookings) }} entries</div>


    <div id="konfirmasi-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px); z-index:9999; overflow-y:auto; padding:20px 10px;">
        <div style="width:100%; max-width:880px; margin:40px auto; background:#fff; border-radius:18px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); overflow:hidden; border:1px solid #e2e8f0;">

            <div style="padding:18px 24px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="font-size:15px; font-weight:900; color:#0f172a; margin:0 0 2px 0; text-transform:uppercase; letter-spacing:0.05em;">Verifikasi Pelunasan Pelanggan</h3>
                    <div style="font-size:12px; color:#64748b;">Periksa kesesuaian nominal pada struk transfer sebelum konfirmasi keuangan.</div>
                </div>
                <button type="button" id="modal-close" style="border:none; background:#f1f5f9; color:#64748b; padding:8px 12px; border-radius:10px; cursor:pointer; font-weight:900; font-size:14px;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background覆='#f1f5f9'">[X]</button>
            </div>

            <div style="padding:24px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:16px; margin-bottom:20px;">
                    <div>
                        <span style="font-size:11px; color:#94a3b8; font-weight:800; text-transform:uppercase; display:block; margin-bottom:4px;">Kode Booking:</span>
                        <span style="font-size:15px; color:#0f172a; font-weight:900; background:#fff; padding:4px 10px; border-radius:6px; border:1px solid #cbd5e1;" id="modal-kode-booking">-</span>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:11px; color:#94a3b8; font-weight:800; text-transform:uppercase; display:block; margin-bottom:4px;">Total Tagihan Akhir:</span>
                        <span style="font-size:16px; color:#16a34a; font-weight:1000;" id="modal-total-akhir">-</span>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap:24px; align-items:start;">

                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div style="border:1px solid #e2e8f0; border-radius:14px; padding:16px; background:#fff;">
                            <div style="font-size:12px; color:#94a3b8; font-weight:800; text-transform:uppercase; margin-bottom:12px; border-bottom:1px dashed #e2e8f0; padding-bottom:6px;">Informasi Pelanggan</div>
                            <div style="margin-bottom:10px; font-size:13px; color:#334155; font-weight:700;">Nama Pelanggan: <span id="modal-nama-pelanggan" style="color:#0f172a; font-weight:900;">-</span></div>
                            <div style="font-size:13px; color:#334155; font-weight:700;">Status Booking: <span id="modal-status-booking" style="color:#334155; font-weight:800;">-</span></div>
                        </div>

                        <div style="border:1px solid #e2e8f0; border-radius:14px; padding:16px; background:#fff;">
                            <div style="font-size:12px; color:#94a3b8; font-weight:800; text-transform:uppercase; margin-bottom:12px; border-bottom:1px dashed #e2e8f0; padding-bottom:6px;">Komponen Biaya Internal</div>
                            <div style="display:flex; flex-direction:column; gap:12px; margin-bottom:14px;">
                                <div>
                                    <div style="font-size:11px; color:#64748b; font-weight:800; margin-bottom:4px;">Detail Jasa Utama:</div>
                                    <div style="font-size:13px; color:#0f172a; font-weight:700; padding:6px; background:#f8fafc; border-radius:6px; border:1px solid #e2e8f0; line-height:1.4;" id="modal-detail-jasa">-</div>
                                </div>
                                <div>
                                    <div style="font-size:11px; color:#64748b; font-weight:800; margin-bottom:4px;">Detail Sparepart:</div>
                                    <div style="font-size:13px; color:#0f172a; font-weight:700; padding:6px; background:#f8fafc; border-radius:6px; border:1px solid #e2e8f0; line-height:1.4;" id="modal-detail-sparepart">-</div>
                                </div>
                            </div>
                            <div style="border-top:1px solid #f1f5f9; padding-top:10px;">
                                <div style="font-size:11px; color:#64748b; font-weight:800; margin-bottom:2px;">Catatan Order:</div>
                                <div style="font-size:13px; color:#334155; font-weight:700; line-height:1.4;" id="modal-catatan-order">-</div>
                            </div>
                        </div>
                    </div>

                    <div style="border:1px solid #e2e8f0; border-radius:14px; padding:16px; background:#fff; display:flex; flex-direction:column; gap:10px;">
                        <div style="font-size:12px; color:#334155; font-weight:900; text-transform:uppercase;">Dokumen Screenshot / Struk Transfer Pelanggan:</div>

                        <div style="background:#0f172a; border-radius:12px; padding:10px; min-height:260px; display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative; overflow:hidden; box-shadow:inset 0 2px 4px rgba(0,0,0,0.1);">
                            <div id="container-bukti-screenshot" style="width:100%; display:flex; justify-content:center; flex-direction:column;">
                                <div style="font-size:12px; color:#94a3b8; font-weight:700; text-align:center;">Memuat Dokumen...</div>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="box-alasan-penolakan" style="display:none; margin-top:20px; padding:16px; background:#fff5f5; border:1px solid #feb2b2; border-radius:12px; animation: slideDown 0.2s ease-out;">
                    <label style="display:block; font-size:11px; font-weight:900; color:#c53030; text-transform:uppercase; margin-bottom:6px; letter-spacing:0.05em;">Alasan Penolakan Bukti (Wajib Diisi)</label>
                    <textarea id="input-alasan-teks" placeholder="Tulis alasan penolakan... (Contoh: Foto terpotong, struk buram, atau nominal transfer kurang)" style="width:100%; box-sizing:border-box; padding:10px; font-size:13px; border:1px solid #fc8181; border-radius:8px; resize:none; font-family:inherit; outline:none; font-weight:700;" rows="2"></textarea>
                </div>

                <div style="margin-top:24px; padding-top:18px; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" id="btn-aksi-tolak" onclick="handleTombolTolak()" style="padding:12px 22px; background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; border-radius:12px; cursor:pointer; font-weight:900; font-size:13px; transition:all 0.2s;">
                        ❌ Tolak Bukti Transfer
                    </button>

                    <button type="button" onclick="submitFormVerifikasi('sah')" style="padding:12px 24px; background:#16a34a; color:#fff; border:none; border-radius:12px; cursor:pointer; font-weight:900; font-size:13px; box-shadow:0 4px 6px -1px rgba(22,163,74,0.2); transition:all 0.2s;" onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
                        ✅ Setujui & Nyatakan Lunas
                    </button>

                </div>

            </div>
        </div>
    </div>

    <form id="form-final-verifikasi" method="POST" action="" style="display:none;">
        @csrf
        <input type="hidden" name="alasan_tolak" id="hidden-alasan-input">
    </form>

    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('konfirmasi-modal');
            const closeBtn = document.getElementById('modal-close');
            const boxAlasan = document.getElementById('box-alasan-penolakan');
            const teksAlasan = document.getElementById('input-alasan-teks');
            const btnTolak = document.getElementById('btn-aksi-tolak');
            const formFinal = document.getElementById('form-final-verifikasi');
            const hiddenAlasan = document.getElementById('hidden-alasan-input');
            const statusEl = document.getElementById('modal-status-booking');

            let currentKodeBooking = '';

            const routeTerimaDPTemplate = "{{ route('home-service.confirm', ['kodeBooking' => '__KODE__']) }}";
            const routeTerimaFinalTemplate = "{{ route('admin.konfirmasi-pembayaran.confirm-final', ['kodeBooking' => '__KODE__']) }}";
            const routeTolakFinalTemplate = "{{ route('admin.konfirmasi-pembayaran.tolak', ['kodeBooking' => '__KODE__']) }}";

            const infoButtons = document.querySelectorAll('.btn-verifikasi');

            infoButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                    // Ambil row kontainer terdekat (<tr class="konfirmasi-row">)
                    const row = this.closest('.konfirmasi-row');

                    // Ambil seluruh data atribut dari objek TR
                    currentKodeBooking = row.getAttribute('data-kode');
                    const nama = row.getAttribute('data-nama');
                    const status = row.getAttribute('data-status');
                    const catatan = row.getAttribute('data-catatan');
                    const buktiUrl = row.getAttribute('data-bukti');
                    const total = row.getAttribute('data-total');
                    
                    // Parse data list komponen komplit
                    const listJasa = JSON.parse(row.getAttribute('data-jasa') || '[]');
                    const listSparepart = JSON.parse(row.getAttribute('data-sparepart') || '[]');

                    // Set data teks dasar modal sesuai id penamaan baru Anda
                    document.getElementById('modal-kode-booking').textContent = currentKodeBooking;
                    document.getElementById('modal-nama-pelanggan').textContent = nama;
                    statusEl.textContent = status;
                    document.getElementById('modal-catatan-order').textContent = catatan;
                    document.getElementById('modal-total-akhir').textContent = 'Rp ' + total;

                    // --- PROSES MENAMPILKAN DETAIL JASA UTAMA ---
                    const containerJasa = document.getElementById('modal-detail-jasa');
                    if (listJasa.length > 0) {
                        let htmlJasa = '<ul style="padding-left:16px; margin:0; line-height: 1.5;">';
                        listJasa.forEach(item => {
                            let namaJasa = item.nama_jasa || item.name || 'Jasa Perbaikan';
                            let hargaJasa = parseInt(item.harga || item.price || 0).toLocaleString('id-ID');
                            htmlJasa += `<li>${namaJasa} (<span style="color:#10b981; font-weight:700;">Rp ${hargaJasa}</span>)</li>`;
                        });
                        htmlJasa += '</ul>';
                        containerJasa.innerHTML = htmlJasa;
                    } else {
                        containerJasa.innerHTML = '<span style="color:#94a3b8; font-style:italic;">Tidak ada detail Jasa.</span>';
                    }

                    // --- PROSES MENAMPILKAN DETAIL SPAREPART ---
                    const containerSparepart = document.getElementById('modal-detail-sparepart');
                    if (listSparepart.length > 0) {
                        let htmlSparepart = '<ul style="padding-left:16px; margin:0; line-height: 1.5;">';
                        listSparepart.forEach(item => {
                            let namaPart = item.nama_sparepart || item.name || 'Sparepart';
                            let qty = item.qty || item.quantity || 1;
                            let hargaPart = parseInt(item.harga || item.price || 0).toLocaleString('id-ID');
                            htmlSparepart += `<li>${namaPart} x${qty} (<span style="color:#10b981; font-weight:700;">Rp ${hargaPart}</span>)</li>`;
                        });
                        htmlSparepart += '</ul>';
                        containerSparepart.innerHTML = htmlSparepart;
                    } else {
                        containerSparepart.innerHTML = '<span style="color:#94a3b8; font-style:italic;">Tidak ada detail Sparepart.</span>';
                    }

                    // --- PROSES BUKTI SCREENSHOT STRUK ---
                    const containerBukti = document.getElementById('container-bukti-screenshot');
                    
                    // Deteksi ekstensi file PDF secara aman
                    const isPdf = buktiUrl ? buktiUrl.toLowerCase().endsWith('.pdf') : false;

                    if (buktiUrl && buktiUrl !== '') {
                        if (isPdf) {
                            containerBukti.innerHTML = `
                                <embed src="${buktiUrl}" type="application/pdf" width="100%" height="250px" style="border-radius:8px;" />
                                <div style="text-align:center; margin-top:10px;">
                                    <a href="${buktiUrl}" target="_blank" style="font-size:11px; text-decoration:none; color:#38bdf8; font-weight:800;">[🔍 Buka PDF Ukuran Penuh]</a>
                                </div>`;
                        } else {
                            containerBukti.innerHTML = `
                                <img src="${buktiUrl}" alt="Struk Pelanggan" style="max-width:100%; max-height:240px; object-fit:contain; border-radius:8px; display:block; margin:0 auto;" onerror="this.onerror=null; this.parentNode.innerHTML='<div style=\"color:#f87171; text-size:12px; text-align:center; font-weight:700;\">Gagal memuat gambar struk.</div>';">
                                <div style="text-align:center; margin-top:10px;">
                                    <a href="${buktiUrl}" target="_blank" style="font-size:11px; text-decoration:none; color:#38bdf8; font-weight:800;">[🔍 Lihat Ukuran Penuh]</a>
                                </div>`;
                        }
                    } else {
                        containerBukti.innerHTML = `
                            <div style="text-align:center; color:#94a3b8; padding:40px 10px;">
                                <div style="font-size:36px; margin-bottom:8px;">📄</div>
                                <div style="font-weight:700; color:#cbd5e1; font-size:12px;">Bukti transfer tidak tersedia / belum diupload.</div>
                                <div style="font-size:11px; color:#64748b; margin-top:4px;">Pastikan field <code>bukti_total_pembayaran_path</code> sudah terisi.</div>
                            </div>`;
                    }

                    // Reset state form penolakan modal
                    boxAlasan.style.display = 'none';
                    teksAlasan.value = '';
                    btnTolak.style.background = '#fee2e2';
                    btnTolak.style.color = '#dc2626';

                    modal.style.display = 'block';
                });
            });

            // Global Actions (Tolak & Setuju) di dalam scope event DOMContentLoaded
            window.handleTombolTolak = function() {
                if (boxAlasan.style.display === 'none') {
                    boxAlasan.style.display = 'block';
                    teksAlasan.focus();
                    btnTolak.style.background = '#dc2626';
                    btnTolak.style.color = '#fff';
                } else {
                    submitFormVerifikasi('tolak');
                }
            }

            window.submitFormVerifikasi = function(keputusan) {
                if (keputusan === 'sah') {
                    const statusNow = (statusEl.textContent || '').toLowerCase().replace(/\s/g, '_');
                    // Pastikan tombol "Setujui & Nyatakan Lunas" selalu memanggil confirm-final
                    // untuk kasus bukti final: menunggu_konfirmasi_bukti_final.
                    let routeTerima = routeTerimaFinalTemplate;

                    // DP bukti hanya untuk status bukti DP.
                    if (statusNow === 'menunggu_konfirmasi_bukti') {
                        routeTerima = routeTerimaDPTemplate;
                    }

                    if (confirm('Apakah Anda yakin dokumen struk sesuai dan dana pelunasan sah?')) {
                        // show extra confirm popup to match request 'Setuju & Nyatakan Lunas'
                        if (confirm('Setuju untuk menandai pelunasan sebagai LUNAS?')) {
                            formFinal.action = routeTerima.replace(/__KODE__/g, currentKodeBooking);
                            formFinal.submit();
                        }
                    }

                } else if (keputusan === 'tolak') {
                    if (!teksAlasan.value.trim()) {
                        alert('Mohon isi alasan penolakan terlebih dahulu!');
                        teksAlasan.focus();
                        return;
                    }
                    if (confirm('Tolak dokumen pembayaran untuk order ini?')) {
                        hiddenAlasan.value = teksAlasan.value;
                        formFinal.action = routeTolakFinalTemplate.replace(/__KODE__/g, currentKodeBooking);
                        formFinal.submit();
                    }
                }
            }

            function closeModal() { modal.style.display = 'none'; }
            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
        });

        // Filter Status Fungsi luar DOM
        function filterStatus(buttonTarget, statusTarget) {
            document.querySelectorAll('.btn-filter').forEach(btn => {
                btn.style.background = '#fff';
                btn.style.color = '#334155';
                btn.style.borderColor = '#cbd5e1';
                btn.style.boxShadow = 'none';
            });

            buttonTarget.style.background = '#0f172a';
            buttonTarget.style.color = '#fff';
            buttonTarget.style.borderColor = '#0f172a';
            buttonTarget.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';

            const rows = document.querySelectorAll('.konfirmasi-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status').toLowerCase();
                let isMatch = false;

                if (statusTarget === 'all') {
                    isMatch = true;
                } else if (statusTarget === 'menunggu') {
                    if (rowStatus.includes('menunggu') || rowStatus.includes('bukti')) {
                        isMatch = true;
                    }
                } else if (rowStatus === statusTarget.toLowerCase()) {
                    isMatch = true;
                }

                if (isMatch) {
                    row.style.display = 'table-row';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const existingEmpty = document.querySelector('.empty-row');
            if (visibleCount === 0) {
                if (!existingEmpty) {
                    const tbody = document.querySelector('tbody');
                    const tr = document.createElement('tr');
                    tr.className = 'empty-row';
                    tr.innerHTML = `<td colspan="5" style="padding:24px; font-size:13px; color:#64748b; text-align:center; font-weight:700;">Tidak ada transaksi dengan status ini.</td>`;
                    tbody.appendChild(tr);
                } else {
                    existingEmpty.style.display = 'table-row';
                }
            } else if (existingEmpty) {
                existingEmpty.style.display = 'none';
            }
        }
    </script>

@endsection