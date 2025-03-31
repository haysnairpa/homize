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
        Schema::create('tarif_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_revisi')->nullable()->index('fk_tarif_layanan_to_revisi');
            $table->foreignId('id_layanan')->index('fk_tarif_layanan_to_layanan');
            $table->integer('harga');
            $table->string('satuan')->nullable();
            $table->integer('durasi');
            $table->string('tipe_durasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_layanan');
    }
};
