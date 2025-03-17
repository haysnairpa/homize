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
        Schema::table("shop", function (Blueprint $table) {
            // from shop to category
            $table->foreign("id_category", "fk_shop_to_category")->references("id")->on("category")->onDelete("CASCADE")->onUpdate("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("shop", function (Blueprint $table) {
            $table->dropForeign("fk_shop_to_category");
        });
    }
};
