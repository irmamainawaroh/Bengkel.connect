@extends('admin.dashboard-layout')

@section('title', 'Data Layanan - Tidak Ditemukan')

@section('heading', 'Data Layanan')

@section('subheading', 'Halaman tidak ditemukan')

@section('content')
    <div style="background:#fff; border:1px solid rgba(0,0,0,0.08); border-radius:16px; padding:18px; max-width:720px;">
        <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin-bottom:10px;">Halaman Data Layanan tidak ditemukan</h3>
        <p style="font-size:13px; color:#64748b; font-weight:700; line-height:1.6; margin-bottom:16px;">
            URL yang Anda buka tidak tersedia. Pastikan route `/admin/data-layanan` sudah didaftarkan.
        </p>

        <a href="/admin/data-layanan" style="display:inline-block; padding:10px 14px; background:#dc2626; color:#fff; border-radius:12px; text-decoration:none; font-weight:900;">
            Kembali ke Data Layanan
        </a>
    </div>
@endsection

