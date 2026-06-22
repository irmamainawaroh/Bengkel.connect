<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerHomeServiceController extends Controller
{
    public function showMyBookings()
    {
        $customerId = session('id_user');
        if (!$customerId) {
            abort(403);
        }

        $bookings = Booking::where('user_id', $customerId)
            ->with('progressUpdates')
            ->orderBy('created_at', 'desc')
            ->get();

        // Normalisasi field untuk tampilan riwayat:
        // jika status Paid (Lunas) maka nilai tagihan yang tampil menjadi 0.
        $bookings->transform(function (Booking $booking) {
            if (($booking->status ?? null) === 'Paid (Lunas)') {
                $booking->total_biaya_perbaikan = 0;
            }
            return $booking;
        });

        return view('customer.home-service-bookings', compact('bookings'));
    }

    public function showDetail(string $kodeBooking)
    {
        $customerId = session('id_user');
        if (!$customerId) {
            abort(403);
        }

        $bookingQuery = Booking::where('kode_booking', $kodeBooking)
            ->where('user_id', $customerId)
            ->with('progressUpdates');

        $booking = $bookingQuery->first();

        if (!$booking) {
            abort(404, 'Booking tidak ditemukan untuk kodeBooking=' . $kodeBooking . ' user_id=' . $customerId);
        }

        return view('customer.home-service-detail', compact('booking'));
    }

    public function showRepairHistory()
    {
        $customerId = session('id_user');
        if (!$customerId) {
            abort(403);
        }

        $bookings = Booking::where('user_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Normalisasi untuk tampilan:
        // - Paid (Lunas) dan lunas => tampilkan Rp 0 (karena sudah lunas)
        $bookings->transform(function (Booking $booking) {
            $status = strtolower(trim((string)($booking->status ?? '')));
            if (in_array($status, ['paid (lunas)', 'lunas', 'selesai'], true)) {
                $booking->total_biaya_perbaikan = 0;
            }

            return $booking;
        });

        return view('customer.riwayat-perbaikan', compact('bookings'));
    }

    /**
     * Riwayat nota yang sudah lunas/paid/selesai.
     */
    public function showRepairInvoiceLunas()
    {
        $customerId = session('id_user');
        if (!$customerId) {
            abort(403);
        }

        $allowed = ['lunas', 'Paid (Lunas)', 'selesai'];

        $bookings = Booking::query()
            ->where('user_id', $customerId)
            ->whereIn('status', $allowed)
            ->orderBy('lunas_at', 'desc')
            ->get();

        return view('customer.riwayat-nota-lunas', compact('bookings'));
    }



    public function showRepairInvoice(string $kodeBooking)
    {
        $customerId = session('id_user');
        if (!$customerId) {
            abort(403);
        }

        $kodeBookingNormalized = trim($kodeBooking);

        // Status di sistem Anda tampaknya punya variasi penamaan (case/spasi).
        // Agar halaman tidak jadi 404, kita normalisasi dan perluas whitelist status.
        $allowedStatuses = [
            'butuh_konfirmasi_biaya',
            'butuh konfirmasi biaya',
            'menunggu_pembayaran_final',
            'menunggu_pembayaran',
            // normal bentuk umum
            'menunggu_pembayaran_lunas',
            // variasi penulisan (spasi/typo)
            'menunggu pembayaran lunas',
            'menunggu_pembayaran lunas',

            'menunggu pembayaran final',
            'menunggu_konfirmasi_bukti',
            'menunggu_konfirmasi_bukti_final',
            'paid (lunas)',
            'lunas',
            'selesai',
        ];

        $allowedStatusesNormalized = array_map(fn ($s) => strtolower(trim($s)), $allowedStatuses);

        // Normalisasi status: lower + trim.
        $booking = Booking::query()
            ->where('kode_booking', $kodeBookingNormalized)
            ->where('user_id', $customerId)
            ->whereIn(\DB::raw('LOWER(TRIM(status))'), $allowedStatusesNormalized)
            ->first();

        // fallback: jika kodeBooking beda case/whitespace
        if (!$booking) {
            $booking = Booking::query()
                ->where('user_id', $customerId)
                ->whereRaw('LOWER(TRIM(kode_booking)) = ?', [strtolower(trim($kodeBookingNormalized))])
                ->first();

            if ($booking) {
                $statusNormalized = strtolower(trim((string) $booking->status));
                if (!in_array($statusNormalized, array_map(fn ($s) => strtolower(trim($s)), $allowedStatuses), true)) {
                    abort(404, 'Nota tidak ditemukan atau status booking tidak mengizinkan akses invoice.');
                }
            }
        }

        if (!$booking) {
            abort(404, 'Nota tidak ditemukan atau status booking tidak mengizinkan akses invoice.');
        }

        return view('customer.repair-invoice', compact('booking'));
    }

    /**
     * Tambahan Fitur: Menangani aksi tombol submit ketika customer menyetujui biaya perbaikan
     */
    public function approveRepairCost(string $kodeBooking)
    {
        $customerId = session('id_user');
        if (!$customerId) {
            abort(403);
        }

        $booking = Booking::where('kode_booking', $kodeBooking)
            ->where('user_id', $customerId)
            ->whereIn('status', ['butuh_konfirmasi_biaya','menunggu_konfirmasi_biaya','butuh konfirmasi biaya'])
            ->firstOrFail();

        // Mengubah status menjadi tahap pembayaran setelah customer setuju
        $booking->status = 'menunggu_pembayaran';
        $booking->save();

        return redirect()->back()->with('success', 'Estimasi biaya berhasil disetujui! Silakan lakukan pembayaran.');
    }
}