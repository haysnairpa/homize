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
            $table->integer('id_user');
            $table->integer('id_sub_kategori');
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
