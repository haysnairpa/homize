<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('penarikan', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu')->change();
        });
    }

    public function down()
    {
        Schema::table('penarikan', function (Blueprint $table) {
            $table->string('status')->default('menunggu')->change();
        });
    }
};
