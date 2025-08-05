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
        Schema::create('maintenance_produk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('produk_id');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status', ['Diperbaiki', 'Selesai', 'Dikirim ke Vendor', 'Tidak Diperbaiki']);
            $table->text('deskripsi')->nullable();
            $table->uuid('teknisi_id')->nullable();
            $table->text('hasil_perbaikan')->nullable();
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('teknisi_id')->references('id')->on('teknisi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_produk');
    }
};
