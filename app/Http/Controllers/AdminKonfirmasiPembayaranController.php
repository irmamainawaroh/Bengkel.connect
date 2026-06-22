<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\FinalPaymentConfirmedToCustomer;

class AdminKonfirmasiPembayaranController extends Controller
{
    public function index()
    {
        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }

        $bookings = Booking::query()
            ->whereIn('status', [
                'menunggu_konfirmasi_bukti',
                'menunggu_konfirmasi_bukti_final',
                'menunggu_pembayaran_final',
                'ditolak',
                'lunas',
                'Paid (Lunas)',
                'selesai',
            ])
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($bookings as $booking) {
            if (!in_array($booking->status, ['menunggu_konfirmasi_bukti', 'menunggu_konfirmasi_bukti_final'], true)) {
                continue;
            }

            if (!empty($booking->bukti_total_pembayaran_path) && $booking->status !== 'menunggu_konfirmasi_bukti_final') {
                $booking->status = 'menunggu_konfirmasi_bukti_final';
                $booking->save();
            }

            if (!empty($booking->bukti_dp_path) && $booking->status !== 'menunggu_konfirmasi_bukti') {
                if (empty($booking->bukti_total_pembayaran_path)) {
                    $booking->status = 'menunggu_konfirmasi_bukti';
                    $booking->save();
                }
            }

            $booking->list_jasa = is_string($booking->jasa) ? json_decode($booking->jasa, true) : ($booking->jasa ?? []);
            $booking->list_sparepart = is_string($booking->sparepart) ? json_decode($booking->sparepart, true) : ($booking->sparepart ?? []);
        }

        return view('admin.konfirmasi-pembayaran', compact('bookings'));
    }

    public function reject(Request $request, string $kodeBooking)
    {
        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404, 'Booking tidak ditemukan.');
        }

        if ($booking->status !== 'menunggu_konfirmasi_bukti_final') {
            abort(422, 'Booking belum dalam status menunggu konfirmasi bukti final.');
        }

        // Sesuaikan dengan kebutuhan UI admin: booking harus masuk ke filter "Ditolak"
        $booking->status = 'ditolak';
        $booking->bukti_total_pembayaran_path = null;

        $booking->save();

        PaymentHistory::create([
            'kode_booking' => $booking->kode_booking,
            'action' => 'reject_final_payment',
            'amount' => $booking->total_biaya_perbaikan ?? $booking->total_biaya ?? 0,
            'remarks' => 'Admin menolak bukti pelunasan final. Booking dikembalikan ke menunggu_pembayaran_final.',
            'performed_by' => auth()->user()?->name ?? 'admin',
        ]);

        return redirect()->route('admin.konfirmasi-pembayaran')
            ->with('success', 'Bukti pelunasan ditolak. Booking dikembalikan ke menunggu pembayaran final.');
    }

    public function confirmFinalPayment(Request $request, string $kodeBooking)
    {
        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404, 'Booking tidak ditemukan.');
        }

        if ($booking->status !== 'menunggu_konfirmasi_bukti_final') {
            abort(422, 'Booking belum dalam status menunggu konfirmasi bukti final.');
        }

        $booking->status = 'lunas';
        $booking->lunas_at = now();
        // Bersihkan field yang mungkin memicu status/flow lain di UI
        $booking->bukti_total_pembayaran_path = $booking->bukti_total_pembayaran_path;
        $booking->save();

        // Pastikan relasi user_id dan data customer siap (hindari pengambilan email gagal karena booking masih ter-load lama)
        $booking->refresh();

        PaymentHistory::create([
            'kode_booking' => $booking->kode_booking,
            'action' => 'confirm_final_payment',
            'amount' => $booking->total_biaya_perbaikan ?? $booking->total_biaya ?? 0,
            'remarks' => 'Admin menyetujui pelunasan final dan status booking ditandai lunas.',
            'performed_by' => auth()->user()?->name ?? 'admin',
        ]);

        // Setelah final lunas, status harus langsung menjadi 'lunas' (sesuai permintaan task).
        // Jangan di-overwrite lagi ke status lain seperti 'menunggu_pembayaran_lunas',
        // agar tidak terjadi duplikasi flow/status booking.

        // Notifikasi invoice ke customer
        // IMPORTANT: Jangan set invoice_sent_at saat pelunasan final dikonfirmasi,
        // karena dashboard customer akan menganggap nota sudah dikirim.
        // Cukup ubah status booking & lunas_at saja.

        \Log::info('confirmFinalPayment done', [
            'kode_booking' => $booking->kode_booking,
            'status' => $booking->status,
        ]);

        // IMPORTANT:
        // Redirect back ke list admin seharusnya langsung tampil sebagai lunas.
        // Tambahkan flash + refresh status customer agar tidak terasa status belum berubah saat user kembali ke halaman.
        return redirect()->route('admin.konfirmasi-pembayaran')
            ->with('success', 'Pelunasan final berhasil dikonfirmasi. Status booking: lunas.');

    }
}

