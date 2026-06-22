@extends('admin.dashboard-layout')

@section('title','Lihat Bukti DP')

@section('heading','Lihat Bukti DP')

@section('subheading','Tampilan bukti pembayaran DP customer untuk booking home service')

@section('content')

    <div style="background:#fff; border-radius:14px; padding:22px; border:1px solid rgba(0,0,0,0.08); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:18px;">
            <div>
                <h2 style="font-size:18px; font-weight:700; color:#0f172a; margin-bottom:6px;">{{ $booking->kode_booking }}</h2>
                <p style="font-size:13px; color:#64748b;">Booking: {{ $booking->nama }} · {{ $booking->telepon }}</p>
            </div>
            <a href="{{ route('home-service.detail', $booking->kode_booking) }}" style="padding:10px 18px; background:#e2e8f0; color:#475569; border-radius:10px; text-decoration:none; font-weight:600;">Kembali ke Detail Booking</a>
        </div>

        <div style="background:#f0fdf4; border-radius:14px; padding:20px; border:1px solid #bbf7d0;">
            <h3 style="font-size:15px; font-weight:700; color:#166534; margin-bottom:12px;">Bukti Pembayaran DP</h3>
            <p style="font-size:13px; color:#334155; margin-bottom:16px;">Berikut adalah bukti pembayaran DP yang diunggah oleh customer.</p>

            @if(in_array($dpExtension, ['jpg', 'jpeg', 'png', 'gif']))
                <img src="{{ $dpUrl }}" alt="Bukti DP {{ $booking->kode_booking }}" style="width:100%; max-width:900px; border-radius:16px; border:1px solid #cbd5e1;" />
            @elseif($dpExtension === 'pdf')
                <iframe src="{{ $dpUrl }}" style="width:100%; min-height:720px; border:1px solid #cbd5e1; border-radius:16px;"></iframe>
            @else
                <p style="font-size:13px; color:#475569;">Tipe file tidak didukung untuk preview. Silakan gunakan tombol unduh di bawah ini.</p>
            @endif

            <div style="margin-top:18px; display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ $dpUrl }}" target="_blank" style="display:inline-flex; align-items:center; justify-content:center; padding:12px 18px; background:#16a34a; color:#fff; border-radius:12px; text-decoration:none; font-weight:700;">Buka/Pindai Bukti DP</a>
                <a href="{{ $dpUrl }}" download style="display:inline-flex; align-items:center; justify-content:center; padding:12px 18px; background:#0f172a; color:#fff; border-radius:12px; text-decoration:none; font-weight:700;">Unduh Bukti DP</a>
            </div>
        </div>
    </div>

@endsection
