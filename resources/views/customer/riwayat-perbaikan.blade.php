@extends('admin.dashboard-layout')

@section('title', 'Riwayat Perbaikan')

@section('heading', 'Riwayat Perbaikan')

@section('subheading', 'Riwayat booking & status pelunasan customer')

@section('content')

<div style="background:#fff; border:1px solid rgba(0,0,0,0.08); border-radius:14px; padding:16px;">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px; flex-wrap:wrap;">
        <div>
            <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin-bottom:6px;">Daftar Riwayat Perbaikan</h3>
            <div style="font-size:13px; color:#64748b;">Menampilkan histori booking Anda.</div>
        </div>
    </div>

    <div class="table-wrap" style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; min-width:780px;">
            <thead>
            <tr>
                <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Kode Booking</th>
                <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Layanan</th>
                <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Status</th>
                <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Total Tagihan</th>
                <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Waktu</th>
                <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:center; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($bookings ?? [] as $booking)
                @php
                    $total = $booking->total_biaya_perbaikan ?? 0;
                    if (($booking->status ?? null) === 'Paid (Lunas)') {
                        $total = 0;
                    }
                    $totalFormatted = 'Rp ' . number_format((float)$total, 0, ',', '.');

                    $statusColor = match($booking->status) {
                        'Paid (Lunas)' => '#16a34a',
                        'lunas' => '#16a34a',
                        'selesai' => '#16a34a',
                        default => '#64748b',
                    };

                    $timeLabel = $booking->lunas_at ?? $booking->updated_at;
                    $timeText = $timeLabel ? $timeLabel->format('d M Y H:i') : '-';
                @endphp

                <tr>
                    <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->kode_booking }}</td>
                    <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->layanan }}</td>
                    <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:800; color:{{ $statusColor }};">
                        {{ $booking->status ?? '-' }}
                    </td>
                    <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:900; color:#0f172a;">{{ $totalFormatted }}</td>
                    <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; color:#334155;">{{ $timeText }}</td>
                    <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; text-align:center;">
                        <a href="{{ route('customer.repair-invoice', $booking->kode_booking) }}" 
                           style="display:inline-block; padding:7px 12px; background:#0f172a; color:#fff; border-radius:8px; text-decoration:none; font-size:12px; font-weight:700;">
                            Invoice
                        </a>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:18px 14px; font-size:13px; color:#64748b;">Belum ada riwayat perbaikan.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

