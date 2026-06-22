@extends('admin.dashboard-layout')

@section('title','Detail Laporan Mekanik - Admin')
@section('heading','Detail Laporan Mekanik')
@section('subheading','Validasi biaya & konfirmasi ke akun customer')

@section('content')

<style>
    .card{background:#fff;border:1px solid rgba(0,0,0,0.08);border-radius:16px;box-shadow:0 10px 22px rgba(0,0,0,0.04);padding:20px}
    .muted{color:#64748b}
    .badge{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:900;display:inline-block}
    .badge-approval{background:#dcfce7;color:#166534}
    .badge-warning{background:#fef3c7;color:#92400e}
    .section-title{font-size:14px;font-weight:900;color:#0f172a;margin-bottom:14px}
    .summary-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-bottom:18px}
    .summary-item{background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:16px}
    .summary-item span{display:block;color:#64748b;font-size:12px;margin-bottom:6px}
    .summary-item strong{display:block;font-size:16px;color:#0f172a;font-weight:900}
    .detail-table{overflow-x:auto}
    .detail-table table{width:100%;border-collapse:collapse;min-width:720px}
    .detail-table th,
    .detail-table td{padding:14px 16px;border:1px solid #e2e8f0;vertical-align:top}
    .detail-table th{background:#f8fafc;color:#475569;font-size:13px;text-align:left}
    .detail-table input[type=number]{width:100%;padding:10px 12px;border:1px solid #e2e8f0;border-radius:12px}
    .field-row{display:grid;gap:16px;grid-template-columns:1fr 1fr;align-items:start}
    .field-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:16px}
    .field-box label{display:block;font-size:12px;color:#64748b;margin-bottom:8px;font-weight:800}
    .field-box input{width:100%;padding:14px 16px;border:1px solid #e2e8f0;border-radius:14px;background:#fff;color:#0f172a;font-weight:900}
    .field-box input[readonly]{background:#f1f5f9;cursor:not-allowed}
    .btn-primary{background:#dc2626;color:#fff;border:none;padding:14px 18px;border-radius:14px;font-weight:900;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;min-width:240px;text-align:center}
    .btn-outline{background:#fff;border:1px solid #e2e8f0;color:#475569;padding:12px 16px;border-radius:12px;font-weight:900;cursor:pointer;display:inline-block;text-decoration:none}
    .radio-group{display:flex;gap:12px;flex-wrap:wrap}
    .radio-group label{display:flex;gap:10px;align-items:center;font-weight:700;color:#0f172a}
    .sticky-actions{background:linear-gradient(180deg, rgba(248,250,252,0), #f8fafc 60%);position:sticky;bottom:0;padding:18px 0 10px;z-index:5}
</style>

<form method="POST" action="{{ url('/admin/laporan-mekanik/'.$booking->kode_booking.'/konfirmasi-biaya') }}">
    @csrf

    @php
        $booking = $booking ?? null;
        $mechanicName = optional($booking->mechanic)->name ?? '-';
        $recommendedPartsRaw = $booking->recommended_parts ?? [];
        if (is_string($recommendedPartsRaw)) {
            $decoded = json_decode($recommendedPartsRaw, true);
            $recommendedPartsRaw = is_array($decoded) ? $decoded : [];
        }

        $parts = [];
        foreach((array)$recommendedPartsRaw as $p){
            if(is_array($p)){
                $name = $p['name'] ?? ($p[0] ?? null);
                $qty = $p['qty'] ?? null;
                $price = $p['price'] ?? 0;
                if($name) $parts[] = ['name'=>$name,'qty'=>$qty ?? 1,'price'=>$price];
            }else{
                $parts[] = ['name'=>(string)$p,'qty'=>1,'price'=>0];
            }
        }

        $defaultServiceFee = old('biaya_layanan_utama', 150000);
        $existingReport = old('laporan_perbaikan', $booking->laporan_perbaikan ?? 'Hasil perbaikan: '.$booking->layanan.' selesai. Dilakukan penggantian part tambahan sesuai temuan mekanik agar berkendara lebih stabil.');
    @endphp

    <div class="card" style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;gap:16px;align-items:flex-start;flex-wrap:wrap">
            <div>
                <div class="muted" style="font-weight:800;font-size:12px">DETAIL LAPORAN MEKANIK</div>
                <div style="font-size:22px;font-weight:900;color:#0f172a;margin-top:10px">{{ $booking->kode_booking }}</div>
            </div>
            <div style="text-align:right;min-width:220px">
                <div class="muted" style="font-weight:800;font-size:12px">Status</div>
                <span class="badge badge-warning" style="font-weight:800;text-transform:uppercase">{{ strtoupper(str_replace('_',' ', $booking->status ?? '-')) }}</span>
                <div class="muted" style="margin-top:12px;font-size:12px;font-weight:800">Mekanik</div>
                <div style="font-weight:900;color:#0f172a">{{ $mechanicName }}</div>
            </div>
        </div>

        <div class="summary-grid" style="margin-top:24px">
            <div class="summary-item">
                <span>Pelanggan</span>
                <strong>{{ $booking->nama ?? '-' }}</strong>
            </div>
            <div class="summary-item">
                <span>Unit</span>
                <strong>{{ $booking->kendaraan ?? '-' }}</strong>
            </div>
            <div class="summary-item">
                <span>Layanan</span>
                <strong>{{ $booking->layanan ?? '-' }}</strong>
            </div>
            <div class="summary-item">
                <span>Tanggal / Waktu</span>
                <strong>{{ $booking->tanggal ?? '-' }} / {{ $booking->waktu ?? '-' }}</strong>
            </div>
        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="section-title">⚠️ TEMUAN TAMBAHAN DARI MEKANIK</div>
        <div class="detail-table">
            <table>
                <thead>
                    <tr>
                        <th>Item Suku Cadang</th>
                        <th style="width:170px">Estimasi Harga (Satuan)</th>
                        <th style="width:100px">Qty</th>
                        <th style="width:150px">Bukti Foto</th>
                        <th style="width:220px">Validasi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $idx => $part)
                        <tr class="part-row">
                            <td style="font-weight:800;color:#0f172a">
                                {{ $part['name'] }}
                                @if($booking->status === 'menunggu_konfirmasi_biaya')
                                    <div style="margin-top:6px; font-size:11px; font-weight:900; color:#dc2626;">Notifikasi akan muncul di halaman Repair Invoice</div>
                                @endif
                            </td>
                            <td>
                                <input type="number" name="items[{{ $idx }}][price]" value="{{ $part['price'] ?? 0 }}" min="0" step="1000" class="price-input" required>
                                <input type="hidden" name="items[{{ $idx }}][name]" value="{{ $part['name'] }}">
                            </td>
                            <td>
                                <input type="number" name="items[{{ $idx }}][qty]" value="{{ $part['qty'] ?? 1 }}" min="1" class="qty-input" style="width: 70px;" required>
                            </td>
                            <td>
                                @if(!empty($booking->bukti_pengerjaan_path))
                                    <a href="{{ asset('storage/'.$booking->bukti_pengerjaan_path) }}" target="_blank" class="btn-outline">Lihat Bukti</a>
                                @else
                                    <span class="muted">Tidak ada bukti</span>
                                @endif
                            </td>
                            <td>
                                <div class="radio-group">
                                    <label><input type="radio" name="items[{{ $idx }}][decision]" value="setujui" class="decision-radio" checked> Setujui</label>
                                    <label><input type="radio" name="items[{{ $idx }}][decision]" value="tolak" class="decision-radio"> Tolak</label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#64748b;font-weight:700;padding:20px">Belum ada temuan tambahan dari mekanik.</td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
        <div style="margin-top:18px">
            <div class="muted" style="font-weight:800;font-size:12px;margin-bottom:8px">Catatan Mekanik</div>
            <div style="font-weight:800;color:#334155;line-height:1.7;white-space:pre-wrap">"{{ $booking->mechanic_note ?? 'Tidak ada catatan tambahan.' }}"</div>
        </div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="section-title">💰 KONFIRMASI PEMBAYARAN</div>
        <div class="field-row">
            <div class="field-box">
                <label for="serviceFee">Biaya Layanan Utama</label>
                <input type="number" id="serviceFee" name="biaya_layanan_utama" min="0" step="1000" value="{{ $defaultServiceFee }}">
            </div>
            <div class="field-box">
                <label for="additionalTotal">Total Biaya Tambahan</label>
                <input type="text" id="additionalTotal" readonly value="Rp 0">
            </div>
            <div class="field-box" style="grid-column:1 / -1">
                <label for="grandTotal">TOTAL TAGIHAN AKHIR</label>
                <input type="text" id="grandTotal" readonly value="Rp 0" style="font-size:16px;font-weight:900">
            </div>
        </div>
        <input type="hidden" name="total_biaya_perbaikan" id="totalBiayaPerbaikan" value="{{ $booking->total_biaya_perbaikan ?? 0 }}">
        <div style="margin-top:10px;color:#64748b;font-size:13px">TOTAL TAGIHAN AKHIR akan dihitung otomatis berdasarkan Biaya Layanan Utama dan Total Suku Cadang yang disetujui.</div>
    </div>

    <div class="card" style="margin-bottom:16px">
        <div class="section-title">📝 LAPORAN PERBAIKAN (RINGKAS) - Akan tampil di akun pelanggan</div>
        <textarea name="laporan_perbaikan" rows="5" required style="width:100%;padding:16px;border:1px solid #e2e8f0;border-radius:16px;font-size:14px;color:#0f172a;resize:vertical">{{ $existingReport }}</textarea>
    </div>

    <div class="sticky-actions">
        <div style="display:flex;gap:12px;justify-content:flex-end;flex-wrap:wrap">
            <button type="button" class="btn-outline" onclick="history.back()">⬅️ Kembali</button>
            <button type="submit" class="btn-primary">📩 Konfirmasi & Kirim ke Akun</button>
        </div>
    </div>

</form>

<script>
    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
    }

    function calculateTotals() {
        const rows = document.querySelectorAll('.part-row');
        const serviceFee = Number(document.getElementById('serviceFee')?.value) || 0;
        let additionalTotal = 0;

        rows.forEach(row => {
            const priceInput = row.querySelector('.price-input');
            const qtyInput = row.querySelector('.qty-input');
            const decisionRadio = row.querySelector('.decision-radio:checked');

            // Kalkulasi hanya jika admin memilih 'setujui'
            if (decisionRadio && decisionRadio.value === 'setujui') {
                const price = Number(priceInput.value) || 0;
                const qty = Number(qtyInput.value) || 1;
                additionalTotal += (price * qty);
            }
        });

        const grandTotal = serviceFee + additionalTotal;
        document.getElementById('additionalTotal').value = formatCurrency(additionalTotal);
        document.getElementById('grandTotal').value = formatCurrency(grandTotal);
        document.getElementById('totalBiayaPerbaikan').value = grandTotal;
    }

    document.addEventListener('DOMContentLoaded', function () {
        calculateTotals();
        
        // Listener dinamis untuk deteksi perubahan harga, qty, status setuju/tolak, dan biaya utama
        document.addEventListener('input', function(e) {
            if (e.target.matches('.price-input, .qty-input, #serviceFee')) {
                calculateTotals();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.matches('.decision-radio')) {
                calculateTotals();
            }
        });
    });
</script>

@endsection