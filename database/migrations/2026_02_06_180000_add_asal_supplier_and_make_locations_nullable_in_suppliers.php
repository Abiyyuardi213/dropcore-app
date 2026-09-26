<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (!Schema::hasColumn('suppliers', 'asal_supplier')) {
                $table->string('asal_supplier')->default('dalam_negeri')->after('tipe_supplier');
            }
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement("ALTER TABLE suppliers MODIFY wilayah_id VARCHAR(255) NULL");
        DB::statement("ALTER TABLE suppliers MODIFY provinsi_id VARCHAR(255) NULL");
        DB::statement("ALTER TABLE suppliers MODIFY kota_id VARCHAR(255) NULL");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'asal_supplier')) {
                $table->dropColumn('asal_supplier');
            }
        });
    }
};
