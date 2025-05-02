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
            $table->string('contact_email')->nullable()->after('id_booking_schedule');
            $table->string('contact_phone')->nullable()->after('contact_email');
            $table->string('first_name')->nullable()->after('contact_phone');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('country')->nullable()->after('last_name');
            $table->string('city')->nullable()->after('country');
            $table->string('province')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('province');
            // Note: We're keeping the existing alamat_pembeli field but it will now store the complete address
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn([
                'contact_email',
                'contact_phone',
                'first_name',
                'last_name',
                'country',
                'city',
                'province',
                'postal_code'
            ]);
        });
    }
};
