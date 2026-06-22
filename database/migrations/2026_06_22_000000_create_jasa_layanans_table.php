<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jasa_layanans', function (Blueprint $table) {
            $table->id();
            $table->string('id_jasa')->unique();
            $table->string('nama_jasa');
            $table->unsignedInteger('estimasi_harga')->default(0);
            $table->boolean('is_locked')->default(false); // untuk id_jasa tertentu (L00)
            $table->timestamps();
        });

        // seed record default: L00
        $now = now();
        DB::table('jasa_layanans')->insertOrIgnore([
            [
                'id_jasa' => 'L00',
                'nama_jasa' => 'Lainnya (Tulis Manual)',
                'estimasi_harga' => 0,
                'is_locked' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('jasa_layanans');
    }
};


