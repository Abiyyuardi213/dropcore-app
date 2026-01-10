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
        Schema::table('gudang', function (Blueprint $table) {
            $table->string('jenis_gudang')->nullable()->after('nama_gudang'); // Utama, Transit, Retur, Produksi
            $table->string('pic')->nullable()->after('lokasi');
            $table->string('kapasitas')->nullable()->after('pic'); // ex: 1000m3 or 5000 items
            $table->string('luas_area')->nullable()->after('kapasitas'); // ex: 100m2
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang', function (Blueprint $table) {
            $table->dropColumn(['jenis_gudang', 'pic', 'kapasitas', 'luas_area']);
        });
    }
};
