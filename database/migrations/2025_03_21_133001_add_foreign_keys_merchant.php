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
        Schema::table('merchant', function (Blueprint $table) {
            $table->foreignId('id_user')->index('fk_merchant_to_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('id_sub_kategori')->index('fk_merchant_to_sub_kategori')->references('id')->on('sub_kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant', function (Blueprint $table) {
            $table->dropForeign("fk_merchant_to_user");
            $table->dropForeign("fk_merchant_to_sub_kategori");
        });
    }
};
