<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceBiayaPerbaikanSentToCustomer extends Mailable
{
    use Queueable, SerializesModels;

    // Gunakan public agar variabel otomatis bisa diakses di dalam view Blade
    public Booking $booking;

    public function __construct(Booking $booking)
    {
        // Memastikan relasi user/customer ikut terbawa agar tidak error saat mengambil email di Controller
        $this->booking = $booking->load(['user', 'pembayaran']); 
    }

    /**
     * Mengatur Subject Email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nota Tagihan Biaya Perbaikan - ' . $this->booking->kode_booking,
        );
    }

    /**
     * Mengatur View dan Data yang dikirim
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-biaya-perbaikan-sent',
            with: [
                'booking' => $this->booking,
            ],
        );
    }
}