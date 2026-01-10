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
        Schema::table('area_gudang', function (Blueprint $table) {
            $table->string('jenis_area')->nullable()->after('nama_area'); // e.g., Dry, Cold, Dangerous
            $table->string('pic')->nullable()->after('jenis_area');
            $table->string('kapasitas_area')->nullable()->after('pic');
            $table->string('suhu')->nullable()->after('kapasitas_area'); // e.g., 25C
            $table->string('kelembaban')->nullable()->after('suhu'); // e.g., 60%
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('area_gudang', function (Blueprint $table) {
            $table->dropColumn(['jenis_area', 'pic', 'kapasitas_area', 'suhu', 'kelembaban']);
        });
    }
};
