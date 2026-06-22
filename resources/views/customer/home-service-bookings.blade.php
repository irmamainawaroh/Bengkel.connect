@extends('admin.dashboard-layout')

@section('title', 'Home Service Saya')

@section('heading', 'Home Service Saya')

@section('subheading', 'Lihat status pengerjaan & progress untuk booking home service Anda')

@section('content')

    <div style="background:#fff; border:1px solid rgba(0,0,0,0.08); border-radius:14px; padding:16px;">

        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:14px; flex-wrap:wrap;">
            <div>
                <h3 style="font-size:15px; font-weight:800; color:#0f172a; margin-bottom:6px;">Daftar Booking Home Service</h3>
                <div style="font-size:13px; color:#64748b;">Klik Detail untuk melihat status pengerjaan terbaru.</div>
            </div>
        </div>

        <div class="table-wrap">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                <tr>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Kode Booking</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Layanan</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Status</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Progress</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:center; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings ?? [] as $booking)
                    @php
                        $statusColor = match($booking->status) {
                            'menunggu_pembayaran' => '#cc3a2b',
                            'menunggu_konfirmasi_bukti' => '#16a34a',
                            'pembayaran_dikonfirmasi' => '#0ea5e9',
                            'dikirim_ke_mekanik' => '#8b5cf6',
                            'sedang_dikerjakan' => '#f59e0b',
                            'selesai' => '#16a34a',
                            'Paid (Lunas)' => '#16a34a',
                            'lunas' => '#16a34a',
                            default => '#64748b',
                        };
                    @endphp

                    <tr>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->kode_booking }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->layanan }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:800; color:{{ $statusColor }};">
                            {{ $booking->status ?? '-' }}
                        </td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; color:#0f172a; font-weight:700;">
                            {{ $booking->latest_progress ?? 0 }}%
                        </td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; text-align:center;">
                            <a href="{{ route('customer.home-service.detail', $booking->kode_booking) }}"
                               style="display:inline-block; padding:7px 12px; background:#0f172a; color:#fff; border-radius:8px; text-decoration:none; font-size:12px; font-weight:700;">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:18px 14px; font-size:13px; color:#64748b;">Belum ada booking home service.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

