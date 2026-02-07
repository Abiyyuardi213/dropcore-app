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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement("ALTER TABLE suppliers MODIFY kecamatan_id CHAR(36) NULL");
        DB::statement("ALTER TABLE suppliers MODIFY kelurahan_id CHAR(36) NULL");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE suppliers MODIFY kecamatan_id CHAR(36) NOT NULL");
        DB::statement("ALTER TABLE suppliers MODIFY kelurahan_id CHAR(36) NOT NULL");
    }
};
