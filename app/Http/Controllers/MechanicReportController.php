<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceBiayaPerbaikanSentToCustomer;
use App\Mail\ProgressSelesaiNotifiedAdmin;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MechanicReportController extends Controller
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


        $filter = $request->query('filter');

        $query = Booking::query()
            ->whereNotNull('alamat') // ini halaman admin home service mekanik
            ->with(['mechanic', 'progressUpdates']);

        if ($filter === 'proses') {
            // Tahap pengerjaan / proses (mekanik sedang kerja)
            $query->whereIn('status', ['sedang_dikerjakan']);
        } elseif ($filter === 'approval') {
            // Tahap menunggu approval biaya oleh admin
            $query->whereIn('status', ['menunggu_konfirmasi_biaya']);
        } else {
            $query->whereIn('status', ['sedang_dikerjakan', 'menunggu_konfirmasi_biaya', 'menunggu_pembayaran_lunas']);
        }


        $bookings = $query
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();

        return view('admin.laporan-mekanik', compact('bookings'));
    }

    public function showDetail(string $kodeBooking)
    {
        $this->ensureAdmin();

        $booking = Booking::with(['mechanic', 'progressUpdates'])
            ->where('kode_booking', $kodeBooking)
            ->first();

        if (!$booking) {
            abort(404);
        }

        return view('admin.laporan-mekanik-detail', compact('booking'));
    }

    public function confirmCost(Request $request, string $kodeBooking)
    {
        $this->ensureAdmin();

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        // Admin workflow baru:
        // - Tidak perlu lagi konfirmasi biaya/tolak-setujui item.
        // - Begitu admin klik aksi, status langsung dipindahkan ke menunggu_pembayaran_lunas
        //   dan nota dikirim ke customer.
        // Admin hanya boleh mengirim nota bila booking memang sudah selesai mekanik.
        // Karena status sering diubah otomatis/bertahap,
        // kita terima transisi dari `butuh_konfirmasi_biaya` maupun `sedang_dikerjakan`.
        // (Jika sudah `menunggu_pembayaran_lunas`, berarti nota sudah pernah dikirim.)
        if ($booking->status === 'menunggu_pembayaran_lunas') {
            return redirect('/admin/laporan-mekanik')
                ->with('success', 'Nota sudah dikirim. Booking saat ini menunggu_pembayaran_lunas.');
        }

        // Requirement: admin hanya boleh mengirim invoice ketika booking berada di `menunggu_konfirmasi_biaya`.
        // Kalau status masih `sedang_dikerjakan`, tolong lanjutkan proses mekanik dulu sampai bukti kerja terupload.
        if ($booking->status !== 'menunggu_konfirmasi_biaya') {
            return redirect('/admin/laporan-mekanik')
                ->with('success', 'Booking tidak berada dalam status menunggu_konfirmasi_biaya (status saat ini: '.$booking->status.').');
        }



        // Validasi ringan (supaya route bisa tetap menerima payload dari form lama)
        $data = $request->validate([
            'total_biaya_perbaikan' => 'nullable|numeric|min:0',
            'laporan_perbaikan' => 'nullable|string|max:5000',
        ]);

        // Karena user bilang nilai sudah tersimpan oleh mekanik, kita hanya override jika request mengirimnya.
        if (array_key_exists('total_biaya_perbaikan', $data) && $data['total_biaya_perbaikan'] !== null) {
            $booking->total_biaya_perbaikan = $data['total_biaya_perbaikan'];
        }
        if (!empty($data['laporan_perbaikan'])) {
            $booking->laporan_perbaikan = $data['laporan_perbaikan'];
        }

        // Kirim nota tagihan biaya perbaikan ke customer.
        // Sesuai requirement: status booking berubah ke `menunggu_pembayaran_lunas` hanya setelah nota (invoice) benar-benar terkirim.
        $customerEmail = null;
        // Pastikan email customer tersedia (jangan hanya rely di user_id field)
        if (!empty($booking->user_id)) {
            $customer = User::find($booking->user_id);
            $customerEmail = $customer?->email;
        }
        if (empty($customerEmail) && !empty($booking->email)) {
            $customerEmail = $booking->email;
        }


        $invoiceSent = false;

        if (!empty($customerEmail)) {
            try {
                \Log::info('Kirim invoice biaya perbaikan ke customer', [
                    'kode_booking' => $booking->kode_booking,
                    'customer_email' => $customerEmail,
                ]);

                // Kirim email/notification invoice tagihan ke customer
                Mail::to($customerEmail)->send(new InvoiceBiayaPerbaikanSentToCustomer($booking));

                // Simpan flag agar bisa dilacak (jika tampilan customer menggunakan reload/ polling)
                $booking->invoice_sent_at = now();
                $booking->save();


                \Log::info('Berhasil kirim invoice biaya perbaikan ke customer', [
                    'kode_booking' => $booking->kode_booking,
                    'customer_email' => $customerEmail,
                ]);

                $invoiceSent = true;
            } catch (\Throwable $e) {
                \Log::error('Gagal kirim invoice biaya perbaikan ke customer', [
                    'kode_booking' => $booking->kode_booking,
                    'customer_email' => $customerEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            \Log::warning('Customer email kosong, tidak kirim invoice biaya perbaikan', [
                'kode_booking' => $booking->kode_booking,
            ]);
        }

        // Only move status AFTER invoice sent successfully.
        if (!$invoiceSent) {
            // agar customer tidak bisa "kirim ulang nota" jika email gagal, kita tetap kunci status,
            // sehingga alur tidak kembali ke proses invoice yang sama.
            // (tetap bisa audit via laravel.log/ exception)
            $booking->status = 'menunggu_pembayaran_lunas';
            $booking->save();

            return redirect('/admin/laporan-mekanik')
                ->with('error', 'Gagal mengirim nota invoice ke customer (cek laravel.log). Status sudah diubah agar tidak bisa dikirim ulang.');
        }

        // Setelah invoice berhasil terkirim, status customer menerima nota & bisa lanjut ke pembayaran.
        $booking->status = 'menunggu_pembayaran_lunas';
        $booking->save();

        // Pesan ke admin (optional - tetap kirim seperti sebelumnya)

        $adminEmail = auth()->user()?->email;
        if (!empty($adminEmail)) {
            Mail::to($adminEmail)->send(new ProgressSelesaiNotifiedAdmin($booking));
        }

        return redirect('/admin/laporan-mekanik')
            ->with('success', 'Nota tagihan terkirim ke customer. Booking dipindahkan ke menunggu_konfirmasi_biaya.');
    }
}