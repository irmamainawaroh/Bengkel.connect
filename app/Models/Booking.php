<?php

namespace App\Models;

use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $casts = [
        'recommended_parts' => 'array',

        // Ensure timestamp columns are always Carbon instances
        'assigned_at' => 'datetime',
        'selesai_at' => 'datetime',
        'lunas_at' => 'datetime',
    ];



    protected $fillable = [
        'kode_booking',
        'user_id',
        'mekanik_id',
        'nama',
        'telepon',
        'kendaraan',
        'nopol',
        'alamat',
        'layanan',
        'tanggal',
        'waktu',
        'catatan',
        'status',
        'bukti_dp_path',
        'qris_path',
        'bukti_total_pembayaran_path',
        'assigned_at',

        // Mechanic report
        'total_biaya_perbaikan',
        'laporan_perbaikan',
        'recommended_parts',
        'mechanic_note',
        'bukti_pengerjaan_path',
        'bukti_total_pembayaran_path',
        'selesai_at',
        'lunas_at',
    ];

    // Relationships
    public function progressUpdates()
    {
        return $this->hasMany(ProgressUpdate::class, 'kode_booking', 'kode_booking');
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class, 'kode_booking', 'kode_booking');
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mekanik_id', 'id');
    }

    // Get latest progress percentage
    public function getLatestProgressAttribute()
    {
        if ($this->relationLoaded('progressUpdates')) {
            return $this->progressUpdates->sortByDesc('created_at')->first()?->progress_percentage ?? 0;
        }

        $latest = $this->progressUpdates()->latest()->first();
        return $latest?->progress_percentage ?? 0;
    }

    // Get all progress updates ordered by latest
    public function getProgressHistoryAttribute()
    {
        return $this->progressUpdates()->orderBy('created_at', 'desc')->get();
    }
}

