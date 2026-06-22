<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('mekanik_id')->nullable()->after('user_id');
            $table->timestamp('assigned_at')->nullable()->after('updated_at');
            $table->foreign('mekanik_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['mekanik_id']);
            $table->dropColumn('mekanik_id');
            $table->dropColumn('assigned_at');
        });
    }
};
