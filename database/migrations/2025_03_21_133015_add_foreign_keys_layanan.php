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
        Schema::table('layanan', function (Blueprint $table) {
            $table->foreignId('id_jam_operasional')->index('fk_layanan_to_jam_operasional')->references('id')->on('jam_operasional')->onDelete('cascade');
            $table->foreignId('id_sub_kategori')->index('fk_layanan_to_sub_kategori')->references('id')->on('sub_kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropForeign("fk_layanan_to_jam_operasional");
            $table->dropForeign("fk_layanan_to_sub_kategori");
        });
    }
};
