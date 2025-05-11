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
        Schema::create('rekening_merchant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_merchant')->constrained('merchant')->cascadeOnDelete();
            $table->string('nama_bank', 100);
            $table->string('nomor_rekening', 50);
            $table->string('nama_pemilik', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_merchant');
    }
};
