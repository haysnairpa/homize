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
        Schema::table("room", function (Blueprint $table) {
            // from room to shop
            $table->foreign("id_shop", "fk_room_to_shop")->references("id")->on("shop")->onDelete("CASCADE");
            // from room to customer
            $table->foreign("id_customer", "fk_room_to_customer")->references("id")->on("customer")->onDelete("CASCADE");
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
