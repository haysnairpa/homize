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
        Schema::table('layanan_merchant', function (Blueprint $table) {
            $table->foreignId('id_layanan')->index('fk_layanan_merchant_to_layanan')->references('id')->on('layanan')->onDelete('cascade');
            $table->foreignId('id_merchant')->index('fk_layanan_merchant_to_merchant')->references('id')->on('merchant')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layanan_merchant', function (Blueprint $table) {
            $table->dropForeign("fk_layanan_merchant_to_layanan");
            $table->dropForeign("fk_layanan_merchant_to_merchant");
        });
    }
};
