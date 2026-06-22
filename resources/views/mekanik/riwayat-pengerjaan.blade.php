@extends('mekanik.dashboard-layout')

@section('title','Riwayat Pengerjaan Mekanik')

@section('heading','Riwayat Pengerjaan')
@section('subheading','Daftar booking yang sudah dikerjakan oleh Anda')

@section('styles')
    <style>
        .history-table-wrap{background:#fff;border:1px solid rgba(0,0,0,0.06);border-radius:16px;overflow:hidden;}
        table{width:100%;border-collapse:collapse;}
        th{background:#f8fafc;padding:14px 16px;font-size:13px;color:#64748b;text-align:left;}
        td{padding:14px 16px;border-top:1px solid #f1f5f9;font-size:14px;color:#0f172a;vertical-align:top;}
        tr:hover td{background:#f8fafc;}
        .badge{padding:8px 12px;border-radius:999px;font-size:12px;font-weight:700;display:inline-block;}
        .badge-antrean{background:#fef3c7;color:#92400e;}
        .badge-dikerjakan{background:#dbeafe;color:#1d4ed8;}
        .badge-selesai{background:#dcfce7;color:#15803d;}
        .empty{padding:28px;text-align:center;color:#64748b;font-weight:600;}
        .muted{color:#64748b;font-size:12px;}
    </style>
@endsection

@section('content')
    @if(session('success'))
        <div style="background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px; border-radius:10px; margin-bottom:12px; font-weight:700;">
            {{ session('success') }}
        </div>
    @endif

    <div class="history-table-wrap">
        <div style="padding:16px 20px;border-bottom:1px solid #f1f5f9;">
            <b>Riwayat Pengerjaan Mekanik</b>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nomor Polisi</th>
                    <th>Kendaraan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings ?? [] as $booking)
                    @php
                        $status = $booking->status ?? 'dikirim_ke_mekanik';
                        $statusLabel = [
                            'dikirim_ke_mekanik' => 'Antrean',
                            'sedang_dikerjakan' => 'Dikerjakan',
                            'selesai' => 'Selesai',
                            'butuh_konfirmasi_biaya' => 'Butuh Konfirmasi Biaya',
                            'menunggu_pembayaran' => 'Menunggu Pembayaran',
                            'menunggu_pembayaran_final' => 'Menunggu Pembayaran Final',
                        ][$status] ?? ucfirst(str_replace('_',' ', $status));

                        $statusClass = [
                            'dikirim_ke_mekanik' => 'badge-antrean',
                            'sedang_dikerjakan' => 'badge-dikerjakan',
                            'selesai' => 'badge-selesai',
                        ][$status] ?? 'badge-dikerjakan';

                        $progress = $booking->latest_progress ?? ($booking->progress_percentage ?? 0);
                    @endphp
                    <tr>
                        <td>{{ $booking->kode_booking }}</td>
                        <td>{{ $booking->nopol ?? '-' }}</td>
                        <td>{{ $booking->kendaraan ?? '-' }}</td>
                        <td>{{ $booking->layanan ?? '-' }}</td>
                        <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                        <td>{{ $progress }}%</td>
                        <td>
                            <a class="btn btn-secondary" style="padding:10px 14px; border-radius:12px; background:#e2e8f0; color:#334155; font-weight:800; text-decoration:none; display:inline-block;" 
href="{{ url('/admin/laporan-mekanik/detail/'.$booking->kode_booking) }}">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty">Belum ada riwayat pengerjaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px" class="muted">
        *Menu ini sudah terhubung ke halaman mekanik. Jika Anda ingin tampilkan detail invoice/konfirmasi biaya, silakan hubungkan ke halaman admin yang sesuai.
    </div>
@endsection

