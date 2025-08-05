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
        Schema::table('stok', function (Blueprint $table) {
            $table->uuid('kondisi_id')->nullable()->after('quantity');

            $table->foreign('kondisi_id')
                  ->references('id')
                  ->on('kondisi_barang')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stok', function (Blueprint $table) {
            $table->dropForeign(['kondisi_id']);
            $table->dropColumn('kondisi_id');
        });
    }
};
