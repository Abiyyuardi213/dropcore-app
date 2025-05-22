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
        Schema::create('rak_gudang', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->uuid('area_id');
            $table->foreign('area_id')->references('id')->on('area_gudang')->onDelete('cascade');
            $table->string('kode_rak');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rak_gudang');
    }
};
