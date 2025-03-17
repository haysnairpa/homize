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
        Schema::table("shop_services", function (Blueprint $table) {
            // from shop services to shop
            $table->foreign("id_shop", "fk_shop_services_to_shop")->references("id")->on("shop")->onDelete("CASCADE");
            // from shop services to services
            $table->foreign("id_services", "fk_shop_services_to_services")->references("id")->on("services")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("shop_services", function (Blueprint $table) {
            $table->dropForeign("fk_shop_services_to_shop");
            $table->dropForeign("fk_shop_services_to_services");
        });
    }
};
