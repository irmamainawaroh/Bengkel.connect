@extends('admin.dashboard-layout')

@section('title', 'Riwayat Nota Lunas')

@section('heading', 'Riwayat Nota Lunas')

@section('subheading', 'Daftar transaksi yang sudah lunas / Paid')

@section('content')

{{-- CSS untuk menghapus sidebar dan memaksa seluruh halaman meregang lebar ke samping --}}
<style>
    /* 1. Sembunyikan sidebar secara total */
    .sidebar, aside, [class*="sidebar"], [id*="sidebar"] {
        display: none !important;
        width: 0 !important;
    }
    
    /* 2. Reset paksa pembungkus luar agar menggunakan 100% lebar layar */
    .wrapper, .container-fluid, .app-body, [class*="-wrapper"], [class*="container"] {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    /* 3. Paksa area konten utama melebar penuh tanpa batas margin kiri */
    .main-content, main, .content-page, [class*="main-content"], [class*="content-"] {
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 10px !important;  /* Sedikit ruang agar tidak terlalu menempel ke tepi layar */
        padding-right: 10px !important;
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }
</style>

<div style="background:#fff; border:1px solid rgba(0,0,0,0.08); border-radius:14px; padding:24px; width: 100%; box-sizing: border-box;">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <div>
            <h3 style="font-size:16px; font-weight:800; color:#0f172a; margin-bottom:6px;">Riwayat Nota yang Sudah Lunas</h3>
            <div style="font-size:13px; color:#64748b;">Menampilkan nota/tagihan final untuk status: lunas, Paid (Lunas), atau selesai.</div>
        </div>
    </div>

    <div class="table-wrap" style="overflow-x:auto; width: 100%;">
        <table style="width:100%; border-collapse:collapse; table-layout: auto;">
            <thead>
            <tr>
                <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Kode Booking</th>
                <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Layanan</th>
                <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Status</th>
                <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Total Tagihan</th>
                <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Waktu Lunas</th>
                <th style="padding:14px; background:#f8fafc; color:#334155; font-weight:700; text-align:center; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bookings ?? [] as $booking)

                @php
                    $total = $booking->total_biaya_perbaikan ?? $booking->total_biaya ?? 0;
                    $totalFormatted = 'Rp ' . number_format((float)$total, 0, ',', '.');

                    $status = (string)($booking->status ?? '-');
                    $statusColor = match(true) {
                        $status === 'Paid (Lunas)' || $status === 'lunas' || $status === 'selesai' => '#16a34a',
                        default => '#64748b',
                    };

                    $timeLabel = $booking->lunas_at ?? $booking->updated_at;
                    $timeText = $timeLabel ? $timeLabel->format('d M Y H:i') : '-';
                @endphp

                <tr>
                    <td style="padding:14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->kode_booking }}</td>
                    <td style="padding:14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->layanan }}</td>
                    <td style="padding:14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:800; color:{{ $statusColor }};">
                        {{ $booking->status ?? '-' }}
                    </td>
                    <td style="padding:14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:900; color:#0f172a;">{{ $totalFormatted }}</td>
                    <td style="padding:14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; color:#334155;">{{ $timeText }}</td>
                    <td style="padding:14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; text-align:center;">
                        <a href="{{ route('customer.repair-invoice', $booking->kode_booking) }}"
                           style="display:inline-block; padding:8px 14px; background:#0f172a; color:#fff; border-radius:8px; text-decoration:none; font-size:12px; font-weight:700;">
                            Invoice
                        </a>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" style="padding:24px 14px; text-align:center; font-size:13px; color:#64748b;">Belum ada nota lunas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection