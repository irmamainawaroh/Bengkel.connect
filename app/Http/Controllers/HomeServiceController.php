<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\HomeServiceBookingCreated;
use App\Mail\BookingAssignedToMechanic;
use App\Mail\FinalPaymentConfirmedToCustomer;


class HomeServiceController extends Controller
{
    public function showForm()
    {
        return view('services.home-service');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'layanan' => 'required|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|string',
            'nama' => 'required|string|max:255',
            'telepon' => 'required|string|max:50',
            'kendaraan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nopol' => 'required|string|max:50',
            'catatan' => 'nullable|string',
        ]);

        $layanan = $data['layanan'];

        $totalPembayaran = 'Rp 100.000';
        if (str_contains($layanan, 'AC')) {
            $totalPembayaran = 'Rp 100.000';
        } elseif (str_contains($layanan, 'Mesin')) {
            $totalPembayaran = 'Rp 100.000';
        }

        $userId = session('id_user');

        $kodeBooking = $this->generateKodeBooking();

        // simpan ke session agar halaman payment/qris bisa menampilkan data booking terakhir
        session()->put('totalPembayaran', $totalPembayaran);
        session()->put('jenisLayanan', $layanan);
        session()->put('kode_booking', $kodeBooking);
        session()->put('nama_booking', $data['nama']);
        session()->put('telepon_booking', $data['telepon']);
        session()->put('kendaraan_booking', $data['kendaraan']);
        session()->put('nopol_booking', $data['nopol']);
        session()->put('alamat_booking', $data['alamat']);
        session()->put('tanggal_booking', $data['tanggal']);
        session()->put('waktu_booking', $data['waktu']);
        session()->put('catatan_booking', $data['catatan'] ?? '-');


        try {
            $booking = \Illuminate\Support\Facades\DB::transaction(function () use ($kodeBooking, $userId, $layanan, $data) {
                return Booking::create([
                    'kode_booking' => $kodeBooking,
                    'user_id' => $userId,
                    'nama' => $data['nama'],
                    'telepon' => $data['telepon'],
                    'kendaraan' => $data['kendaraan'],
                    'nopol' => $data['nopol'],
                    'alamat' => $data['alamat'],
                    'layanan' => $layanan,
                    'tanggal' => $data['tanggal'],
                    'waktu' => $data['waktu'],
                    'catatan' => $data['catatan'] ?? null,
                    'status' => 'menunggu_pembayaran',
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika slot sudah penuh, unique index (tanggal, waktu) akan memblokir insert
            $message = 'Slot untuk tanggal & waktu tersebut sudah penuh. Silakan pilih waktu lain.';

            return redirect()->back()
                ->withInput()
                ->withErrors(['waktu' => $message]);
        }


        // Kirim notifikasi ke admin
        $adminEmails = User::where('role', 'admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->toArray();

        if (!empty($adminEmails)) {
            Mail::to($adminEmails)
                ->send(new HomeServiceBookingCreated($booking, $totalPembayaran));
        }

        return view('services.home-service-success', [
            'kodeBooking' => $kodeBooking,
            'jenisLayanan' => $layanan,
            'totalPembayaran' => $totalPembayaran,
            'nama' => $data['nama'],
            'telepon' => $data['telepon'],
            'kendaraan' => $data['kendaraan'],
            'nopol' => $data['nopol'],
            'alamat' => $data['alamat'],
            'tanggal' => $data['tanggal'],
            'waktu' => $data['waktu'],
            'catatan' => $data['catatan'] ?? '-',
        ]);
    }

    private function generateKodeBooking(): string
    {
        $date = now()->format('Ymd');
        return 'BC-' . $date . '-' . strtoupper(Str::random(6));
    }

    public function showDetail($kodeBooking)
    {
        // Cari booking by kode_booking. Jika tidak ada, tampilkan informasi agar tidak “misterius 404”.
        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            // Jika admin masih dapat melihat kodeBooking dari list, tetapi 404 terjadi,
            // kemungkinan url yang dikirim tidak sama persis atau ada mismatch data.
            // Untuk mencegah "not found" yang membingungkan, coba fallback: cari booking
            // berdasarkan user (admin) tidak relevan, jadi fallback hanya berdasarkan kodeBooking (case-insensitive).
            $booking = Booking::whereRaw('LOWER(kode_booking) = ?', [strtolower($kodeBooking)])->first();
        }

        if (!$booking) {
            abort(404, 'Booking tidak ditemukan. kodeBooking=' . $kodeBooking);
        }


        $mechanics = User::where('role', 'mekanik')->get();

        return view('admin.booking-detail', [
            'booking' => $booking,
            'mechanics' => $mechanics,
        ]);
    }

    public function showDpProof($kodeBooking)
    {
        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404, 'Booking tidak ditemukan.');
        }

        // Jika bukti DP belum diupload (bukti_dp_path kosong / null), jangan 404.
        // Tampilkan pesan di halaman detail booking supaya admin tetap bisa lanjut.
        if (empty($booking->bukti_dp_path)) {
            return redirect('/admin/home-service/detail/' . $booking->kode_booking)
                ->withErrors('Bukti DP belum diupload oleh customer.');
        }

        $dpUrl = Storage::disk('public')->url($booking->bukti_dp_path);
        $dpExtension = strtolower(pathinfo($booking->bukti_dp_path, PATHINFO_EXTENSION));

        return view('admin.bukti-dp-view', [
            'booking' => $booking,
            'dpUrl' => $dpUrl,
            'dpExtension' => $dpExtension,
        ]);
    }


    public function confirmPayment($kodeBooking)
    {
        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        // Guard: admin hanya boleh mengonfirmasi setelah bukti tersimpan oleh customer
        if ($booking->status === 'menunggu_konfirmasi_bukti_final') {
            if (empty($booking->bukti_total_pembayaran_path)) {
                return redirect('/admin/home-service/detail/' . $booking->kode_booking)
                    ->withErrors('Bukti pembayaran final belum tersedia. Customer belum upload bukti.');
            }

            // Sesuai permintaan: setelah biaya dikonfirmasi, booking masuk ke tahap pembayaran final
            $booking->status = 'menunggu_pembayaran_final';
            $booking->save();

            PaymentHistory::create([
                'kode_booking' => $booking->kode_booking,
                'action' => 'confirm_full_payment_move_to_wait_final',
                'amount' => $booking->total_biaya_perbaikan,
                'remarks' => 'Biaya dikonfirmasi. Booking dipindahkan ke menunggu_pembayaran_final.',
                'performed_by' => auth()->user()?->name ?? 'admin',
            ]);

            return redirect('/admin/home-service')->with('success', 'Biaya berhasil dikonfirmasi. Booking dipindahkan ke menunggu_pembayaran_final.');
        }


        if ($booking->status === 'menunggu_konfirmasi_bukti') {
            if (empty($booking->bukti_dp_path)) {
                return redirect('/admin/home-service/detail/' . $booking->kode_booking)
                    ->withErrors('Bukti DP belum tersedia. Customer belum upload bukti.');
            }

            $booking->status = 'pembayaran_dikonfirmasi';
            $booking->save();

            PaymentHistory::create([
                'kode_booking' => $booking->kode_booking,
                'action' => 'confirm_dp',
                'amount' => null,
                'remarks' => 'Pembayaran DP berhasil dikonfirmasi oleh admin.',
                'performed_by' => auth()->user()?->name ?? 'admin',
            ]);

            return redirect('/admin/home-service')->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        // Untuk status lain, batalkan agar tidak terjadi konfirmasi yang tidak sesuai state
        abort(422, 'Booking belum dalam status yang dapat dikonfirmasi.');
    }

    public function sendFinalInvoice(Request $request, $kodeBooking)
    {
        $data = $request->validate([
            'total_biaya_perbaikan' => 'required|numeric|min:0',
            'laporan_perbaikan' => 'required|string|max:5000',
        ]);

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        if (!in_array($booking->status, ['dikirim_ke_mekanik', 'sedang_dikerjakan', 'pembayaran_dikonfirmasi'])) {
            abort(422, 'Booking tidak dapat dikirim invoice total pembayaran pada status saat ini.');
        }

        $booking->total_biaya_perbaikan = $data['total_biaya_perbaikan'];
        $booking->laporan_perbaikan = $data['laporan_perbaikan'];
        // Setelah admin mengirim nota tagihan/invoice, customer masuk tahap menunggu pembayaran lunas.
        $booking->status = 'menunggu_pembayaran_lunas';
        $booking->save();


        PaymentHistory::create([
            'kode_booking' => $booking->kode_booking,
            'action' => 'invoice_sent',
            'amount' => $booking->total_biaya_perbaikan,
            'remarks' => 'Struk total pembayaran dikirim ke customer.',
            'performed_by' => auth()->user()?->name ?? 'admin',
        ]);

        return redirect('/admin/home-service/detail/' . $kodeBooking)->with('success', 'Struk total pembayaran berhasil disiapkan dan ditandai menunggu pembayaran final.');
    }

    public function confirmFullPayment($kodeBooking)
    {
        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        if ($booking->status !== 'menunggu_konfirmasi_bukti_final') {
            abort(422, 'Booking belum dalam status konfirmasi pembayaran final.');
        }

        $booking->status = 'lunas';
        $booking->lunas_at = now();
        $booking->save();

        PaymentHistory::create([
            'kode_booking' => $booking->kode_booking,
            'action' => 'confirm_final_payment',
            'amount' => $booking->total_biaya_perbaikan,
            'remarks' => 'Pembayaran final dikonfirmasi dan status booking ditandai lunas.',
            'performed_by' => auth()->user()?->name ?? 'admin',
        ]);

        // Notifikasi email ke customer
        try {
            if (!empty($booking->user_id)) {
                $customerEmail = User::where('id', $booking->user_id)->value('email');
                if (!empty($customerEmail)) {
                    Mail::to($customerEmail)->send(new FinalPaymentConfirmedToCustomer($booking));
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim email notifikasi pelunasan lunas untuk booking ' . $booking->kode_booking . ': ' . $e->getMessage());
        }

        return redirect('/admin/home-service')->with('success', 'Pembayaran final berhasil dikonfirmasi dan status disimpan lunas.');
    }


    public function sendToMechanic(Request $request, $kodeBooking)
    {
        $data = $request->validate([
            'mekanik_id' => 'required|exists:users,id',
        ]);

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            abort(404);
        }

        $mechanic = User::find($data['mekanik_id']);
        
        // Assign ke mekanik
        $booking->mekanik_id = $data['mekanik_id'];
        $booking->assigned_at = now();
        $booking->status = 'dikirim_ke_mekanik';
        $booking->save();

        // Kirim email ke mekanik
        if ($mechanic->email) {
            Mail::to($mechanic->email)
                ->send(new BookingAssignedToMechanic($booking));
        }

        return redirect('/admin/home-service')->with('success', 'Booking berhasil dikirim ke mekanik!');
    }
}

