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
        Schema::table("order", function (Blueprint $table) {
            // from order to category
            $table->foreign("id_customer", "fk_order_to_category")->references("id")->on("customer")->onDelete("CASCADE")->onUpdate("CASCADE");
            // from order to shop service
            $table->foreign("id_services", "fk_order_to_shop_services")->references("id")->on("shop_services")->onDelete("CASCADE")->onUpdate("CASCADE");
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
