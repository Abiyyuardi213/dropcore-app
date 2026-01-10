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
        Schema::table('jabatan', function (Blueprint $table) {
            $table->text('tanggung_jawab')->nullable()->after('deskripsi');
            $table->text('kualifikasi')->nullable()->after('tanggung_jawab');
            $table->decimal('gaji_pokok', 15, 2)->nullable()->after('kualifikasi');
            $table->decimal('tunjangan', 15, 2)->nullable()->after('gaji_pokok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatan', function (Blueprint $table) {
            $table->dropColumn(['tanggung_jawab', 'kualifikasi', 'gaji_pokok', 'tunjangan']);
        });
    }
};
