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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['kelurahan_id']);
            $table->dropColumn(['website', 'kecamatan_id', 'kelurahan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('website')->nullable()->after('email');
            $table->uuid('kecamatan_id')->nullable()->after('kota_id');
            $table->uuid('kelurahan_id')->nullable()->after('kecamatan_id');

            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('cascade');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onDelete('cascade');
        });
    }
};
