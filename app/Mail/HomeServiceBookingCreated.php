<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HomeServiceBookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $totalPembayaran;

    public function __construct(Booking $booking, string $totalPembayaran)
    {
        $this->booking = $booking;
        $this->totalPembayaran = $totalPembayaran;
    }

    public function build()
    {
        return $this->subject('Booking Home Service Baru - ' . $this->booking->kode_booking)
            ->view('emails.home-service-booking-created')
            ->with([
                'booking' => $this->booking,
                'totalPembayaran' => $this->totalPembayaran,
            ]);
    }
}
