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
        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('jasa_pengiriman_id')->nullable()->after('status');
            $table->uuid('metode_pembayaran_id')->nullable()->after('jasa_pengiriman_id');
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran_id');

            // Optional: Foreign keys if you want strict integrity
            // $table->foreign('jasa_pengiriman_id')->references('id')->on('jasa_pengiriman')->onDelete('set null');
            // $table->foreign('metode_pembayaran_id')->references('id')->on('metode_pembayaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
