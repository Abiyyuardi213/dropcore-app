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
        DB::statement("ALTER TABLE suppliers MODIFY email VARCHAR(255) NULL");
        DB::statement("ALTER TABLE suppliers MODIFY no_telepon VARCHAR(255) NULL");
        DB::statement("ALTER TABLE suppliers MODIFY alamat TEXT NULL");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement("ALTER TABLE suppliers MODIFY email VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE suppliers MODIFY no_telepon VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE suppliers MODIFY alamat TEXT NOT NULL");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
