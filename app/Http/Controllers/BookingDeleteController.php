<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingDeleteController extends Controller
{
    public function delete(Request $request)
    {
        $kodeBooking = $request->input('kode_booking');
        if (!$kodeBooking) {
            abort(400, 'kode_booking tidak ditemukan');
        }

        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }

        $booking = Booking::where('kode_booking', $kodeBooking)->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Booking berhasil dihapus.');
    }
}

