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
        Schema::table('pengeluaran_barang', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->after('distributor_id');
            $table->string('referensi')->nullable()->after('tanggal_pengeluaran'); // SJ/PO Reference
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('completed')->after('referensi');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran_barang', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'referensi', 'status']);
        });
    }
};
