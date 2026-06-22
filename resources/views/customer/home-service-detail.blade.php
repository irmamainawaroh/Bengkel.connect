@extends('admin.dashboard-layout')

@section('title', 'Detail Home Service')

@section('heading', 'Detail Home Service')

@section('subheading', 'Status pengerjaan dan laporan dari mekanik')

@section('content')

    <div style="background:#fff; border-radius:14px; padding:18px; border:1px solid rgba(0,0,0,0.08); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);">

        <div style="margin-bottom:18px;">
            <h2 style="font-size:18px; font-weight:800; color:#0f172a; margin-bottom:6px;">{{ $booking->kode_booking }}</h2>
            <p style="font-size:13px; color:#64748b;">Dibuat pada: {{ $booking->created_at ? $booking->created_at->format('d M Y H:i') : '-' }}</p>
        </div>

        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:16px; margin-bottom:18px;">

            <div style="background:#f8fafc; border-radius:14px; padding:14px; border:1px solid #e2e8f0;">
                <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin-bottom:12px;">Informasi Layanan</h3>
                <div style="margin-bottom:10px;">
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Jenis Layanan</p>
                    <p style="font-size:14px; font-weight:700; color:#0f172a;">{{ $booking->layanan }}</p>
                </div>
                <div style="margin-bottom:10px;">
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Tanggal & Waktu</p>
                    <p style="font-size:14px; font-weight:700; color:#0f172a;">{{ $booking->tanggal }} • {{ $booking->waktu }}</p>
                </div>
                <div>
                    <p style="font-size:12px; color:#64748b; margin-bottom:4px;">Status</p>
                    <p style="font-size:13px; font-weight:800; color:#0f172a; padding:6px 10px; background:#dcfce7; border-radius:6px; display:inline-block;">
                        {{ $booking->status ?? '-' }}
                    </p>
                </div>
            </div>

            <div style="background:#f8fafc; border-radius:14px; padding:14px; border:1px solid #e2e8f0;">
                <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin-bottom:12px;">Progress Pengerjaan</h3>

                <div style="margin-top:6px;">
                    <small style="font-weight:800; color:#64748b;">Progress Terakhir</small>
                    <div style="margin-top:10px; font-weight:900; color:#0f172a; font-size:20px;">
                        {{ $booking->latest_progress ?? 0 }}%
                    </div>

                    <div style="width:100%; height:10px; background:#e2e8f0; border-radius:20px; overflow:hidden; margin-top:10px;">
                        <div style="height:100%; width: {{ $booking->latest_progress ?? 0 }}%; background:#ea580c;"></div>
                    </div>

                    <div style="margin-top:8px; font-size:12px; color:#64748b; font-weight:700;">
                        Update terakhir: {{ $booking->progressUpdates && count($booking->progressUpdates) ? $booking->progressUpdates->first()->created_at->format('d M Y H:i') : '-' }}
                    </div>
                </div>
            </div>

        </div>

        {{-- Riwayat Progress --}}
        @if($booking->progressUpdates && count($booking->progressUpdates) > 0)
            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:14px; margin-bottom:18px;">
                <h3 style="font-size:14px; font-weight:800; color:#0f172a; margin-bottom:12px;">Riwayat Update Progress</h3>
                @foreach($booking->progressUpdates as $update)
                    <div style="background:#f1f5f9; border:1px solid #cbd5e1; border-radius:12px; padding:12px; margin-bottom:10px;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                            <div style="font-size:13px; font-weight:900; color:#ea580c;">
                                {{ $update->progress_percentage }}%
                            </div>
                            <div style="font-size:12px; color:#64748b; font-weight:700;">
                                {{ $update->created_at ? $update->created_at->format('d M Y H:i') : '-' }}
                            </div>
                        </div>
                        <div style="font-size:13px; color:#475569;">
                            {{ $update->update_text }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Laporan Mekanik (total biaya, laporan perbaikan, bukti) --}}
        @if(in_array($booking->status, ['selesai','menunggu_pembayaran_lunas']))
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:14px; padding:14px; margin-bottom:18px;">
                <h3 style="font-size:14px; font-weight:900; color:#166534; margin-bottom:12px;">Laporan Pekerjaan Mekanik</h3>

                <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:14px;">
                    <div>
                        <p style="font-size:12px; color:#475569; font-weight:800; margin-bottom:4px;">Total Biaya Perbaikan</p>
                        <p style="font-size:14px; color:#0f172a; font-weight:900;">{{ $booking->total_biaya_perbaikan ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px; color:#475569; font-weight:800; margin-bottom:4px;">Waktu Selesai</p>
                        <p style="font-size:14px; color:#0f172a; font-weight:900;">{{ $booking->selesai_at ? $booking->selesai_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>

                <div style="margin-top:12px;">
                    <p style="font-size:12px; color:#475569; font-weight:800; margin-bottom:4px;">Catatan/Laporan Perbaikan</p>
                    <p style="font-size:13px; color:#0f172a; line-height:1.6; font-weight:700;">
                        {{ $booking->laporan_perbaikan ?? '-' }}
                    </p>
                </div>

                @if($booking->bukti_pengerjaan_path)
                    <div style="margin-top:12px;">
                        <p style="font-size:12px; color:#475569; font-weight:800; margin-bottom:6px;">Bukti Pengerjaan</p>
                        <a href="{{ asset('storage/' . $booking->bukti_pengerjaan_path) }}" target="_blank" style="display:inline-block; padding:8px 12px; background:#16a34a; color:#fff; border-radius:10px; text-decoration:none; font-size:12px; font-weight:900;">
                            Lihat Bukti
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <div style="display:flex; gap:10px;">
            <a href="/customer/home-service" style="padding:10px 18px; background:#e2e8f0; color:#475569; border-radius:10px; text-decoration:none; font-weight:800; font-size:13px;">
                Kembali ke daftar
            </a>
        </div>

    </div>
@endsection

