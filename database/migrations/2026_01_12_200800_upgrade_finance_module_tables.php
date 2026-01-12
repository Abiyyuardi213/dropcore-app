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
        // 1. Create Kategori Keuangan Table (COA)
        Schema::create('kategori_keuangan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->enum('tipe', ['pemasukkan', 'pengeluaran']);
            $table->string('kode')->nullable(); // e.g. 4001, 5001
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 2. Upgrade Sumber Keuangan (becoming Financial Accounts)
        Schema::table('sumber_keuangan', function (Blueprint $table) {
            $table->enum('jenis', ['bank', 'tunai', 'e-wallet'])->default('tunai')->after('nama_sumber');
            $table->string('nomor_rekening')->nullable()->after('jenis');
            $table->string('atas_nama')->nullable()->after('nomor_rekening');
            $table->decimal('saldo', 18, 2)->default(0)->after('atas_nama');
            $table->boolean('is_active')->default(true)->after('saldo');
        });

        // 3. Upgrade Keuangan (Transaction Ledger)
        Schema::table('keuangan', function (Blueprint $table) {
            $table->string('no_transaksi')->unique()->after('id')->nullable(); // Generated TRX-YYYYMMDD-001
            $table->uuid('kategori_keuangan_id')->nullable()->after('jenis_transaksi');
            $table->uuid('user_id')->nullable()->after('kategori_keuangan_id'); // Who made the transaction
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('jumlah');
            $table->string('bukti_transaksi')->nullable()->after('keterangan');

            // Foreign Keys
            $table->foreign('kategori_keuangan_id')->references('id')->on('kategori_keuangan')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $table->dropForeign(['kategori_keuangan_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['no_transaksi', 'kategori_keuangan_id', 'user_id', 'status', 'bukti_transaksi']);
        });

        Schema::table('sumber_keuangan', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'nomor_rekening', 'atas_nama', 'saldo', 'is_active']);
        });

        Schema::dropIfExists('kategori_keuangan');
    }
};
