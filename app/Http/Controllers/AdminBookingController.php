<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        // pakai session role dari AuthController (role tersimpan di session)
        if (!session('role') || session('role') !== 'admin') {
            abort(403);
        }

        // Bedakan data booking bengkel vs home service berdasarkan adanya kolom alamat.
        // - Workshop/bengkel: alamat biasanya NULL
        // - Home service: alamat terisi
        $bookingsBengkel = Booking::whereNull('alamat')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $bookingsHomeService = Booking::whereNotNull('alamat')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.dashboard', compact('bookingsBengkel', 'bookingsHomeService'));
    }
}

