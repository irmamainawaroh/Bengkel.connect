@extends('admin.dashboard-layout')

@section('title', 'Edit Data Layanan')

@section('heading', 'Edit Data Layanan')

@section('subheading', 'Perbarui detail jasa layanan')

@section('content')
    <div style="background:#fff; border:1px solid rgba(0,0,0,0.08); border-radius:16px; padding:18px; max-width:560px;">
        <h3 style="font-size:14px; font-weight:900; color:#0f172a; margin-bottom:12px;">Edit: {{ $service->id_jasa }}</h3>

        @if($service->is_locked)
            <div style="background:#fef3c7; border:1px solid rgba(245,158,11,0.35); color:#92400e; padding:12px; border-radius:12px; font-weight:900; margin-bottom:14px;">
                ID L00 terkunci. Anda tidak dapat mengubah/menghapusnya.
            </div>
        @endif

        <form method="POST" action="/admin/data-layanan/{{ $service->id_jasa }}/update" style="display:grid; gap:12px;">
            @csrf
            <div style="display:grid; gap:6px">
                <label style="font-size:12px; color:#64748b; font-weight:800">Nama Jasa Layanan</label>
                <input type="text" name="nama_jasa" value="{{ old('nama_jasa', $service->nama_jasa) }}" required maxlength="255" style="padding:10px; border:1px solid #e6edf3; border-radius:10px" @if($service->is_locked) disabled @endif>
            </div>

            <div style="display:grid; gap:6px">
                <label style="font-size:12px; color:#64748b; font-weight:800">Estimasi Harga</label>
                <input type="number" name="estimasi_harga" min="0" value="{{ old('estimasi_harga', (int)$service->estimasi_harga) }}" required style="padding:10px; border:1px solid #e6edf3; border-radius:10px" @if($service->is_locked) disabled @endif>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;">
                <a href="/admin/data-layanan" style="padding:10px 14px; background:#e2e8f0; color:#475569; border-radius:12px; text-decoration:none; font-weight:900;">Kembali</a>

                @if(!$service->is_locked)
                    <button type="submit" style="padding:10px 16px; background:#16a34a; color:#fff; border:none; border-radius:12px; font-weight:900; cursor:pointer;">Simpan</button>
                @endif
            </div>
        </form>
    </div>
@endsection

