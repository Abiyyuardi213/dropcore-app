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
        Schema::create('retur_barang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('produk_id');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->enum('jenis', ['ke_supplier', 'dari_internal']);
            $table->text('alasan');
            $table->enum('tindakan_selanjutnya', ['ganti_baru', 'perbaikan', 'refund', 'buang']);
            $table->uuid('supplier_id')->nullable();
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_barang');
    }
};
