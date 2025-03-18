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
        Schema::table("category", function (Blueprint $table) {
            // from category to jasa category
            $table->foreign("id_category", "fk_category_to_jasa_category")->references("id")->on("jasa_category")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("category_form", function (Blueprint $table) {
            $table->dropForeign("fk_category_to_jasa_category");
        });
    }
};
