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
        Schema::create('rate', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->foreignId("id_customer")->index("fk_rate_to_customer");
            $table->foreignId("id_shop")->index("fk_rate_to_shop");
            $table->integer("rate");
            $table->string("message")->nullable();
            $table->string("media_url")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate');
    }
};
