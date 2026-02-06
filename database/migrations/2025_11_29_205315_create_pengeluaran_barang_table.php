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

            $table->enum('tipe_penerima', ['distributor', 'konsumen']);

            $table->char('distributor_id', 36)->nullable();

            $table->string('nama_konsumen')->nullable();
            $table->string('telepon_konsumen')->nullable();
            $table->string('alamat_konsumen')->nullable();

            $table->date('tanggal_pengeluaran');

            $table->text('keterangan')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('distributor_id')
                ->references('id')->on('distributor')
                ->onDelete('set null')
                ->onUpdate('cascade');
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
