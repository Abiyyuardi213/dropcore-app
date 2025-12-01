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
        Schema::create('pengeluaran_barang_detail', function (Blueprint $table) {
            $table->char('id', 36)->primary();

            $table->char('pengeluaran_id', 36);
            $table->char('produk_id', 36);

            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);

            $table->char('kondisi_id', 36)->nullable();

            $table->char('gudang_id', 36);
            $table->char('area_id', 36);
            $table->char('rak_id', 36);

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('pengeluaran_id')
                ->references('id')->on('pengeluaran_barang')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('produk_id')
                ->references('id')->on('products')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('kondisi_id')
                ->references('id')->on('kondisi_barang')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('gudang_id')
                ->references('id')->on('gudang')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('area_id')
                ->references('id')->on('area_gudang')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('rak_id')
                ->references('id')->on('rak_gudang')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_barang_detail');
    }
};
