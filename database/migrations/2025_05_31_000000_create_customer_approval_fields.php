<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerApprovalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->string('customer_approval_status')->nullable()->default(null)->comment('null=pending, approved, protested');
            $table->timestamp('customer_approval_date')->nullable();
            $table->text('protest_reason')->nullable();
            $table->timestamp('protest_date')->nullable();
            $table->boolean('merchant_balance_added')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn('customer_approval_status');
            $table->dropColumn('customer_approval_date');
            $table->dropColumn('protest_reason');
            $table->dropColumn('protest_date');
            $table->dropColumn('merchant_balance_added');
        });
    }
}
