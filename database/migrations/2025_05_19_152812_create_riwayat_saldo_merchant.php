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
        Schema::create('riwayat_saldo_merchant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_merchant');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('saldo_sebelum', 10, 2);
            $table->decimal('saldo_sesudah', 10, 2);
            $table->string('tipe');
            $table->string('keterangan');
            $table->foreign('id_merchant')->references('id')->on('merchant')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_saldo_merchant');
    }
};
