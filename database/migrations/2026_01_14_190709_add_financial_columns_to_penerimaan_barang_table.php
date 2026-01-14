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
        Schema::table('penerimaan_barang', function (Blueprint $table) {
            $table->decimal('subtotal', 15, 2)->default(0)->after('keterangan');
            $table->decimal('ppn_percentage', 5, 2)->default(10)->after('subtotal'); // Default 10%
            $table->decimal('ppn_amount', 15, 2)->default(0)->after('ppn_percentage');
            $table->decimal('total_amount', 15, 2)->default(0)->after('ppn_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penerimaan_barang', function (Blueprint $table) {
            //
        });
    }
};
