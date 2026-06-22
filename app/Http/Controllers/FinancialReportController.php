<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinancialReportController extends Controller
{
    private function ensureAdmin()
    {
        if (!session('id_user') || !session('role')) {
            abort(403);
        }

        $role = session('role');
        $roleStr = is_string($role) ? trim(strtolower($role)) : '';
        if ($roleStr !== 'admin') {
            $containsAdmin = is_string($role) && stripos($role, 'admin') !== false;
            if (!$containsAdmin) {
                abort(403);
            }
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $start = $request->query('start');
        $end = $request->query('end');

        $today = now()->toDateString();
        $start = $start ? date('Y-m-d', strtotime($start)) : now()->subDays(6)->toDateString();
        $end = $end ? date('Y-m-d', strtotime($end)) : $today;

        $baseQuery = Booking::query()
            ->whereNotNull('alamat')
            ->whereIn('status', ['lunas', 'selesai', 'Paid (Lunas)'])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end);

        $bookings = $baseQuery->get();

        $totalPendapatanKotor = (float) $bookings->sum(fn($b) => (float) ($b->total_biaya_perbaikan ?? 0));
        $totalPengeluaranStok = 0.0;
        $pendapatanBersih = $totalPendapatanKotor - $totalPengeluaranStok;

        $daily = $bookings
            ->groupBy(fn($b) => $b->created_at ? $b->created_at->toDateString() : null)
            ->filter(fn($group) => !is_null($group->keys()[0] ?? null))
            ->sortKeys();

        $dailyRows = $daily->map(function ($group, $date) {
            $count = $group->count();
            $pendapatanJasa = (float) $group->sum(fn($b) => (float) ($b->total_biaya_perbaikan ?? 0));
            $penjualanSukuCadang = 0.0;

            return [
                'tanggal' => $date,
                'jml_transaksi' => $count,
                'pendapatan_jasa' => $pendapatanJasa,
                'penjualan_suku_cadang' => $penjualanSukuCadang,
                'total_pendapatan' => $pendapatanJasa + $penjualanSukuCadang,
            ];
        })->values();

        $year = now()->year;

        $monthBookings = Booking::query()
            ->whereNotNull('alamat')
            ->whereIn('status', ['lunas', 'selesai', 'Paid (Lunas)'])
            ->whereYear('created_at', $year)
            ->get();

        $monthly = $monthBookings
            ->groupBy(fn($b) => $b->created_at ? $b->created_at->format('Y-m') : null)
            ->filter(fn($group) => !is_null($group->keys()[0] ?? null))
            ->sortKeys();

        $monthlyRows = $monthly->map(function ($group, $ym) {
            $count = $group->count();
            $totalJasa = (float) $group->sum(fn($b) => (float) ($b->total_biaya_perbaikan ?? 0));
            $totalSukuCadang = 0.0;

            return [
                'bulan' => date('F Y', strtotime($ym . '-01')),
                'total_transaksi' => $count,
                'total_jasa' => $totalJasa,
                'total_suku_cadang' => $totalSukuCadang,
                'total_net_profit' => $totalJasa - 0.0,
            ];
        })->values();

        return view('admin.laporan-keuangan', [
            'start' => $start,
            'end' => $end,
            'bookings' => $bookings,
            'totalPendapatanKotor' => $totalPendapatanKotor,
            'totalPengeluaranStok' => $totalPengeluaranStok,
            'pendapatanBersih' => $pendapatanBersih,
            'dailyRows' => $dailyRows,
            'monthlyRows' => $monthlyRows,
        ]);
    }

    public function downloadHarian(Request $request): StreamedResponse
    {
        $this->ensureAdmin();

        $start = $request->query('start');
        $end = $request->query('end');

        $today = now()->toDateString();
        $start = $start ? date('Y-m-d', strtotime($start)) : now()->subDays(6)->toDateString();
        $end = $end ? date('Y-m-d', strtotime($end)) : $today;

        $bookings = Booking::query()
            ->whereNotNull('alamat')
            ->whereIn('status', ['lunas', 'selesai'])
            ->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
            ->get();

        $daily = $bookings
            ->groupBy(fn($b) => $b->created_at ? $b->created_at->toDateString() : null)
            ->filter(fn($group) => !is_null($group->keys()[0] ?? null))
            ->sortKeys();

        $filename = sprintf('laporan-keuangan-harian-%s-sd-%s.csv', $start, $end);

        $headers = [
            'Tanggal',
            'Jml Transaksi',
            'Pendapatan Jasa',
            'Penjualan Suku Cadang',
            'Total Pendapatan',
        ];

        $response = new StreamedResponse(function () use ($daily, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            foreach ($daily as $date => $group) {
                $count = $group->count();
                $pendapatanJasa = (float) $group->sum(fn($b) => (float) ($b->total_biaya_perbaikan ?? 0));
                $penjualanSukuCadang = 0.0;
                $totalPendapatan = $pendapatanJasa + $penjualanSukuCadang;

                fputcsv($out, [
                    $date,
                    $count,
                    $pendapatanJasa,
                    $penjualanSukuCadang,
                    $totalPendapatan,
                ]);
            }

            fclose($out);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function downloadBulanan(Request $request): StreamedResponse
    {
        $this->ensureAdmin();

        $year = (int) ($request->query('year') ?? now()->year);

        $monthBookings = Booking::query()
            ->whereNotNull('alamat')
            ->whereIn('status', ['lunas', 'selesai'])
            ->whereYear('created_at', $year)
            ->get();

        $monthly = $monthBookings
            ->groupBy(fn($b) => $b->created_at ? $b->created_at->format('Y-m') : null)
            ->filter(fn($group) => !is_null($group->keys()[0] ?? null))
            ->sortKeys();

        $filename = sprintf('laporan-keuangan-bulanan-%d.csv', $year);

        $headers = [
            'Bulan',
            'Total Transaksi',
            'Total Jasa',
            'Total Suku Cadang',
            'Total Net Profit',
        ];

        $response = new StreamedResponse(function () use ($monthly, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);

            foreach ($monthly as $ym => $group) {
                $count = $group->count();
                $totalJasa = (float) $group->sum(fn($b) => (float) ($b->total_biaya_perbaikan ?? 0));
                $totalSukuCadang = 0.0;
                $net = $totalJasa - 0.0;

                $bulanName = date('F Y', strtotime($ym . '-01'));

                fputcsv($out, [
                    $bulanName,
                    $count,
                    $totalJasa,
                    $totalSukuCadang,
                    $net,
                ]);
            }

            fclose($out);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}

