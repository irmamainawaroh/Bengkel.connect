<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_histories';

    protected $fillable = [
        'kode_booking',
        'action',
        'amount',
        'remarks',
        'performed_by',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'kode_booking', 'kode_booking');
    }
}
