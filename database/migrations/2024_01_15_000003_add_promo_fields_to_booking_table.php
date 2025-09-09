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
        Schema::table('booking', function (Blueprint $table) {
            $table->unsignedBigInteger('kode_promo_id')->nullable()->after('id_layanan')->comment('ID kode promo yang digunakan');
            $table->decimal('original_amount', 15, 2)->nullable()->after('kode_promo_id')->comment('Harga asli sebelum diskon');
            $table->decimal('diskon_amount', 15, 2)->default(0)->after('original_amount')->comment('Nominal diskon yang diberikan');
            $table->decimal('diskon_percentage', 5, 2)->default(0)->after('diskon_amount')->comment('Persentase diskon yang diberikan');
            $table->decimal('final_amount', 15, 2)->nullable()->after('diskon_percentage')->comment('Harga final setelah diskon');
            
            // Foreign key constraint
            $table->foreign('kode_promo_id')->references('id')->on('kode_promo')->onDelete('set null');
            
            // Index
            $table->index('kode_promo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropForeign(['kode_promo_id']);
            $table->dropColumn([
                'kode_promo_id',
                'original_amount',
                'diskon_amount',
                'diskon_percentage',
                'final_amount'
            ]);
        });
    }
};