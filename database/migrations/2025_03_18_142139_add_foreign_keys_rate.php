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
        Schema::table("rate", function (Blueprint $table) {
            // fk rate to customer
            $table->foreign("id_customer", "fk_rate_to_customer")->references("id")->on("customer")->onDelete("CASCADE");
            // fk rate to shop
            $table->foreign("id_shop", "fk_rate_to_shop")->references("id")->on("shop")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("category_form", function (Blueprint $table) {
            $table->dropForeign("fk_rate_to_customer");
            $table->dropForeign("fk_rate_to_shop");
        });
    }
};
