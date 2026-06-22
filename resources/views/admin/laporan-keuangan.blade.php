@extends('admin.dashboard-layout')

@section('title','Laporan Keuangan & Pendapatan Admin')
@section('heading','Laporan Keuangan & Pendapatan Admin')
@section('subheading','Ringkasan pendapatan berdasarkan bookings (lunas/selesai)')

@section('content')
@php
    $fmt = fn($v) => 'Rp ' . number_format((float)$v, 0, ',', '.');
@endphp

<style>
    .wrap-fin {
        background:#fff;
        border:1px solid rgba(15,23,42,0.08);
        border-radius:22px;
        box-shadow:0 14px 45px rgba(15,23,42,0.08);
        overflow:hidden;
    }
    .fin-head {
        padding:18px 22px;
        background:#f8fafc;
        border-bottom:1px solid rgba(15,23,42,0.06);
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:16px;
        flex-wrap:wrap;
    }
    .fin-head .title {
        font-weight:1000;
        color:#0f172a;
        font-size:14px;
        text-transform:uppercase;
        letter-spacing:0.4px;
    }
    .fin-head .period {
        display:flex;
        align-items:center;
        gap:12px;
        flex-wrap:wrap;
    }
    .fin-head input {
        padding:10px 12px;
        border-radius:12px;
        border:1px solid rgba(15,23,42,0.1);
        font-weight:700;
        color:#0f172a;
        background:#fff;
    }
    .fin-head .btn-run {
        padding:10px 16px;
        border-radius:12px;
        border:none;
        background:#dc2626;
        color:#fff;
        font-weight:900;
        cursor:pointer;
    }
    .stats-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
        gap:16px;
        padding:22px;
    }
    .stat-card{
        background:#fff;
        border:1px solid rgba(15,23,42,0.07);
        border-radius:20px;
        padding:18px;
        box-shadow:0 12px 30px rgba(15,23,42,0.06);
    }
    .stat-card h4{
        font-size:13px;
        color:#64748b;
        margin-bottom:10px;
        font-weight:1000;
    }
    .stat-card h2{
        font-size:28px;
        margin:0;
        color:#0f172a;
    }

    .section { padding: 0 22px 22px; }
    .section-title {
        font-weight:1000;
        color:#0f172a;
        margin-bottom:10px;
        font-size:14px;
        text-transform:uppercase;
        letter-spacing:0.3px;
    }

    .table-card {
        background:#fff;
        border:1px solid rgba(15,23,42,0.07);
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 12px 30px rgba(15,23,42,0.06);
    }
    table { width:100%; border-collapse:collapse; }
    th {
        padding:14px 16px;
        background:#f8fafc;
        text-align:left;
        font-size:13px;
        color:#64748b;
        border-bottom:1px solid rgba(15,23,42,0.06);
        font-weight:1000;
    }
    td {
        padding:14px 16px;
        border-bottom:1px solid rgba(15,23,42,0.05);
        font-size:13px;
        color:#0f172a;
        font-weight:800;
    }
    tr:last-child td { border-bottom:none; }

    .btn-link {
        display:inline-block;
        padding:10px 14px;
        border-radius:12px;
        background:#e2e8f0;
        color:#334155;
        font-weight:1000;
        text-decoration:none;
        border:1px solid rgba(15,23,42,0.06);
    }

    .chart-placeholder {
        height:220px;
        border-radius:18px;
        border:1px dashed rgba(15,23,42,0.18);
        background:linear-gradient(180deg,#f8fafc,#fff);
        display:flex;
        align-items:center;
        justify-content:center;
        color:#64748b;
        font-weight:1000;
        text-align:center;
        padding:20px;
    }

    @media (max-width: 768px) {
        .fin-head { padding:16px; }
        .stats-grid { padding:16px; }
        .section { padding:0 16px 16px; }
    }
</style>

<div class="wrap-fin">

    <div class="fin-head">
        <div>
            <div class="title">BENGKEL CONNECT > LAPORAN KEUANGAN & PENDAPATAN ADMIN</div>
            <div style="margin-top:6px;color:#64748b;font-weight:800;font-size:13px;">Sumber data: tabel bookings (status: lunas/selesai, alamat tidak null)</div>
        </div>

        <form class="period" method="GET" action="/admin/laporan-keuangan">
            <input type="date" name="start" value="{{ $start }}" />
            <span style="font-weight:1000;color:#64748b;">s/d</span>
            <input type="date" name="end" value="{{ $end }}" />
            <button class="btn-run" type="submit">Tampilkan</button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h4>💰 Total Pendapatan Kotor (Jasa)</h4>
            <h2>{{ $fmt($totalPendapatanKotor) }}</h2>
        </div>
        <div class="stat-card">
            <h4>📦 Total Pengeluaran Stok</h4>
            <h2>{{ $fmt($totalPengeluaranStok) }}</h2>
        </div>
        <div class="stat-card">
            <h4>✅ Pendapatan Bersih</h4>
            <h2>{{ $fmt($pendapatanBersih) }}</h2>
        </div>
    </div>

    <div class="section">
        <div class="section-title">📈 Grafik Tren Transaksi Minggu Ini (placeholder)</div>
        <div class="chart-placeholder">
            Grafik belum tersedia karena belum ada chart library di project.
            Data harian sudah tersedia di tabel di bawah.
        </div>
    </div>

    <div class="section">
        <div class="section-title">📅 Laporan Pendapatan Harian</div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:160px;">Tanggal</th>
                        <th style="width:160px;">Jml Transaksi</th>
                        <th>Pendapatan Jasa</th>
                        <th>Penjualan Suku Cadang</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyRows as $row)
                        <tr>
                            <td>{{ 
                                \Carbon\Carbon::parse($row['tanggal'])->format('d M Y')
                            }}</td>
                            <td>{{ $row['jml_transaksi'] }}</td>
                            <td>{{ $fmt($row['pendapatan_jasa']) }}</td>
                            <td>{{ $fmt($row['penjualan_suku_cadang']) }}</td>
                            <td>{{ $fmt($row['total_pendapatan']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#64748b;font-weight:900;padding:24px;">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:12px;">
            <a class="btn-link" href="{{ url('/admin/laporan-keuangan/download-harian?start='.$start.'&end='.$end) }}">📥 Download Laporan Harian (.Excel)</a>
        </div>
    </div>

    <div class="section">
        <div class="section-title">📆 Rekapitulasi Pendapatan Bulanan (Tahun {{ now()->year }})</div>

        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th style="width:170px;">Total Transaksi</th>
                        <th>Total Jasa</th>
                        <th>Total Suku Cadang</th>
                        <th>Total Net Profit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyRows as $row)
                        <tr>
                            <td>{{ $row['bulan'] }}</td>
                            <td>{{ $row['total_transaksi'] }}</td>
                            <td>{{ $fmt($row['total_jasa']) }}</td>
                            <td>{{ $fmt($row['total_suku_cadang']) }}</td>
                            <td>{{ $fmt($row['total_net_profit']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#64748b;font-weight:900;padding:24px;">Tidak ada data bulanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:12px;">
            <a class="btn-link" href="{{ url('/admin/laporan-keuangan/download-bulanan?year='.now()->year) }}">📥 Download Laporan Bulanan (.PDF)</a>
        </div>
    </div>

</div>
@endsection

