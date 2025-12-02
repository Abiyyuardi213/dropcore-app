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
        Schema::create('keuangan', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->enum('jenis_transaksi', ['pemasukkan', 'pengeluaran']);

            $table->decimal('jumlah', 18, 2);

            $table->uuid('referensi_id')->nullable();
            $table->string('referensi_tabel')->nullable();

            $table->uuid('sumber_id')->nullable(); // relasi ke sumber_keuangan
            $table->foreign('sumber_id')->references('id')->on('sumber_keuangan')->nullOnDelete();

            $table->text('keterangan')->nullable();

            $table->date('tanggal_transaksi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};
