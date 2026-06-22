<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_updates', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking');
            $table->unsignedBigInteger('mekanik_id');
            $table->text('update_text');
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
            
            $table->foreign('kode_booking')->references('kode_booking')->on('bookings')->onDelete('cascade');
            $table->foreign('mekanik_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('kode_booking');
            $table->index('mekanik_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_updates');
    }
};
