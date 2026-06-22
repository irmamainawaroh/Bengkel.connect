@extends('admin.dashboard-layout')

@section('title','Dashboard Admin')
@section('heading','Admin Dashboard')
@section('subheading','BengkelConnect Management')

@section('styles')
    body { background:#f4f4f5; }
@endsection

@section('content')

<style>
    body { background:#f4f4f5; }
    .stats-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
        gap:16px;
        margin-bottom:24px;
    }

    .stat-card{
        background:#fff;
        border-radius:20px;
        padding:24px;
        box-shadow:0 14px 40px rgba(15,23,42,0.08);
        display:flex;
        justify-content:space-between;
        align-items:center;
        min-height:120px;
    }

    .stat-card h4{
        font-size:13px;
        color:#64748b;
        margin-bottom:10px;
    }

    .stat-card h2{
        font-size:30px;
        margin:0;
        color:#0f172a;
    }

    .stat-icon{
        width:56px;
        height:56px;
        border-radius:16px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
    }

    .menu-tab{
        display:flex;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom:24px;
    }

    .menu-tab a{
        padding:12px 20px;
        border-radius:14px;
        text-decoration:none;
        font-weight:600;
        color:#334155;
        background:#f8fafc;
        transition:0.2s ease;
        box-shadow: inset 0 0 0 1px rgba(15,23,42,0.06);
    }

    .menu-tab a:hover{
        background:#e2e8f0;
    }

    .menu-tab a.active{
        background:#dc2626;
        color:white;
        box-shadow:none;
    }

    .table-wrapper{
        background:#fff;
        border-radius:22px;
        overflow:hidden;
        box-shadow:0 16px 45px rgba(15,23,42,0.08);
        border:1px solid rgba(15,23,42,.07);
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        background:#f8fafc;
        padding:18px;
        font-size:13px;
        text-align:left;
        color:#64748b;
    }

    td{
        padding:16px;
        border-top:1px solid #f1f5f9;
        font-size:14px;
        color:#0f172a;
    }

    tr:hover td{
        background:#f8fafc;
    }

    .badge{
        padding:8px 14px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
        display:inline-block;
    }

    .badge-warning{
        background:#fef3c7;
        color:#b45309;
    }

    .badge-success{
        background:#dcfce7;
        color:#15803d;
    }

    .badge-progress{
        background:#dbeafe;
        color:#1d4ed8;
    }

    .btn-detail{
        border:none;
        padding:10px 18px;
        border-radius:14px;
        background: linear-gradient(135deg, #f43f5e 0%, #b91c1c 100%);
        color:white;
        cursor:pointer;
        font-size:13px;
        font-weight:700;
        box-shadow:0 12px 22px rgba(220,38,38,0.18);
        transition:transform .2s, box-shadow .2s;
    }

    .btn-detail:hover{
        transform:translateY(-1px);
        box-shadow:0 18px 26px rgba(220,38,38,0.22);
    }

    .btn-delete{
        border:none;
        background:none;
        color:#dc2626;
        font-size:18px;
        cursor:pointer;
        transition:color .2s;
    }

    .btn-delete:hover{
        color:#991b1b;
    }

    .btn-secondary,
    .btn-primary {
        border:none;
        border-radius:14px;
        padding:12px 18px;
        font-weight:700;
        cursor:pointer;
        transition:transform .2s, box-shadow .2s, background .2s;
    }

    .btn-secondary {
        background:#e2e8f0;
        color:#1e293b;
        box-shadow:0 10px 20px rgba(15,23,42,0.08);
    }

    .btn-secondary:hover {
        background:#cbd5e1;
        transform:translateY(-1px);
    }

    .btn-primary {
        background:#2563eb;
        color:#fff;
        box-shadow:0 10px 22px rgba(37,99,235,0.18);
    }

    .btn-primary:hover {
        background:#1d4ed8;
        transform:translateY(-1px);
    }

    .modal-backdrop {
        position:fixed;
        inset:0;
        display:none;
        align-items:center;
        justify-content:center;
        background:rgba(15,23,42,0.55);
        z-index:1000;
        padding:20px;
    }

    .modal-backdrop.open {
        display:flex;
    }

    .confirm-modal {
        width:100%;
        max-width:460px;
        background:#ffffff;
        border-radius:24px;
        box-shadow:0 30px 80px rgba(15,23,42,0.2);
        padding:32px 30px;
        text-align:left;
        border:1px solid rgba(15,23,42,0.08);
    }

    .confirm-modal h3 {
        margin:0 0 8px;
        font-size:22px;
        color:#111827;
    }

    .confirm-modal p {
        margin:0 0 24px;
        color:#475569;
        font-size:15px;
        line-height:1.75;
    }

    .confirm-modal .modal-icon {
        width:52px;
        height:52px;
        border-radius:16px;
        background:#fee2e2;
        display:flex;
        align-items:center;
        justify-content:center;
        margin-bottom:20px;
        font-size:24px;
        color:#b91c1c;
    }

    .modal-actions {
        display:flex;
        gap:12px;
        justify-content:flex-end;
    }

    .modal-actions .btn-secondary,
    .modal-actions .btn-primary {
        min-width:120px;
    }
</style>

{{-- Statistik --}}
    <div class="stats-grid">

    <div class="stat-card">
        <div>
            <h4>Total Booking Bengkel</h4>
            <h2>{{ $bookingsBengkel->count() }}</h2>
        </div>

        <div class="stat-icon" style="background:#dbeafe;color:#2563eb;">
            <i class="bi bi-building"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <h4>Total Home Service</h4>
            <h2>{{ $bookingsHomeService->count() }}</h2>
        </div>

        <div class="stat-icon" style="background:#fef3c7;color:#ca8a04;">
            <i class="bi bi-house-door"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <h4>Pending (Semua)</h4>
            <h2>
                {{ $bookingsBengkel->where('status','menunggu_pembayaran')->count() + $bookingsHomeService->where('status','menunggu_pembayaran')->count() }}
            </h2>
        </div>

        <div class="stat-icon" style="background:#dcfce7;color:#16a34a;">
            <i class="bi bi-clock"></i>
        </div>
    </div>

</div>

{{-- Menu --}}
{{-- Menu --}}
<div class="menu-tab">

    <a href="/admin/dashboard" class="active">
        Kelola Booking
    </a>

    <a href="/admin/teknisi">
        Kelola Teknisi
    </a>

    <a href="/admin/home-service" class="">
        Kelola Home Service
    </a>

    <a href="/admin/laporan-mekanik" class="">
        Laporan Mekanik
    </a>

    <a href="/admin/laporan-keuangan" class="">
        Laporan Keuangan
    </a>
</div>






{{-- Table (Bengkel & Home Service beda bagian) --}}
<div class="table-wrapper">

    <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9;">
        <b>Booking Bengkel</b>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bookingsBengkel as $booking)
                <tr>
                    <td>{{ $booking->kode_booking }}</td>
                    <td>{{ $booking->nama }}</td>
                    <td>{{ $booking->layanan }}</td>
                    <td>{{ $booking->tanggal }}</td>
                    <td>{{ $booking->waktu }}</td>
                    <td>
                        @if($booking->status == 'menunggu_pembayaran')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($booking->status == 'konfirmasi_pembayaran')
                            <span class="badge badge-progress">Confirmed</span>
                        @elseif($booking->status == 'selesai')
                            <span class="badge badge-success">Completed</span>
                        @endif
                    </td>
<td style="display:flex; gap:10px; align-items:center;">
                        <a href="{{ route('home-service.detail', $booking->kode_booking) }}" class="btn-detail" style="display:inline-block; padding:10px 18px;">Detail</a>
                        <form method="POST" action="/booking/delete" onsubmit="event.preventDefault(); openDeleteModal(this)">
                            @csrf
                            <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
                            <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center; padding:30px;">Belum ada booking bengkel</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding:16px 20px; border-top:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9;">
        <b>Booking Home Service</b>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookingsHomeService as $booking)
                <tr>
                    <td>{{ $booking->kode_booking }}</td>
                    <td>{{ $booking->nama }}</td>
                    <td>{{ $booking->layanan }}</td>
                    <td>{{ $booking->tanggal }}</td>
                    <td>{{ $booking->waktu }}</td>
                    <td>{{ $booking->alamat }}</td>
                    <td>
                        @if($booking->status == 'menunggu_pembayaran')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($booking->status == 'konfirmasi_pembayaran')
                            <span class="badge badge-progress">Confirmed</span>
                        @elseif($booking->status == 'selesai')
                            <span class="badge badge-success">Completed</span>
                        @endif
                    </td>
<td style="display:flex; gap:10px; align-items:center;">
                        <a href="{{ route('home-service.detail', $booking->kode_booking) }}" class="btn-detail" style="display:inline-block; padding:10px 18px;">Detail</a>
                        <form method="POST" action="/booking/delete" onsubmit="event.preventDefault(); openDeleteModal(this)">
                            @csrf
                            <input type="hidden" name="kode_booking" value="{{ $booking->kode_booking }}">
                            <button type="submit" class="btn-delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center; padding:30px;">Belum ada booking home service</td></tr>
            @endforelse
        </tbody>
    </table>

</div>

<div id="deleteModal" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
    <div class="confirm-modal">
        <div class="modal-icon">
            <i class="bi bi-exclamation-lg"></i>
        </div>
        <h3 id="deleteModalTitle">Konfirmasi Hapus Booking</h3>
        <p>Anda akan menghapus booking ini secara permanen. Silakan pastikan kembali sebelum melanjutkan.</p>
        <div class="modal-actions">
            <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
            <button type="button" class="btn-primary" onclick="submitDelete()">Hapus</button>
        </div>
    </div>
</div>

<script>
    let currentDeleteForm = null;

    function openDeleteModal(form) {
        currentDeleteForm = form;
        document.getElementById('deleteModal').classList.add('open');
    }

    function closeModal() {
        currentDeleteForm = null;
        document.getElementById('deleteModal').classList.remove('open');
    }

    function submitDelete() {
        if (!currentDeleteForm) return;
        currentDeleteForm.submit();
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && document.getElementById('deleteModal').classList.contains('open')) {
            closeModal();
        }
    });
</script>

@endsection

