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
        Schema::table('kantor', function (Blueprint $table) {
            $table->string('kode_kantor')->unique()->nullable()->after('id');
            $table->string('email')->nullable()->after('nama_kantor');
            $table->string('fax')->nullable()->after('telepon');
            $table->string('website')->nullable()->after('fax');
            $table->string('kepala_kantor')->nullable()->after('website');
            $table->date('tgl_berdiri')->nullable()->after('kepala_kantor');
            $table->text('keterangan')->nullable()->after('alamat');
            $table->string('logo')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kantor', function (Blueprint $table) {
            $table->dropColumn([
                'kode_kantor',
                'email',
                'fax',
                'website',
                'kepala_kantor',
                'tgl_berdiri',
                'keterangan',
                'logo',
            ]);
        });
    }
};
