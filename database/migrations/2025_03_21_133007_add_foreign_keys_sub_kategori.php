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
        Schema::table('sub_kategori', function (Blueprint $table) {
            $table->foreignId('id_kategori')->index('fk_sub_kategori_to_kategori')->references('id')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_kategori', function (Blueprint $table) {
            $table->dropForeign("fk_sub_kategori_to_kategori");
        });
    }
};
