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
        Schema::create('penggunaan_kode_promo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kode_promo_id')->comment('ID kode promo yang digunakan');
            $table->unsignedBigInteger('user_id')->comment('ID user yang menggunakan');
            $table->unsignedBigInteger('booking_id')->comment('ID booking terkait');
            $table->decimal('diskon_amount', 15, 2)->comment('Nominal diskon yang diberikan');
            $table->decimal('original_amount', 15, 2)->comment('Harga asli sebelum diskon');
            $table->decimal('final_amount', 15, 2)->comment('Harga final setelah diskon');
            $table->datetime('tanggal_digunakan')->comment('Tanggal dan waktu penggunaan');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('kode_promo_id')->references('id')->on('kode_promo')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            
            // Indexes
            $table->index(['kode_promo_id', 'user_id']);
            $table->index(['user_id', 'tanggal_digunakan']);
            $table->index('booking_id');
            
            // Unique constraint to prevent duplicate usage for same booking
            $table->unique(['booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_kode_promo');
    }
};