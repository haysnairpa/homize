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
        Schema::table("category_form", function (Blueprint $table) {
            // from category form to category
            $table->foreign("id_category", "fk_category_form_to_category")->references("id")->on("category")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("category_form", function (Blueprint $table) {
            $table->dropForeign("fk_category_form_to_category");
        });
    }
};
