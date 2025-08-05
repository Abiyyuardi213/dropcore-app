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
        Schema::create('riwayat_aktivitas_produk', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('produk_id');
            $table->foreign('produk_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('tipe_aktvitas');
            $table->text('deskripsi')->nullable();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_aktivitas_produk');
    }
};
