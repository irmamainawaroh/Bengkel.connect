<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('nama');
            $table->string('telepon');
            $table->string('kendaraan');
            $table->string('nopol');
            $table->string('layanan');
            $table->date('tanggal');
            $table->string('waktu');
            $table->text('catatan')->nullable();

            $table->string('status')->default('menunggu_pembayaran');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

