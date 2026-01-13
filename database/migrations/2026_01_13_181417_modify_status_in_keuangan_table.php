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
            // Modify the ENUM column using raw SQL to ensure 'selesai' is included
            // We use DB::statement because changing ENUMs via Schema builder can be tricky
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE keuangan MODIFY COLUMN status ENUM('pending', 'selesai', 'dibatalkan', 'approved') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            // Reverting is hard without knowing the exact previous state, 
            // but we generally expect it might have been just 'approved' or similar. 
            // Ideally we don't reverse this narrowly.
            // DB::statement("ALTER TABLE keuangan MODIFY COLUMN status ENUM('pending', 'approved') DEFAULT 'pending'");
        });
    }
};
