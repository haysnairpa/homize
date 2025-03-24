<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->index('fk_wishlist_to_user');
            $table->foreignId('id_layanan')->index('fk_wishlist_to_layanan');
            $table->timestamps();
            
            // Prevent duplicate wishlists
            $table->unique(['id_user', 'id_layanan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};