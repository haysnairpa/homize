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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->index('fk_booking_to_user');
            $table->foreignId('id_merchant')->index('fk_booking_to_merchant');
            $table->foreignId('id_layanan')->index('fk_booking_to_layanan');
            $table->foreignId('id_status')->index('fk_booking_to_status');
            $table->foreignId("id_booking_schedule")->index("fk_booking_to_booking_schedule");
            $table->time('tanggal_booking');
            $table->string('catatan');
            $table->string('alamat_pembeli');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
