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
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first. Note: index names might vary, but usually table_column_foreign
            $table->dropForeign(['divisi_id']);
            $table->dropForeign(['jabatan_id']);

            $table->dropColumn(['divisi_id', 'jabatan_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('divisi_id')->nullable();
            $table->uuid('jabatan_id')->nullable();
        });
    }
};
