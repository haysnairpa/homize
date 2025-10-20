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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_conversation');
            $table->enum('sender_type', ['user', 'merchant']);
            $table->unsignedBigInteger('id_sender');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('id_conversation')
                  ->references('id')
                  ->on('conversations')
                  ->onDelete('cascade');
            
            // Indexes for performance
            $table->index('id_conversation');
            $table->index(['sender_type', 'id_sender']);
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
