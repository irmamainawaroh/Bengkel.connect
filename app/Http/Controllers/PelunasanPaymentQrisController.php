<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class PelunasanPaymentQrisController extends Controller
{
    public function show(Request $request, string $kode_booking)
    {
        // 1. Cari data booking berdasarkan kode
        $booking = Booking::where('kode_booking', $kode_booking)->first();
        
        if (!$booking) {
            abort(404, 'Data booking tidak ditemukan.');
        }

        // 2. Proteksi Status: Jika sudah lunas, arahkan langsung ke halaman sukses/riwayat
        if (in_array($booking->status, ['lunas', 'selesai'])) {
            return redirect()->route('customer.dashboard') // Sesuaikan dengan nama route Anda
                ->with('info', 'Transaksi ini sudah dinyatakan lunas.');
        }

        // 3. Proteksi Data: Pastikan total biaya perbaikan sudah dihitung oleh mekanik/admin
        $totalTagihan = $booking->total_biaya_perbaikan ?? $booking->total_biaya ?? 0;
        if ($totalTagihan <= 0) {
            return redirect()->back()
                ->with('error', 'Nota tagihan perbaikan Anda sedang diproses oleh mekanik.');
        }

        // 4. PERBARUI: Langsung arahkan dan tampilkan file view upload-pelunasan-bukti.blade.php
        return view('customer.upload-pelunasan-bukti', compact('booking'));
    }
}