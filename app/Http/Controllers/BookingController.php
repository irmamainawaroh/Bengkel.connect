<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'layanan'   => 'required|string',
            'tanggal'   => 'required|date',
            'waktu'     => 'required|string',
            'nama'      => 'required|string|max:255',
            'telepon'   => 'required|string|max:50',
            'kendaraan' => 'required|string|max:255',
            'nopol'     => 'required|string|max:50',
            'catatan'   => 'nullable|string',
        ]);

        $userId      = session('id_user');
        $kodeBooking = $this->generateKodeBooking();

        try {
            DB::transaction(function () use ($kodeBooking, $userId, $data) {
                Booking::create([
                    'kode_booking' => $kodeBooking,
                    'user_id'      => $userId,
                    'nama'         => $data['nama'],
                    'telepon'      => $data['telepon'],
                    'kendaraan'    => $data['kendaraan'],
                    'nopol'        => $data['nopol'],
                    'layanan'      => $data['layanan'],
                    'tanggal'      => $data['tanggal'],
                    'waktu'        => $data['waktu'],
                    'catatan'      => $data['catatan'] ?? null,
                    'status'       => 'berhasil', // ← diubah
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['waktu' => 'Slot untuk tanggal & waktu tersebut sudah penuh. Silakan pilih waktu lain.']);
        }

        Session::flash('kode_booking',      $kodeBooking);
        Session::flash('nama_booking',      $data['nama']);
        Session::flash('telepon_booking',   $data['telepon']);
        Session::flash('kendaraan_booking', $data['kendaraan']);
        Session::flash('nopol_booking',     $data['nopol']);
        Session::flash('layanan_booking',   $data['layanan']);
        Session::flash('tanggal_booking',   $data['tanggal']);
        Session::flash('waktu_booking',     $data['waktu']);

        return redirect('/booking/sukses');
    }

    private function generateKodeBooking(): string
    {
        $date = now()->format('Ymd');
        return 'BC-' . $date . '-' . strtoupper(Str::random(6));
    }
}