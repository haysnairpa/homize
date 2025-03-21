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
        Schema::table('jam_operasional', function (Blueprint $table) {
            $table->foreignId('id_hari')->index('fk_jam_operasional_to_hari')->references('id')->on('hari')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jam_operasional', function (Blueprint $table) {
            $table->dropForeign("fk_jam_operasional_to_hari");
        });
    }
};
