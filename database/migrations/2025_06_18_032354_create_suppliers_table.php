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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_supplier');
            $table->string('email');
            $table->string('no_telepon');
            $table->text('alamat');
            $table->uuid('wilayah_id');
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->uuid('provinsi_id');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
            $table->uuid('kota_id');
            $table->foreign('kota_id')->references('id')->on('kota')->onDelete('cascade');
            $table->uuid('kecamatan_id');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('cascade');
            $table->uuid('kelurahan_id');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onDelete('cascade');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
