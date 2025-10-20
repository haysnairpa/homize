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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_booking')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_merchant');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('id_booking')
                  ->references('id')
                  ->on('booking')
                  ->onDelete('set null');
                  
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('id_merchant')
                  ->references('id')
                  ->on('merchant')
                  ->onDelete('cascade');
            
            // Indexes for performance
            $table->index(['id_user', 'id_merchant']);
            $table->index('id_booking');
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
