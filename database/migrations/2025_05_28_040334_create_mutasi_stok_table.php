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
        Schema::create('mutasi_stok', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('produk_id');
            $table->string('gudang_asal_id');
            $table->string('area_asal_id')->nullable();
            $table->string('rak_asal_id')->nullable();
            $table->string('gudang_tujuan_id');
            $table->string('area_tujuan_id')->nullable();
            $table->string('rak_tujuan_id')->nullable();
            $table->integer('quantity');
            $table->date('tanggal_mutasi')->default(now());
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('gudang_asal_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->foreign('area_asal_id')->references('id')->on('area_gudang')->onDelete('set null');
            $table->foreign('rak_asal_id')->references('id')->on('rak_gudang')->onDelete('set null');

            $table->foreign('gudang_tujuan_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->foreign('area_tujuan_id')->references('id')->on('area_gudang')->onDelete('set null');
            $table->foreign('rak_tujuan_id')->references('id')->on('rak_gudang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
