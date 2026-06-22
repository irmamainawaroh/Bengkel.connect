@extends('admin.dashboard-layout')

@section('title','Kelola Home Service')

@section('heading','Kelola Home Service')

@section('subheading','Daftar booking home service (input pelanggan)')

@section('content')

    <div class="table-wrap" style="overflow-x:auto;">
        <table style="min-width:1200px; width:max-content; border-collapse:collapse;">

            <thead>
                <tr>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Kode Booking</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Nama</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Layanan</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Tanggal</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Waktu</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Alamat</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->kode_booking }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->nama }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->layanan }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->tanggal }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->waktu }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->alamat }}</td>
                        
                        {{-- Mengubah logika pewarnaan text sesuai dengan status baru --}}
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:700; 
                            color: {{ $booking->status === 'menunggu_pembayaran' ? '#cc3a2b' : '' }} 
                                   {{ $booking->status === 'menunggu_konfirmasi_biaya' ? '#eab308' : '' }} 
                                   {{ $booking->status === 'menunggu_konfirmasi_bukti' ? '#16a34a' : '' }};">
                            
                            {{-- Menampilkan teks status agar lebih rapi dibaca di dashboard admin, contoh: Menunggu Konfirmasi Biaya --}}
                            {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:18px 14px; font-size:13px; color:#64748b; text-align: center;">Belum ada home service.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection