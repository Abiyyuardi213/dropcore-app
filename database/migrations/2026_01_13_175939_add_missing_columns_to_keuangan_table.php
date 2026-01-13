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
        Schema::table('keuangan', function (Blueprint $table) {
            if (!Schema::hasColumn('keuangan', 'no_transaksi')) {
                $table->string('no_transaksi')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('keuangan', 'kategori_keuangan_id')) {
                $table->uuid('kategori_keuangan_id')->nullable()->after('jenis_transaksi');
            }
            if (!Schema::hasColumn('keuangan', 'status')) {
                $table->enum('status', ['pending', 'selesai', 'dibatalkan'])->default('pending')->after('keterangan');
            }
            if (!Schema::hasColumn('keuangan', 'bukti_transaksi')) {
                $table->string('bukti_transaksi')->nullable()->after('status');
            }
            if (!Schema::hasColumn('keuangan', 'user_id')) {
                $table->uuid('user_id')->nullable()->after('bukti_transaksi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('keuangan', 'no_transaksi')) $columnsToDrop[] = 'no_transaksi';
            if (Schema::hasColumn('keuangan', 'kategori_keuangan_id')) $columnsToDrop[] = 'kategori_keuangan_id';
            if (Schema::hasColumn('keuangan', 'status')) $columnsToDrop[] = 'status';
            if (Schema::hasColumn('keuangan', 'bukti_transaksi')) $columnsToDrop[] = 'bukti_transaksi';
            if (Schema::hasColumn('keuangan', 'user_id')) $columnsToDrop[] = 'user_id';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
