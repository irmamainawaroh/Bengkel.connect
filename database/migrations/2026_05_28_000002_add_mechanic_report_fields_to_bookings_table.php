<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_biaya_perbaikan', 12, 0)->nullable()->after('assigned_at');
            $table->text('laporan_perbaikan')->nullable()->after('total_biaya_perbaikan');
            $table->string('bukti_pengerjaan_path')->nullable()->after('laporan_perbaikan');
            $table->timestamp('selesai_at')->nullable()->after('bukti_pengerjaan_path');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('total_biaya_perbaikan');
            $table->dropColumn('laporan_perbaikan');
            $table->dropColumn('bukti_pengerjaan_path');
            $table->dropColumn('selesai_at');
        });
    }
};

