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
            $table->string('kode_supplier')->unique()->nullable()->after('id');
            $table->string('penanggung_jawab')->nullable()->after('nama_supplier');
            $table->string('website')->nullable()->after('email');
            $table->text('keterangan')->nullable()->after('alamat');
            $table->string('logo')->nullable()->after('keterangan');
            $table->string('tipe_supplier')->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn([
                'kode_supplier',
                'penanggung_jawab',
                'website',
                'keterangan',
                'logo',
                'tipe_supplier',
            ]);
        });
    }
};
