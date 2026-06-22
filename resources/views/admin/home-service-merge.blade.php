@extends('admin.dashboard-layout')

@section('title','Kelola Home Service')

@section('heading','Kelola Home Service')

@section('subheading','Daftar booking home service (input pelanggan)')

@section('content')

    <div style="margin-bottom:14px;"></div>

    <div id="tab-daftar-home-service" class="tab-pane">

        <div style="display:flex; flex-direction:column; gap:18px;">

        {{-- Daftar Home Service --}}
        <div class="table-wrap">
            <table
                style="width:100%; border-collapse:collapse;"
            >
                <thead>
                    <tr>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Kode Booking</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Nama</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Layanan</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Tanggal</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Mekanik</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Status</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:700; text-align:center; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookingsHomeService as $booking)
                        <tr>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->kode_booking }}</td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->nama }}</td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->layanan }}</td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">{{ $booking->tanggal }}</td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">
                                @if($booking->mekanik_id)
                                    {{ optional(\App\Models\User::find($booking->mekanik_id))->name ?? 'N/A' }}
                                @else
                                    <span style="color:#9ca3af;">Belum ditugaskan</span>
                                @endif
                            </td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:700; color: {{ $booking->status === 'menunggu_pembayaran' ? '#cc3a2b' : '' }} {{ $booking->status === 'menunggu_konfirmasi_bukti' ? '#16a34a' : '' }} {{ $booking->status === 'pembayaran_dikonfirmasi' ? '#0ea5e9' : '' }} {{ $booking->status === 'dikirim_ke_mekanik' ? '#8b5cf6' : '' }} {{ $booking->status === 'sedang_dikerjakan' ? '#f59e0b' : '' }} {{ $booking->status === 'selesai' ? '#16a34a' : '' }};">
                                {{ $booking->status }}
                                @if($booking->status === 'sedang_dikerjakan' || $booking->status === 'selesai')
                                    <div style="margin-top:6px; font-weight:600; font-size:12px; color:#0f172a;">
                                        Total: {{ $booking->total_biaya_perbaikan ?? '-' }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; text-align:center;">
                                <a href="{{ route('home-service.detail', $booking->kode_booking) }}" style="display:inline-block; padding:6px 12px; margin-right:6px; background:#0f172a; color:#fff; border-radius:8px; text-decoration:none; font-size:12px; font-weight:600;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding:18px 14px; font-size:13px; color:#64748b;">Belum ada home service.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>



@endsection


