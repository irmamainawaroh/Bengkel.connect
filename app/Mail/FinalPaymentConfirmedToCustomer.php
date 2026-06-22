<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinalPaymentConfirmedToCustomer extends Mailable
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
            ->subject('Tagihan Anda Sudah Lunas - ' . $this->booking->kode_booking)
            ->view('emails.final-payment-confirmed-to-customer')
            ->with([
                'booking' => $this->booking,
            ]);
    }
}

