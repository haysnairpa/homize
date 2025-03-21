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
        Schema::create('layanan_merchant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_layanan')->index('fk_layanan_merchant_to_layanan');
            $table->foreignId('id_merchant')->index('fk_layanan_merchant_to_merchant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_merchant');
    }
};
