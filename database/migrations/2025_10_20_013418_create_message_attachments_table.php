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
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_message');
            $table->string('file_url');
            $table->string('file_name');
            $table->string('file_type', 50);
            $table->unsignedInteger('file_size'); // in bytes
            $table->timestamps();
            
            // Foreign key
            $table->foreign('id_message')
                  ->references('id')
                  ->on('messages')
                  ->onDelete('cascade');
            
            // Index
            $table->index('id_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
    }
};
