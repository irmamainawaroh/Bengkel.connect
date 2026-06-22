{{-- Optional partial untuk menampilkan laporan mekanik di halaman admin --}}

@if(isset($booking) && ($booking->laporan_perbaikan || $booking->total_biaya_perbaikan || $booking->bukti_pengerjaan_path))
    <div style="background:#f0fdf4; border-radius:14px; padding:14px; border:1px solid #bbf7d0; margin-top:18px;">
        <h3 style="font-size:14px; font-weight:800; color:#166534; margin-bottom:10px;">Laporan Pekerjaan Mekanik</h3>

        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:14px;">
            <div>
                <p style="font-size:12px; color:#475569; margin-bottom:4px; font-weight:700;">Total Biaya Perbaikan</p>
                <p style="font-size:13px; color:#0f172a; font-weight:700;">{{ $booking->total_biaya_perbaikan ?? '-' }}</p>
            </div>
            <div>
                <p style="font-size:12px; color:#475569; margin-bottom:4px; font-weight:700;">Waktu Selesai</p>
                <p style="font-size:13px; color:#0f172a; font-weight:700;">{{ $booking->selesai_at ? $booking->selesai_at->format('d M Y H:i') : '-' }}</p>
            </div>
        </div>

        <div style="margin-top:12px;">
            <p style="font-size:12px; color:#475569; margin-bottom:4px; font-weight:700;">Catatan Perbaikan</p>
            <p style="font-size:13px; color:#0f172a; line-height:1.6;">{{ $booking->laporan_perbaikan ?? '-' }}</p>
        </div>

        @if(!empty($booking->mechanic_note))
            <div style="margin-top:12px;">
                <p style="font-size:12px; color:#475569; margin-bottom:4px; font-weight:700;">Catatan Tambahan Mekanik</p>
                <p style="font-size:13px; color:#0f172a; line-height:1.6; white-space:pre-wrap;">{{ $booking->mechanic_note }}</p>
            </div>
        @endif

        @if(!empty($booking->recommended_parts))
            @php
                $parts = [];
                try {
                    $parts = is_string($booking->recommended_parts)
                        ? (json_decode($booking->recommended_parts, true) ?: [])
                        : $booking->recommended_parts;
                } catch (\Exception $e) {
                    $parts = [];
                }
            @endphp
            @if(is_array($parts) && count($parts) > 0)
                <div style="margin-top:12px;">
                    <p style="font-size:12px; color:#475569; margin-bottom:6px; font-weight:700;">Rekomendasi Part</p>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @foreach($parts as $part)
                            <span style="padding:6px 10px; background:#e0f2fe; border:1px solid rgba(14,165,233,0.25); color:#075985; border-radius:999px; font-size:12px; font-weight:700;">
                                {{ $part }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        @if($booking->bukti_pengerjaan_path)

            <div style="margin-top:12px;">
                <p style="font-size:12px; color:#475569; margin-bottom:6px; font-weight:700;">Bukti Pengerjaan</p>
                <a href="{{ asset('storage/' . $booking->bukti_pengerjaan_path) }}" target="_blank" style="display:inline-block; padding:8px 12px; background:#16a34a; color:#fff; border-radius:8px; text-decoration:none; font-weight:700; font-size:12px;">Lihat Bukti</a>
            </div>
        @endif
    </div>
@endif

