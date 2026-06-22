@extends('admin.dashboard-layout')

@section('title','Detail Booking')

@section('heading','Detail Booking')

@section('subheading','Informasi lengkap booking home service')

@section('content')

    <div style="background:#fff; border-radius:14px; padding:18px; border:1px solid rgba(0,0,0,0.08); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);">
        
        <div style="margin-bottom:18px;">
            <h2 style="font-size:18px; font-weight:700; color:#0f172a; margin-bottom:6px;">{{ $booking->kode_booking }}</h2>
            <p style="font-size:13px; color:#64748b;">Dibuat pada: {{ $booking->created_at->format('d M Y H:i') }}</p>
        </div>

        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:16px; margin-bottom:18px;">
            <!-- Info Pelanggan -->
            <div style="background:#f8fafc; border-radius:14px; padding:14px; border:1px solid #e2e8f0;">
                <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:12px;">Informasi Pelanggan</h3>
                <div style="margin-bottom:10px;">
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Nama</p>
                    <p style="font-size:13px; font-weight:600; color:#0f172a;">{{ $booking->nama }}</p>
                </div>
                <div style="margin-bottom:10px;">
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Telepon</p>
                    <p style="font-size:13px; font-weight:600; color:#0f172a;">{{ $booking->telepon }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Alamat</p>
                    <p style="font-size:13px; font-weight:600; color:#0f172a;">{{ $booking->alamat }}</p>
                </div>
            </div>

            <!-- Info Kendaraan -->
            <div style="background:#f8fafc; border-radius:14px; padding:14px; border:1px solid #e2e8f0;">
                <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:12px;">Informasi Kendaraan</h3>
                <div style="margin-bottom:10px;">
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Jenis Kendaraan</p>
                    <p style="font-size:13px; font-weight:600; color:#0f172a;">{{ $booking->kendaraan }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Nomor Polisi</p>
                    <p style="font-size:13px; font-weight:600; color:#0f172a;">{{ $booking->nopol }}</p>
                </div>
            </div>
        </div>

        <!-- Info Layanan -->
        <div style="background:#f8fafc; border-radius:14px; padding:14px; border:1px solid #e2e8f0; margin-bottom:18px;">
            <h3 style="font-size:14px; font-weight:700; color:#0f172a; margin-bottom:12px;">Informasi Layanan</h3>
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:14px;">
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Jenis Layanan</p>
                    <p style="font-size:14px; font-weight:600; color:#0f172a;">{{ $booking->layanan }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Tanggal Layanan</p>
                    <p style="font-size:14px; font-weight:600; color:#0f172a;">{{ $booking->tanggal }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Waktu Layanan</p>
                    <p style="font-size:14px; font-weight:600; color:#0f172a;">{{ $booking->waktu }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Status</p>
                    <p style="font-size:13px; font-weight:600; color:#0f172a; padding:5px 10px; background:#dcfce7; color:#166534; border-radius:6px; display:inline-block;">
                        {{ $booking->status }}
                    </p>
                </div>
            </div>
        </div>

        @if($booking->catatan)
        <!-- Catatan -->
        <div style="background:#eef2ff; border-radius:14px; padding:14px; border:1px solid #c7d2fe; margin-bottom:18px;">
            <h3 style="font-size:14px; font-weight:700; color:#1e3a8a; margin-bottom:10px;">Catatan Pelanggan</h3>
            <p style="font-size:13px; color:#1e3a8a; line-height:1.6;">{{ $booking->catatan }}</p>
        </div>
        @endif




        <!-- Tombol Aksi -->
        <div style="display:flex; gap:10px; margin-top:20px; flex-wrap:wrap; align-items:center;">

            <a href="/admin/home-service" style="padding:10px 18px; background:#e2e8f0; color:#475569; border:none; border-radius:10px; text-decoration:none; font-weight:600; cursor:pointer;">
                Kembali
            </a>

            {{-- Print/Cetak booking detail --}}
            <button type="button" onclick="window.print()" style="padding:10px 18px; background:#f8fafc; color:#0f172a; border:1px solid rgba(148,163,184,0.5); border-radius:10px; font-weight:800; cursor:pointer;">
                🖨️ Cetak Booking
            </button>

            @if($booking->status === 'menunggu_konfirmasi_bukti')
                <form method="POST" action="{{ route('home-service.confirm', $booking->kode_booking) }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="padding:10px 18px; background:#16a34a; color:#fff; border:none; border-radius:10px; font-weight:600; cursor:pointer;">
                        Konfirmasi Pembayaran DP
                    </button>
                </form>
            @endif

        </div>





        @if($booking->status === 'menunggu_pembayaran_final')
            <div style="background:#eef2ff; border-radius:14px; padding:18px; border:1px solid #c7d2fe; margin-top:20px;">
                <h3 style="font-size:14px; font-weight:700; color:#1e3a8a; margin-bottom:10px;">Menunggu Pembayaran Final</h3>
                <p style="margin-bottom:10px; color:#475569;">Nota total pembayaran sudah terkirim ke customer. Customer akan melakukan pelunasan menggunakan QRIS statis.</p>
                <a href="/booking/upload-pelunasan?kode_booking={{ urlencode($booking->kode_booking) }}" target="_blank" style="padding:10px 18px; background:#0f172a; color:#fff; border-radius:10px; text-decoration:none; font-weight:600;">Instruksi / Lihat Halaman Upload Pelunasan</a>

                <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
                    <form method="POST" action="{{ url('/admin/laporan-mekanik/'.$booking->kode_booking.'/revert-konfirmasi-biaya') }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="padding:10px 18px; background:#fef3c7; color:#92400e; border:none; border-radius:10px; font-weight:800; cursor:pointer;">
                            ↩️ Kembalikan ke Butuh Konfirmasi Biaya
                        </button>
                    </form>
                </div>
            </div>
        @endif



        @if($booking->bukti_total_pembayaran_path)
            <div style="background:#f0fdf4; border-radius:14px; padding:14px; border:1px solid #bbf7d0; margin-top:18px;">
                <h3 style="font-size:14px; font-weight:700; color:#166534; margin-bottom:10px;">Bukti Pembayaran Final</h3>
                <a href="{{ asset('storage/' . $booking->bukti_total_pembayaran_path) }}" target="_blank" style="display:inline-block; padding:8px 12px; background:#16a34a; color:#fff; border-radius:8px; text-decoration:none; font-weight:600;">
                    Lihat Bukti Pembayaran Final
                </a>
            </div>
        @endif

        @if($booking->paymentHistories->isNotEmpty())
            <div style="background:#f8fafc; border-radius:14px; padding:18px; border:1px solid #dbeafe; margin-top:20px;">
                <h3 style="font-size:14px; font-weight:700; color:#1e3a8a; margin-bottom:12px;">Riwayat Pembayaran</h3>
                <div style="display:grid; gap:12px;">
                    @foreach($booking->paymentHistories()->latest()->get() as $history)
                        <div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:12px;">
                            <p style="font-size:12px; color:#64748b; margin-bottom:4px;">{{ $history->created_at->format('d M Y H:i') }} · {{ ucfirst(str_replace('_', ' ', $history->action)) }}</p>
                            <p style="font-size:13px; color:#0f172a; margin-bottom:4px;">{{ $history->remarks }}</p>
                            @if($history->amount)
                                <p style="font-size:13px; color:#0f172a;">Nominal: Rp {{ number_format($history->amount, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($booking->status === 'pembayaran_dikonfirmasi')
        <!-- Menu Kirim ke Mekanik (bawah halaman detail booking) -->
        <div style="background:#f0fdf4; border-radius:14px; padding:18px; border:1px solid #bbf7d0; margin-top:20px;">
            <h3 style="font-size:14px; font-weight:700; color:#166534; margin-bottom:12px;">Kirim ke Mekanik</h3>
            <form method="POST" action="{{ route('home-service.sendToMechanic', $booking->kode_booking) }}" style="display:flex; gap:10px; align-items:center;">
                @csrf
                <select name="mekanik_id" required style="flex:1; padding:10px; border:1px solid #86efac; border-radius:8px; font-size:13px;">
                    <option value="">-- Pilih Mekanik --</option>
                    @forelse($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
                    @empty
                        <option value="" disabled>Tidak ada mekanik tersedia</option>
                    @endforelse
                </select>
                <button type="submit" style="padding:10px 18px; background:#16a34a; color:#fff; border:none; border-radius:10px; font-weight:600; cursor:pointer; white-space:nowrap;">
                    ⚙️ Kirim ke Mekanik
                </button>
            </form>
            <div style="margin-top:8px; font-size:12px; color:#64748b; font-weight:600;">
              
            </div>
        </div>
        @endif

    </div>

@endsection

