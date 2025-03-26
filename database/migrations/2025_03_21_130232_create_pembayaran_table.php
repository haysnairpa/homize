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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_booking')->index('fk_pembayaran_to_booking');
            $table->string('order_id')->nullable(); // Untuk Midtrans order ID
            $table->integer('amount');
            $table->string('method');
            $table->foreignId('id_status')->index('fk_pembayaran_to_status');
            $table->string('snap_token')->nullable(); // Untuk menyimpan token Midtrans
            $table->timestamp('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
