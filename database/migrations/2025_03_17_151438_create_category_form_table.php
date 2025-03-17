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
        Schema::create('category_form', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->foreignId("id_category")->index("fk_category_form_to_category");
            $table->string("form_name");
            $table->string("form_input");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_form');
    }
};
