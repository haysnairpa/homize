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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->foreignId("id_category")->index("fk_order_to_category");
            $table->foreignId("id_customer")->index("fk_order_to_customer");
            $table->string("customer_address");
            $table->foreignId("id_services")->index("fk_order_to_shop_services");
            $table->foreignId("id_status")->index("fk_order_to_status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
