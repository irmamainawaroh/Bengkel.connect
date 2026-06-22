<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Simpan rekomendasi part dalam bentuk JSON array string.
            $table->json('recommended_parts')->nullable()->after('laporan_perbaikan');

            // Catatan tambahan mekanik (akan ditampilkan ke admin).
            // Menghindari tabrakan dengan catatan pelanggan, maka pakai kolom terpisah.
            $table->text('mechanic_note')->nullable()->after('recommended_parts');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('recommended_parts');
            $table->dropColumn('mechanic_note');
        });
    }
};

