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
        Schema::create('kode_promo', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique()->comment('Kode promo unik');
            $table->string('nama', 255)->comment('Nama/deskripsi promo');
            $table->enum('tipe_diskon', ['percentage', 'fixed'])->comment('Tipe diskon: percentage atau fixed amount');
            $table->decimal('nilai_diskon', 15, 2)->comment('Nilai diskon (% atau nominal)');
            $table->datetime('tanggal_mulai')->comment('Tanggal mulai berlaku');
            $table->datetime('tanggal_berakhir')->comment('Tanggal berakhir');
            $table->integer('batas_penggunaan_global')->nullable()->comment('Batas penggunaan total (null = unlimited)');
            $table->integer('batas_penggunaan_per_user')->default(1)->comment('Batas penggunaan per user');
            $table->boolean('is_exclusive')->default(false)->comment('Apakah promo eksklusif (tidak bisa digabung)');
            $table->enum('target_type', ['all', 'category', 'service'])->default('all')->comment('Target promo: semua, kategori, atau layanan spesifik');
            $table->unsignedBigInteger('target_id')->nullable()->comment('ID target (kategori_id atau layanan_id)');
            $table->boolean('status_aktif')->default(true)->comment('Status aktif promo');
            $table->text('deskripsi')->nullable()->comment('Deskripsi detail promo');
            $table->decimal('minimum_pembelian', 15, 2)->nullable()->comment('Minimum pembelian untuk menggunakan promo');
            $table->decimal('maksimum_diskon', 15, 2)->nullable()->comment('Maksimum nominal diskon (untuk percentage)');
            $table->timestamps();
            
            // Indexes
            $table->index(['kode', 'status_aktif']);
            $table->index(['tanggal_mulai', 'tanggal_berakhir']);
            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_promo');
    }
};