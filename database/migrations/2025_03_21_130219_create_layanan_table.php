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
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_merchant')->index('fk_layanan_to_merchant');
            $table->foreignId('id_jam_operasional')->index('fk_layanan_to_jam_operasional');
            $table->foreignId('id_sub_kategori')->index('fk_layanan_to_sub_kategori');
            $table->string('nama_layanan');
            $table->string('deskripsi_layanan');
            $table->integer('pengalaman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
