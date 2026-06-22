<?php

namespace App\Http\Controllers;

use App\Mail\PaymentProofSubmitted;
use App\Models\Booking;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class BookingUploadController extends Controller
{
    public function showUpload(Request $request)
    {
        $kodeBooking = $request->query('kode_booking');
        $booking = $kodeBooking ? Booking::where('kode_booking', $kodeBooking)->first() : null;

        if (!$booking) {
            abort(404);
        }

        return view('customer.upload-bukti', [
            'booking' => $booking,
        ]);
    }

    public function showQris(Request $request)
    {
        $kodeBooking = $request->query('kode_booking');
        $booking = $kodeBooking ? Booking::where('kode_booking', $kodeBooking)->first() : null;

        if (!$booking) {
            return view('services.home-service-qris');
        }

        return view('services.home-service-qris', [
            'booking' => $booking,
        ]);
    }

    // Admin uploads QRIS image - saved as static qris.png
    // Note: Disabled. QRIS image is now managed directly.
    // Kept for reference in case needed in future.
    /*
    public function uploadQrisAdmin(Request $request, $kodeBooking)
    {
        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }

        $request->validate([
            'qris_file' => 'required|image|max:5120',
        ]);

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) abort(404);

        $file = $request->file('qris_file');
        // Save directly to public/images/qris.png (overwrite existing)
        $destinationPath = public_path('images');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        
        $file->move($destinationPath, 'qris.png');
        
        return back()->with('success', 'QRIS berhasil diunggah dan akan ditampilkan untuk semua customer.');
    }
    */

    public function uploadBukti(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'kode_booking' => 'required|string',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
            'qris_data' => 'nullable|string', // QRIS image as data URL
        ]);

        $booking = Booking::where('kode_booking', $data['kode_booking'])->first();
        if (!$booking) {
            abort(404);
        }

        if (!in_array($booking->status, ['menunggu_pembayaran'])) {
            abort(422, 'Booking tidak dapat mengupload bukti pembayaran DP pada status ini.');
        }


        // Save payment proof file
        $file = $request->file('bukti_pembayaran');
        $ext = $file->getClientOriginalExtension();
        $filename = 'bukti-dp-' . $booking->kode_booking . '-' . Str::random(8) . '.' . $ext;
        $path = $file->storeAs('bukti-dp', $filename, 'public');


        // Save QRIS image if provided
        $qrisPath = null;
        $qrisData = $data['qris_data'] ?? null;
        if ($qrisData) {
            try {
                $image_data = $qrisData;
                if (strpos($image_data, 'data:image') === 0) {
                    list($type, $image_data) = explode(';', $image_data);
                    list(, $image_data) = explode(',', $image_data);
                    $image_data = base64_decode($image_data);

                    $qris_filename = 'qris-' . $booking->kode_booking . '-' . Str::random(8) . '.png';
                    Storage::disk('public')->put('qris/' . $qris_filename, $image_data);
                    $qrisPath = 'qris/' . $qris_filename;
                }
            } catch (\Exception $e) {
                \Log::error('Error saving QRIS: ' . $e->getMessage());
            }
        }

        $booking->status = 'menunggu_konfirmasi_bukti';
        $booking->bukti_dp_path = $path;


        if ($qrisPath) {
            $booking->qris_path = $qrisPath;
        }

        $booking->save();

        PaymentHistory::create([
            'kode_booking' => $booking->kode_booking,
            'action' => $booking->status === 'menunggu_konfirmasi_bukti_final' ? 'upload_bukti_final' : 'upload_bukti_dp',
            'amount' => null,
            'remarks' => $booking->status === 'menunggu_konfirmasi_bukti_final' ? 'Bukti pembayaran final diunggah oleh customer.' : 'Bukti DP diunggah oleh customer.',
            'performed_by' => $booking->nama,
        ]);

        // Send email to admin
        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->toArray();

        if (!empty($adminEmails)) {
            Mail::to($adminEmails)
                ->send(new PaymentProofSubmitted($booking, $path, $qrisPath));
        }

        return redirect(route('payment.qris', ['kode_booking' => $booking->kode_booking]))->with('bukti_uploaded', true);
    }
}



