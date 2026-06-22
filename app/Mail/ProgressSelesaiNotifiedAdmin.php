<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgressSelesaiNotifiedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this
            ->subject('Progress Selesai - Invoice Terkirim - ' . $this->booking->kode_booking)
            ->view('emails.progress-selesai-notified-admin')
            ->with([
                'booking' => $this->booking,
            ]);

    }
}

