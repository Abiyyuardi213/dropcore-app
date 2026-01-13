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
        Schema::create('transfer_saldos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('no_transaksi')->unique();

            $table->uuid('sumber_asal_id');
            $table->foreign('sumber_asal_id')->references('id')->on('sumber_keuangan')->cascadeOnDelete();

            $table->uuid('sumber_tujuan_id');
            $table->foreign('sumber_tujuan_id')->references('id')->on('sumber_keuangan')->cascadeOnDelete();

            $table->decimal('jumlah', 18, 2);
            $table->text('keterangan')->nullable();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_saldos');
    }
};
