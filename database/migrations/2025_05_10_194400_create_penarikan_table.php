<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penarikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekening_merchant_id')->constrained('rekening_merchant')->cascadeOnDelete();
            $table->decimal('jumlah', 15, 2);
            $table->string('status')->default('Menunggu'); // Menunggu, Diproses, Selesai, Ditolak
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penarikan');
    }
};
