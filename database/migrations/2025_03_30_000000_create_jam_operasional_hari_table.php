<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jam_operasional_hari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jam_operasional')->constrained('jam_operasional')->onDelete('cascade');
            $table->foreignId('id_hari')->constrained('hari')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jam_operasional_hari');
    }
};
