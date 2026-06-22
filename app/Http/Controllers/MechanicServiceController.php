<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ProgressUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MechanicServiceController extends Controller
{
    private function getMechanicIdFromSession(): ?int
    {
        return session('id_user');
    }

    public function showDashboard()
    {
        $mechanicId = $this->getMechanicIdFromSession();
        if (!$mechanicId) {
            abort(403);
        }

        $bookings = Booking::where('mekanik_id', $mechanicId)
            ->whereIn('status', ['dikirim_ke_mekanik', 'sedang_dikerjakan', 'selesai'])
            ->with('progressUpdates')
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('admin.mekanik.dashboard', compact('bookings'));
    }

    public function submitTotalRepair(Request $request, string $kodeBooking)
    {
        $mechanicId = $this->getMechanicIdFromSession();
        if (!$mechanicId) {
            abort(403);
        }

        $data = $request->validate([
            'total_biaya_perbaikan' => 'required',
            'laporan_perbaikan' => 'required|string|max:5000',
        ]);

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        if ((int) $booking->mekanik_id !== (int) $mechanicId) {
            abort(403);
        }

        // Tahap 1: mekanik mengisi laporan total perbaikan
        if (!in_array($booking->status, ['dikirim_ke_mekanik'])) {
            abort(422, 'Booking tidak berada pada tahap yang benar untuk mengirim laporan total perbaikan.');
        }

        $booking->total_biaya_perbaikan = $data['total_biaya_perbaikan'];
        $booking->laporan_perbaikan = $data['laporan_perbaikan'];

        // Workflow mekanik:
        // - mekanik mengisi laporan total -> tetap di fase pengerjaan (upload bukti kerja dibutuhkan untuk masuk admin laporan-mekanik)
        $booking->status = 'sedang_dikerjakan';
        $booking->save();

        return redirect('/mekanik/dashboard')
            ->with('success', 'Laporan total perbaikan berhasil dikirim. Lanjutkan dengan upload bukti pengerjaan agar masuk ke antrian laporan mekanik.');
    }

    public function uploadWorkProof(Request $request, string $kodeBooking)
    {
        $mechanicId = $this->getMechanicIdFromSession();
        if (!$mechanicId) {
            abort(403);
        }

        $data = $request->validate([
            'bukti_pengerjaan' => 'required|file|mimes:jpg,jpeg,png|max:5120', // 5MB
            'customer_recommendation' => 'nullable|string|max:5000',
        ]);


        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        if ((int) $booking->mekanik_id !== (int) $mechanicId) {
            abort(403);
        }

        // Tahap 2: upload bukti kerja
        if (!in_array($booking->status, ['sedang_dikerjakan'])) {
            abort(422, 'Booking tidak berada pada tahap yang benar untuk upload bukti pengerjaan.');
        }

        $file = $request->file('bukti_pengerjaan');
        $ext = $file->getClientOriginalExtension();
        $filename = 'bukti-pengerjaan-' . $booking->kode_booking . '-' . Str::random(8) . '.' . $ext;

        $path = $file->storeAs('bukti-pengerjaan', $filename, 'public');

        $booking->bukti_pengerjaan_path = $path;
        $booking->selesai_at = now();

        // Perbarui ringkasan pengerjaan agar sesuai dengan progress/catatan terakhir sebelum masuk ke admin.
        // Ini digunakan oleh tampilan ringkasan di halaman admin-laporan-mekanik-detail.
        $mechanicNote = $request->input('customer_recommendation') ?? $request->input('mechanic_note') ?? $booking->mechanic_note;
        if (!is_string($mechanicNote)) {
            $mechanicNote = (string) $booking->mechanic_note;
        }

        $booking->mechanic_note = $mechanicNote;
        if (empty($booking->laporan_perbaikan)) {
            $booking->laporan_perbaikan = 'Hasil perbaikan: ' . ($booking->layanan ?? '-') . '. Catatan mekanik: ' . $mechanicNote;
        }

        // Setelah mekanik selesai upload bukti kerja, admin perlu konfirmasi biaya.
        // Sesuai requirement: status harus menjadi `menunggu_konfirmasi_biaya`.
        $booking->status = 'menunggu_konfirmasi_biaya';
        $booking->save();





        return redirect('/mekanik/dashboard')->with('success', 'Bukti pengerjaan berhasil diupload. Status booking: masuk antrian laporan mekanik (admin).');
    }



    public function submitProgressUpdate(Request $request, string $kodeBooking)

    {
        $mechanicId = $this->getMechanicIdFromSession();
        if (!$mechanicId) {
            abort(403);
        }

        $data = $request->validate([
            'update_text' => 'required|string|max:500',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'mechanic_note' => 'nullable|string|max:5000',
            'recommended_parts' => 'nullable|array',
            'recommended_parts.*' => 'nullable|string|max:200',
        ]);


        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        if ((int) $booking->mekanik_id !== (int) $mechanicId) {
            abort(403);
        }

        // Hanya bisa submit progress update saat sedang dikerjakan
        if ($booking->status !== 'sedang_dikerjakan') {
            abort(422, 'Booking harus berada dalam status sedang_dikerjakan untuk mengirim progress update.');
        }

        ProgressUpdate::create([
            'kode_booking' => $kodeBooking,
            'mekanik_id' => $mechanicId,
            'update_text' => $data['update_text'],
            'progress_percentage' => $data['progress_percentage'] ?? 0,
        ]);

        // Simpan mechanic_note + recommended_parts ke bookings (konteks laporan mekanik)
        if (array_key_exists('mechanic_note', $data)) {
            $booking->mechanic_note = $data['mechanic_note'];
        }

        if (isset($data['recommended_parts'])) {
            $booking->recommended_parts = json_encode(array_values(array_filter($data['recommended_parts'])));
        }

        $booking->save();




        return redirect('/mekanik/dashboard')
            ->with('success', 'Progress update berhasil dikirim. Booking langsung masuk antrean laporan mekanik (admin).');


    }


    public function updateWorkStatus(Request $request, string $kodeBooking)
    {
        $mechanicId = $this->getMechanicIdFromSession();
        if (!$mechanicId) {
            abort(403);
        }

        $data = $request->validate([
            'new_status' => 'required|in:sedang_dikerjakan,selesai',
            'reason' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        if ((int) $booking->mekanik_id !== (int) $mechanicId) {
            abort(403);
        }

        // Validasi transisi status paling aman
        if ($data['new_status'] === 'selesai') {
            // Pastikan tombol "Selesai Pengerjaan" benar-benar memindahkan booking ke tahap pembayaran final.
            // Kebutuhan: status wajib menjadi 'menunggu_pembayaran_final' (underscore).

            if ($booking->status !== 'sedang_dikerjakan') {
                abort(422, 'Booking harus berstatus sedang_dikerjakan untuk ditandai selesai.');
            }

            // Bila user menandai selesai, wajib ada bukti pengerjaan (mengikuti workflow uploadWorkProof)
            if (empty($booking->bukti_pengerjaan_path)) {
                abort(422, 'Untuk menandai selesai, bukti pengerjaan harus sudah diupload terlebih dahulu.');
            }

            // Saat mekanik menekan "Selesai Pengerjaan", booking harus masuk ke tahap pembayaran final.
            $booking->status = 'menunggu_pembayaran_final';
            $booking->selesai_at = $booking->selesai_at ?? now();
            $booking->save();

            return redirect('/mekanik/dashboard')->with('success', 'Status pengerjaan berhasil diperbarui: selesai.');
        }

        // new_status = sedang_dikerjakan
        if ($booking->status !== 'dikirim_ke_mekanik' && $booking->status !== 'sedang_dikerjakan') {
            abort(422, 'Booking tidak bisa diubah menjadi sedang_dikerjakan dari status saat ini.');
        }

        $booking->status = 'sedang_dikerjakan';
        $booking->save();

        return redirect('/mekanik/dashboard')->with('success', 'Status pengerjaan berhasil diperbarui: sedang_dikerjakan.');
    }

    /**
     * Riwayat pengerjaan mekanik (hubungkan ke halaman riwayat).
     * Route: /mekanik/riwayat-pengerjaan
     */
    public function showHistory()
    {
        $mechanicId = $this->getMechanicIdFromSession();
        if (!$mechanicId) {
            abort(403);
        }

        $bookings = Booking::where('mekanik_id', $mechanicId)
            ->whereIn('status', [
                'dikirim_ke_mekanik',
                'sedang_dikerjakan',
                'menunggu_konfirmasi_biaya',
                'menunggu_pembayaran',
                'menunggu_pembayaran_final',
            ])
            ->with('progressUpdates')
            ->orderBy('assigned_at', 'desc')
            ->get();

        return view('mekanik.riwayat-pengerjaan', compact('bookings'));
    }
}

