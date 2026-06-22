<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PaymentProofSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $filePath;
    public ?string $qrisPath;

    public function __construct(Booking $booking, string $filePath, ?string $qrisPath = null)
    {
        $this->booking = $booking;
        $this->filePath = $filePath;
        $this->qrisPath = $qrisPath;
    }

    public function build()
    {
        $email = $this->subject('Bukti Pembayaran Baru - Booking ' . $this->booking->kode_booking)
            ->view('emails.payment-proof-submitted')
            ->with([
                'booking' => $this->booking,
                'qrisPath' => $this->qrisPath,
            ])
            ->attachFromStorageDisk('public', $this->filePath, basename($this->filePath), [
                'mime' => Storage::disk('public')->mimeType($this->filePath),
            ]);

        // Attach QRIS if available
        if ($this->qrisPath && Storage::disk('public')->exists($this->qrisPath)) {
            $email->attachFromStorageDisk('public', $this->qrisPath, 'QRIS-' . $this->booking->kode_booking . '.png', [
                'mime' => 'image/png',
            ]);
        }

        return $email;
    }
}
