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
            $table->string('pic_nama')->nullable()->after('alamat');
            $table->string('pic_telepon', 20)->nullable()->after('pic_nama');
            $table->string('tipe_distributor', 50)->default('Distributor')->after('nama_distributor'); // Principal, Distributor, Reseller
            $table->string('status', 20)->default('active')->after('tipe_distributor'); // active, inactive, blacklisted
            $table->string('npwp', 30)->nullable()->after('email');
            $table->string('website')->nullable()->after('npwp');
            $table->text('keterangan')->nullable()->after('kota_id');
            $table->decimal('latitude', 10, 8)->nullable()->after('keterangan');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributor', function (Blueprint $table) {
            $table->dropColumn([
                'pic_nama',
                'pic_telepon',
                'tipe_distributor',
                'status',
                'npwp',
                'website',
                'keterangan',
                'latitude',
                'longitude'
            ]);
        });
    }
};
