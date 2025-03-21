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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->foreignId('id_booking')->index('fk_pembayaran_to_booking')->references('id')->on('booking')->onDelete('cascade');
            $table->foreignId('id_status')->index('fk_pembayaran_to_status')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign("fk_pembayaran_to_booking");
            $table->dropForeign("fk_pembayaran_to_status");
        });
    }
};
