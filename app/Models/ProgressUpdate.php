<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgressUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking',
        'mekanik_id',
        'update_text',
        'progress_percentage',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'kode_booking', 'kode_booking');
    }

    public function mekanik()
    {
        return $this->belongsTo(User::class, 'mekanik_id', 'id');
    }
}
