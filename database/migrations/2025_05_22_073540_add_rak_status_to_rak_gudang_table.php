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
            $table->boolean('rak_status')->default(1)->after('keterangan'); // 1 = Aktif, 0 = Nonaktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rak_gudang', function (Blueprint $table) {
            $table->dropColumn('rak_status');
        });
    }
};
