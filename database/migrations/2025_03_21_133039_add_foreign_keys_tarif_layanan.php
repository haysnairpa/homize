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
        Schema::table("tarif_layanan", function (Blueprint $table) {
            $table->foreign('id_revisi')->index('fk_tarif_layanan_to_revisi')->references('id')->on('revisi')->onDelete('cascade');
            $table->foreign('id_layanan')->index('fk_tarif_layanan_to_layanan')->references('id')->on('layanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("tarif_layanan", function (Blueprint $table) {
            $table->dropForeign("fk_tarif_layanan_to_revisi");
            $table->dropForeign("fk_tarif_layanan_to_layanan");
        });
    }
};
