@extends('admin.dashboard-layout')

@section('title', 'Master Layanan Home Service')

@section('heading', 'Master Layanan Home Service')

@section('subheading', 'Daftar layanan yang dipilih customer saat booking home service')

@section('content')
    @php
        $layananList = [
            'Ganti Oli Mesin & Filter Oli',
            'Ganti Filter Udara & Filter Kabin',
            'Spooring & Balancing 4 Roda',
            'Ganti Shockbreaker / Strut',
            'Ganti Link Stabilizer / Tierod / Ball Joint',
            'Rotasi Ban',
            'Ganti Kampas Rem (Dispad / Brake Shoe)',
            'Bubut Piringan Cakram (Disc Brake)',
            'Kuras & Ganti Minyak Rem (Brake Fluid)',
            'Ganti/Kuras Oli Transmisi (Manual / Matic ATF/CVT)',
            'Ganti Set Kopling (Clutch Kit - Manual)',
            'Kalibrasi / Scan Transmisi Otomatis',
            'Ganti Aki (Accu) + Cek Alternator',
            'Jamper Aki / Perbaikan Sekring & Kabel',
            'Lainnya (Opsional)',
        ];
    @endphp

    <div style="background:#fff; border:1px solid rgba(0,0,0,0.08); border-radius:16px; padding:18px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:14px;">
            <div>
                <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin-bottom:6px;">Daftar Layanan</h3>
                <div style="font-size:13px; color:#64748b; font-weight:600;">Menampilkan opsi yang sama seperti form customer.</div>
            </div>
            <div style="font-size:12px; color:#64748b; font-weight:900; background:#f8fafc; border:1px solid #e2e8f0; padding:8px 12px; border-radius:12px;">
                Total: {{ count($layananList) }} layanan
            </div>
        </div>

        <div class="table-wrap" style="overflow-x:auto;">
            <table style="min-width:800px; width:max-content; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:800; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06); width:70px;">No</th>
                        <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:800; text-align:left; font-size:13px; border-bottom:1px solid rgba(0,0,0,0.06);">Nama Layanan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($layananList as $idx => $layanan)
                        <tr>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; color:#64748b; font-weight:800;">{{ $idx+1 }}</td>
                            <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:700; color:#0f172a;">{{ $layanan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:14px; font-size:12px; color:#64748b; font-weight:700; line-height:1.6;">
            Catatan: halaman ini bersifat read-only (untuk kebutuhan menu). Jika kamu ingin CRUD master layanan, bisa kita buatkan setelah ini.
        </div>
    </div>
@endsection

