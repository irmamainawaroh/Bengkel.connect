<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'plat_nomor',
        'role',
    ];


    protected $hidden = [

        'password'

    ];

    public $timestamps = true;
}