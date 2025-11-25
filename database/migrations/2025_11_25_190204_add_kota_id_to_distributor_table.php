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
        Schema::table('distributor', function (Blueprint $table) {
            $table->char('kota_id', 36)->nullable()->after('alamat');

            $table->foreign('kota_id')
                  ->references('id')
                  ->on('kota')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributor', function (Blueprint $table) {
            //
        });
    }
};
