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
            if (!Schema::hasColumn('penerimaan_barang', 'supplier_id')) {
                $table->char('supplier_id', 36)->nullable()->after('distributor_id');
                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            }
            if (!Schema::hasColumn('penerimaan_barang', 'tipe_pengirim')) {
                $table->enum('tipe_pengirim', ['supplier', 'distributor', 'lainnya'])->default('supplier')->after('no_penerimaan');
            }
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
