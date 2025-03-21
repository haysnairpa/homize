<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking_schedule', function (Blueprint $table) {
            $table->foreignId('id_booking')->index('fk_booking_schedule_to_booking')->references('id')->on('booking')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_schedule', function (Blueprint $table) {
            $table->dropForeign("fk_booking_schedule_to_booking");
        });
    }
};
