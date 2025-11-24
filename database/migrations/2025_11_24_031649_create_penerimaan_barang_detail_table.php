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
        Schema::create('penerimaan_barang_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('penerimaan_id', 36);
            $table->char('produk_id', 36);
            $table->integer('qty');
            $table->uuid('kondisi_id')->nullable();
            $table->uuid('gudang_id');
            $table->uuid('area_id');
            $table->uuid('rak_id');
            $table->timestamps();

            $table->foreign('penerimaan_id')
                ->references('id')->on('penerimaan_barang')
                ->onDelete('cascade');

            $table->foreign('produk_id')->references('id')->on('products');
            $table->foreign('kondisi_id')->references('id')->on('kondisi_barang');

            $table->foreign('gudang_id')->references('id')->on('gudang');
            $table->foreign('area_id')->references('id')->on('area_gudang');
            $table->foreign('rak_id')->references('id')->on('rak_gudang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang_detail');
    }
};
