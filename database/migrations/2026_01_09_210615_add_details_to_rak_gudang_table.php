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
        Schema::table('rak_gudang', function (Blueprint $table) {
            $table->string('jenis_rak')->nullable()->after('keterangan'); // e.g., Heavy Duty, Light Duty, Pallet Racking
            $table->string('posisi')->nullable()->after('jenis_rak'); // e.g., Aisle 1, Row 2, Level 3
            $table->string('kapasitas_max')->nullable()->after('posisi'); // e.g., 2000 kg
            $table->string('dimensi')->nullable()->after('kapasitas_max'); // e.g., 200x100x300 cm
            $table->string('bahan_rak')->nullable()->after('dimensi'); // e.g., Besi Baja
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rak_gudang', function (Blueprint $table) {
            $table->dropColumn(['jenis_rak', 'posisi', 'kapasitas_max', 'dimensi', 'bahan_rak']);
        });
    }
};
