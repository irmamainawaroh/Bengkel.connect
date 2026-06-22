<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama' => 'Admin Bengkel',
                'email' => 'admin@gmail.com',
                'password' => 'admin1234',
                'no_hp' => '0812-0000-0001',
                'plat_nomor' => 'B 1111 ABC',
                'role' => 'admin',
            ],
            [
                'nama' => 'Mekanik Bengkel',
                'email' => 'mekanik@gmail.com',
                'password' => 'mekanik1234',
                'no_hp' => '0812-0000-0002',
                'plat_nomor' => 'B 2222 DEF',
                'role' => 'mekanik',
            ],
            [
                'nama' => 'Customer Demo',
                'email' => 'customer@bengkelconnect.test',
                'password' => 'customer12345',
                'no_hp' => '0812-0000-0003',
                'plat_nomor' => 'B 3333 GHI',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                [
                    'name' => $u['nama'],
                    'email' => $u['email'],
                    'password' => Hash::make($u['password']),
                    'no_hp' => $u['no_hp'],
                    'plat_nomor' => $u['plat_nomor'],
                    'role' => $u['role'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

