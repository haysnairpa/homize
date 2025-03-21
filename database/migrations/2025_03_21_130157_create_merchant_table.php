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
        Schema::create('merchant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->index('fk_merchant_to_user');
            $table->foreignId('id_sub_kategori')->index('fk_merchant_to_sub_kategori');
            $table->string('nama_usaha');
            $table->string('profile_url');
            $table->string('alamat');
            $table->string('media_sosial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant');
    }
};
