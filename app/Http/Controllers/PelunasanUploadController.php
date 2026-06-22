<?php

namespace App\Http\Controllers;

use App\Mail\PaymentProofSubmitted;
use App\Models\Booking;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PelunasanUploadController extends Controller
{
    /**
     * Menampilkan Form Upload Pelunasan Home Service (route parameter /pelunasan/upload/{kode_booking})
     */
    public function showUploadPelunasan($kode_booking = null): View|RedirectResponse
    {
        $booking = Booking::where('kode_booking', $kode_booking)->first();

        if (!$booking) {
            return view('customer.upload-pelunasan-notfound', ['kode_booking' => $kode_booking]);
        }

        // Validasi alur home service
        // Nota invoice dikirim saat status berubah menjadi `menunggu_pembayaran_lunas`.
        // Customer butuh bisa lanjut ke halaman upload bukti pelunasan setelah itu.
        // Jadi, izinkan status: menunggu_pembayaran_lunas, menunggu_pembayaran_final, menunggu_konfirmasi_bukti_final.
        if (!in_array($booking->status, ['menunggu_pembayaran_lunas', 'menunggu_pembayaran_final', 'menunggu_konfirmasi_bukti_final'])) {
            return redirect()->route('customer.repair-invoice', ['kodeBooking' => $booking->kode_booking])
                ->with('error', 'Status booking Anda belum masuk ke tahap pelunasan akhir.');
        }


        return view('customer.upload-bukti', [
            'booking' => $booking,
        ]);
    }

    /**
     * Legacy querystring: /upload-pelunasan?kode_booking=...
     */
    public function showUploadPelunasanQuery(Request $request): View|RedirectResponse
    {
        $kode_booking = $request->query('kode_booking') ?? $request->query('kodeBooking') ?? null;
        return $this->showUploadPelunasan($kode_booking);
    }

    /**
     * Memproses File Bukti Pelunasan Home Service
     */
    public function uploadPelunasanBukti(Request $request): RedirectResponse
    {
        if (!$request->filled('kodeBooking') && $request->filled('kode_booking')) {
            $request->merge(['kodeBooking' => $request->input('kode_booking')]);
        }

        $data = $request->validate([
            'kodeBooking' => 'required|string',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'qris_data' => 'nullable|string',
        ]);

        if (!isset($data['kodeBooking']) && $request->filled('kode_booking')) {
            $data['kodeBooking'] = $request->input('kode_booking');
        }

        $booking = Booking::where('kode_booking', $data['kodeBooking'])->first();
        if (!$booking) {
            abort(404);
        }

        // Status yang diizinkan untuk upload bukti pelunasan final
        // (berdasarkan flow di customer: menunggu_pembayaran_lunas -> upload -> menunggu_konfirmasi_bukti_final)
        $allowedUploadStatuses = ['menunggu_pembayaran_final', 'menunggu_pembayaran_lunas'];
        if (!in_array($booking->status, $allowedUploadStatuses, true)) {
            return redirect()->route('customer.repair-invoice', ['kodeBooking' => $booking->kode_booking])
                ->with('error', 'Bukti pelunasan untuk layanan ini belum dibuka atau sedang diproses.');
        }


        $file = $request->file('bukti_pembayaran');
        $ext = $file->getClientOriginalExtension();
        $filename = 'bukti-final-' . $booking->kode_booking . '-' . Str::random(8) . '.' . $ext;
        $path = $file->storeAs('bukti-pembayaran-final', $filename, 'public');

        $qrisPath = null;
        $qrisData = $data['qris_data'] ?? null;
        if ($qrisData) {
            try {
                $image_data = $qrisData;
                if (strpos($image_data, 'data:image') === 0) {
                    list($type, $image_data) = explode(';', $image_data);
                    list(, $image_data) = explode(',', $image_data);
                    $image_data = base64_decode($image_data);

                    $qris_filename = 'qris-final-' . $booking->kode_booking . '-' . Str::random(8) . '.png';
                    Storage::disk('public')->put('qris/' . $qris_filename, $image_data);
                    $qrisPath = 'qris/' . $qris_filename;
                }
            } catch (\Exception $e) {
                \Log::error('Gagal menyimpan tangkapan QRIS Home Service: ' . $e->getMessage());
            }
        }

        // Pastikan booking masuk antrian admin verifikasi bukti final dan bukti tersimpan ke field yang dipakai admin.
        $booking->status = 'menunggu_konfirmasi_bukti_final';
        $booking->bukti_total_pembayaran_path = $path;
        if ($qrisPath) {
            $booking->qris_path = $qrisPath;
        }
        $booking->save();

        PaymentHistory::create([
            'kode_booking' => $booking->kode_booking,
            'action' => 'upload_bukti_final',
            'amount' => $booking->total_biaya_perbaikan,
            'remarks' => 'Customer mengunggah bukti pelunasan akhir untuk pengerjaan Home Service.',
            'performed_by' => $booking->nama,
        ]);

        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->toArray();

        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new PaymentProofSubmitted($booking, $path, $qrisPath));
        }

        return redirect()->route('customer.repair-invoice', ['kodeBooking' => $booking->kode_booking])
            ->with('success', 'Bukti pelunasan Home Service berhasil dikirim! Admin akan segera melakukan verifikasi.');

    }
}




