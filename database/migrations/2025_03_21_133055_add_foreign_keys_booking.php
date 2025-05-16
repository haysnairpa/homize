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
        Schema::table('booking', function (Blueprint $table) {
            $table->foreign('id_user')->index('fk_booking_to_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_merchant')->index('fk_booking_to_merchant')->references('id')->on('merchant')->onDelete('cascade');
            $table->foreign('id_layanan')->index('fk_booking_to_layanan')->references('id')->on('layanan')->onDelete('cascade');
            $table->foreign("id_booking_schedule")->index("fk_booking_to_booking_schedule")->references("id")->on("booking_schedule")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign("fk_booking_to_user");
            $table->dropForeign("fk_booking_to_merchant");
            $table->dropForeign("fk_booking_to_layanan");
            $table->dropForeign("fk_booking_to_status");
            $table->dropForeign("fk_booking_to_booking_schedule");
        });
    }
};
