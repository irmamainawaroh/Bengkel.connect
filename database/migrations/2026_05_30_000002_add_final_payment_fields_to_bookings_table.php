<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('bukti_total_pembayaran_path')->nullable()->after('qris_path');
            $table->timestamp('lunas_at')->nullable()->after('bukti_total_pembayaran_path');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('bukti_total_pembayaran_path');
            $table->dropColumn('lunas_at');
        });
    }
};
