<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update integer satuan values to string equivalents
        DB::table('tarif_layanan')->where('satuan', '1')->update(['satuan' => 'kilogram']);
        DB::table('tarif_layanan')->where('satuan', '2')->update(['satuan' => 'unit']);
        DB::table('tarif_layanan')->where('satuan', '3')->update(['satuan' => 'pcs']);
        DB::table('tarif_layanan')->where('satuan', '4')->update(['satuan' => 'pertemuan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, revert string satuan values back to integers
        DB::table('tarif_layanan')->where('satuan', 'kilogram')->update(['satuan' => '1']);
        DB::table('tarif_layanan')->where('satuan', 'unit')->update(['satuan' => '2']);
        DB::table('tarif_layanan')->where('satuan', 'pcs')->update(['satuan' => '3']);
        DB::table('tarif_layanan')->where('satuan', 'pertemuan')->update(['satuan' => '4']);
    }
};
