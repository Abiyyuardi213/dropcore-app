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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal');
            $table->uuid('gudang_id');
            $table->uuid('user_id'); // Petugas
            $table->string('status')->default('draft'); // draft, processed
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('opname_id');
            $table->uuid('produk_id');
            $table->uuid('area_id');
            $table->uuid('rak_id');
            $table->uuid('kondisi_id');

            $table->integer('qty_sistem'); // Snapshot saat create
            $table->integer('qty_fisik')->nullable(); // Input user
            $table->integer('selisih')->nullable(); // fisik - sistem
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->foreign('opname_id')->references('id')->on('stock_opnames')->onDelete('cascade');
            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('area_gudang')->onDelete('cascade');
            $table->foreign('rak_id')->references('id')->on('rak_gudang')->onDelete('cascade');
            $table->foreign('kondisi_id')->references('id')->on('kondisi_barang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_details');
        Schema::dropIfExists('stock_opnames');
    }
};
