@extends('admin.dashboard-layout')

@section('title','Laporan Mekanik - Admin')
@section('heading','Laporan Mekanik')
@section('subheading','Antrean laporan & konfirmasi biaya dari mekanik')

@section('styles')
    .tabs-inline{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
    .tabs-inline a{padding:10px 14px;border-radius:12px;text-decoration:none;font-weight:800;font-size:13px;background:#f8fafc;color:#334155;border:1px solid rgba(15,23,42,0.06)}
    .tabs-inline a.active{background:#dc2626;color:#fff;border-color:#dc2626}
    .card{padding:22px}
    .table-wrapper{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.05);padding:20px}
    table{width:100%;border-collapse:collapse}
    th{background:#f8fafc;padding:16px;font-size:13px;text-align:left;color:#64748b}
    td{padding:16px;border-top:1px solid #f1f5f9;font-size:14px;color:#0f172a;vertical-align:top}
    .badge{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:800;display:inline-block}
    .badge-antrean{background:#fef3c7;color:#92400e}
    .badge-diagnosis{background:#dbeafe;color:#1d4ed8}
    .badge-dikerjakan{background:#dbf4ff;color:#0369a1}
    .badge-testdrive{background:#f5d0fe;color:#7e22ce}
    .badge-approval{background:#dcfce7;color:#166534}
    .btn-detail{border:none;padding:8px 14px;border-radius:10px;background:#dc2626;color:white;cursor:pointer;font-size:13px;font-weight:800;text-decoration:none;display:inline-block}
@endsection

@section('content')

    {{-- helper untuk memastikan active state tab menu dashboard --}}

    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #bbf7d0;color:#166534;padding:12px;border-radius:10px;margin-bottom:12px;font-weight:800">
            {{ session('success') }}
        </div>
    @endif

    <div class="tabs-inline">
        <a href="{{ url('/admin/laporan-mekanik?filter=proses') }}" class="{{ request('filter') === 'proses' ? 'active' : '' }}">Sedang Dikerjakan / Proses</a>
        <a href="{{ url('/admin/laporan-mekanik?filter=approval') }}" class="{{ request('filter') === 'approval' ? 'active' : '' }}">Butuh Approval / Menunggu Konfirmasi Biaya</a>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width:16%">No. Polisi</th>
                    <th style="width:16%">Nama Mekanik</th>
                    <th style="width:18%">Layanan Utama</th>
                    <th style="width:18%">Progres Terakhir</th>
                    <th style="width:22%">Temuan Tambahan</th>
                    <th style="width:10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    @php
                        $mechanicName = optional($booking->mechanic)->name ?? '-';
                        $recommendedParts = $booking->recommended_parts ?? [];
                        if (is_string($recommendedParts)) {
                            $decoded = json_decode($recommendedParts, true);
                            $recommendedParts = is_array($decoded) ? $decoded : [];
                        }

                        $latestProgressRecord = $booking->progressUpdates->sortByDesc('created_at')->first();
                        $progressPercent = $latestProgressRecord?->progress_percentage ?? 0;
                        $progressNote = trim($latestProgressRecord?->update_text ?? '');
                        $progressAt = $latestProgressRecord?->created_at?->format('d M H:i') ?? '-';

                        $mechanicNote = $booking->mechanic_note ?? null;
                        $additionalText = '';
                        if (!empty($mechanicNote)) {
                            $additionalText = $mechanicNote;
                        }

                        $partsRender = [];
                        foreach((array)$recommendedParts as $p){
                            if (is_array($p)) {
                                $name = $p['name'] ?? ($p[0] ?? null);
                                $qty = $p['qty'] ?? null;
                                if ($name) $partsRender[] = $qty ? ("{$name} x {$qty}") : $name;
                            } else {
                                $partsRender[] = (string)$p;
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $booking->nopol ?? '-' }}</td>
                        <td>{{ $mechanicName }}</td>
                        <td>{{ $booking->layanan ?? '-' }}</td>
                        <td>
                            <div style="font-weight:900;color:#0f172a">{{ $progressPercent }}%</div>
                            <div style="margin-top:4px;color:#475569;font-size:13px;line-height:1.4">{{ $progressNote ?: 'Belum ada catatan progress' }}</div>
                            <div style="margin-top:4px;color:#94a3b8;font-size:12px">{{ $progressAt !== '-' ? 'Terakhir: '.$progressAt : '' }}</div>
                        </td>
                        <td>
                            @php
                                $temuanPartsText = '';
                                if (count($partsRender) > 0) {
                                    $temuanPartsText = implode(', ', $partsRender);
                                }
                                $catatan = !empty($additionalText) ? trim($additionalText) : '';
                            @endphp

                            <div style="display:flex;flex-direction:column;gap:6px">
                                @if(!empty($temuanPartsText) || !empty($catatan))
                                    <div style="font-weight:900;color:#334155;font-size:12px;margin-bottom:2px">Temuan Tambahan</div>
                                    @if(!empty($temuanPartsText))
                                        <div style="color:#075985;font-size:13px;font-weight:800">{{ $temuanPartsText }}</div>
                                    @endif
                                    @if(!empty($catatan))
                                        <div style="color:#475569;font-size:13px;white-space:pre-wrap">{{ $catatan }}</div>
                                    @endif
                                @else
                                    <span style="color:#94a3b8">Tidak ada</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <a class="btn-detail" href="{{ url('/admin/laporan-mekanik/detail/'.$booking->kode_booking) }}">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;padding:30px;color:#64748b;font-weight:800">Belum ada antrean laporan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection