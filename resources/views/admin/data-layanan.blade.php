@extends('admin.dashboard-layout')

@section('title', 'Data Layanan')

@section('heading', 'Data Layanan')

@section('subheading', 'KELOLA MENU JASA LAYANAN')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; flex-wrap:wrap; margin-bottom:16px">
        <div>
            <h3 style="font-size:16px; font-weight:900; color:#0f172a; margin-bottom:6px;">KELOLA MENU JASA LAYANAN</h3>
            <div style="font-size:12px; color:#64748b; font-weight:700; line-height:1.5;">
                ID <b>L00</b> dipakai untuk penulisan manual pelanggan, sehingga tidak boleh dihapus.
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px; align-items:flex-end;">
            <button type="button" id="btnTambahLayanan" style="padding:10px 14px; background:#dc2626; color:#fff; border-radius:12px; border:none; text-decoration:none; font-weight:800; cursor:pointer;">+ Tambah Layanan Baru</button>
        </div>
    </div>

    @if(session('success'))
        <div style="margin:12px 0 16px; padding:12px 14px; background:#ecfdf5; border:1px solid rgba(16,185,129,.25); border-radius:12px; color:#065f46; font-weight:900;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="margin:12px 0 16px; padding:12px 14px; background:#fef2f2; border:1px solid rgba(239,68,68,.25); border-radius:12px; color:#991b1b; font-weight:900;">
            ❌ {{ session('error') }}
        </div>
    @endif

    @include('admin._popup-tambah-layanan')

    <div class="table-wrap" style="overflow-x:auto; margin-bottom:18px">
        <table style="min-width:900px; width:max-content; border-collapse:collapse;"> 
            <thead>
                <tr>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:900; font-size:13px; text-align:left; border-bottom:1px solid rgba(0,0,0,0.06); width:110px;">ID Jasa</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:900; font-size:13px; text-align:left; border-bottom:1px solid rgba(0,0,0,0.06);">Nama Jasa Layanan</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:900; font-size:13px; text-align:left; border-bottom:1px solid rgba(0,0,0,0.06); width:200px;">Estimasi Harga</th>
                    <th style="padding:12px 14px; background:#f8fafc; color:#334155; font-weight:900; font-size:13px; text-align:left; border-bottom:1px solid rgba(0,0,0,0.06); width:220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                    <tr>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; color:#64748b; font-weight:900;">{{ $s->id_jasa }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:800; color:#0f172a;">{{ $s->nama_jasa }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px; font-weight:800; color:#0f172a;">Rp{{ number_format((int)$s->estimasi_harga, 0, ',', '.') }}</td>
                        <td style="padding:12px 14px; border-bottom:1px solid rgba(0,0,0,0.06); font-size:13px;">
                            <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                                <a href="/admin/data-layanan/edit/{{ $s->id_jasa }}" style="padding:6px 10px; background:#f1f5f9; color:#0f172a; border:1px solid rgba(15,23,42,0.1); border-radius:10px; text-decoration:none; font-weight:900;">Edit</a>


                                @if($s->id_jasa === 'L00' || $s->is_locked)
                                    <span style="padding:6px 10px; background:#fef3c7; color:#92400e; border:1px solid rgba(245,158,11,0.35); border-radius:10px; font-weight:900;">Kunci</span>
                                @else
                                    <form method="POST" action="/admin/data-layanan/{{ $s->id_jasa }}/delete" onsubmit="return confirm('Hapus layanan {{ $s->id_jasa }}?');" style="margin:0">
                                        @csrf
                                        <button type="submit" style="padding:6px 10px; background:#fef2f2; color:#991b1b; border:1px solid rgba(239,68,68,0.25); border-radius:10px; font-weight:900; cursor:pointer;">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:18px 14px; font-size:13px; color:#64748b; text-align:center;">Belum ada data layanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>



@endsection


