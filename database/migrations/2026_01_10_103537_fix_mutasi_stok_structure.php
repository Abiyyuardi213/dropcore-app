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
        Schema::table('mutasi_stok', function (Blueprint $table) {
            // Modify existing columns to be nullable
            $table->string('gudang_asal_id')->nullable()->change();
            $table->string('gudang_tujuan_id')->nullable()->change();

            // Add new columns
            $table->enum('jenis_mutasi', ['masuk', 'keluar', 'pindah'])->after('produk_id')->default('pindah');
            $table->string('referensi')->nullable()->after('quantity');
            $table->uuid('user_id')->nullable()->after('keterangan');
            $table->uuid('kondisi_id')->nullable()->after('produk_id');

            // Add Foreign Keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('kondisi_id')->references('id')->on('kondisi_barang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_stok', function (Blueprint $table) {
            // Remove foreign keys first
            $table->dropForeign(['user_id']);
            $table->dropForeign(['kondisi_id']);

            // Drop columns
            $table->dropColumn(['jenis_mutasi', 'referensi', 'user_id', 'kondisi_id']);

            // Revert strictness (Might fail if nulls exist, but standard down procedure)
            // We usually don't revert nullable changes dynamically easily without data issues.
            // keeping them nullable generally doesn't hurt logic if app handles it.
        });
    }
};
