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
        Schema::table("desired_shop", function (Blueprint $table) {
            // from desired shop to customer
            $table->foreign("id_customer", "fk_desired_shop_to_customer")->references("id")->on("customer")->onDelete("CASCADE")->onUpdate("CASCADE");
            // from desired shop to shop
            $table->foreign("id_shop", "fk_desired_shop_to_shop")->references("id")->on("shop")->onDelete("CASCADE")->onUpdate("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
