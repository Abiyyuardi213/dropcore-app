<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 2)->nullable();
        });
        foreach (['penerimaan_barang', 'pengeluaran_barang'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->change();
                $table->string('payment_status', 20)->default('unpaid');
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('canceled_at')->nullable();
            });
            $number = $name === 'penerimaan_barang' ? 'no_penerimaan' : 'no_pengeluaran';
            $prefix = $name === 'penerimaan_barang' ? 'Pembelian Stok Ref: ' : 'Penjualan Stok Ref: ';
            $type = $name === 'penerimaan_barang' ? 'pengeluaran' : 'pemasukkan';
            DB::table($name)->where('status', 'completed')->orderBy('id')->chunk(200, function ($records) use ($name, $number, $prefix, $type) {
                foreach ($records as $record) {
                    $entries = DB::table('keuangan')->where('keterangan', $prefix.$record->$number)
                        ->where('jenis_transaksi', $type)->where('status', 'approved')->get();
                    if ($entries->isEmpty()) continue;
                    foreach ($entries as $entry) {
                        DB::table('keuangan')->where('id', $entry->id)->update(['referensi_id' => $record->id, 'referensi_tabel' => $name]);
                    }
                    DB::table($name)->where('id', $record->id)->update(['payment_status' => 'paid', 'paid_at' => $entries->first()->created_at]);
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['penerimaan_barang', 'pengeluaran_barang'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn(['payment_status', 'paid_at', 'canceled_at']));
        }
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn('purchase_price'));
    }
};
