<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Anti-double booking untuk home service: 1 slot = 1 booking per (tanggal, waktu)
        Schema::table('bookings', function (Blueprint $table) {
            $table->unique(['tanggal', 'waktu'], 'bookings_unique_slot_tanggal_waktu');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique('bookings_unique_slot_tanggal_waktu');
        });
    }
};

