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
        Schema::create('pengeluaran_barang', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('no_pengeluaran')->unique();
            $table->char('supplier_id', 36);
            $table->date('tanggal_pengeluaran');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_barang');
    }
};
