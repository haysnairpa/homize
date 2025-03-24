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
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreign('id_user')->index('fk_wishlist_to_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_layanan')->index('fk_wishlist_to_layanan')->references('id')->on('layanan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlists', function(Blueprint $table) {
            $table->dropForeign('fk_wishlist_to_user');
            $table->dropForeign('fk_wishlist_to_layanan');
        });
    }
};
