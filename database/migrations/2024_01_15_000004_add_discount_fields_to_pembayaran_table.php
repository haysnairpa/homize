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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->decimal('original_amount', 15, 2)->nullable()->after('amount')->comment('Harga asli sebelum diskon');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('original_amount')->comment('Nominal diskon yang diberikan');
            
            // Index for reporting purposes
            $table->index(['original_amount', 'discount_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn([
                'original_amount',
                'discount_amount'
            ]);
        });
    }
};