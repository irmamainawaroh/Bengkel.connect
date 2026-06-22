<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JasaLayanan extends Model
{
    protected $table = 'jasa_layanans';

    protected $fillable = [
        'id_jasa',
        'nama_jasa',
        'estimasi_harga',
        'is_locked',
    ];
}

