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
        Schema::table('pengeluaran_barang_detail', function (Blueprint $table) {

            $table->char('stok_id', 36)->after('produk_id');

            $table->foreign('stok_id')
                ->references('id')->on('stok')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->index(['produk_id']);
            $table->index(['gudang_id']);
            $table->index(['area_id']);
            $table->index(['rak_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengeluaran_barang_detail', function (Blueprint $table) {
            $table->dropForeign(['stok_id']);
            $table->dropColumn('stok_id');
        });
    }
};
