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
            if (!Schema::hasColumn('users', 'divisi_id')) {
                $table->uuid('divisi_id')->nullable()->after('role_id');
                $table->foreign('divisi_id')->references('id')->on('divisi')->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'jabatan_id')) {
                $table->uuid('jabatan_id')->nullable()->after('divisi_id');
                $table->foreign('jabatan_id')->references('id')->on('jabatan')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'divisi_id')) {
                $table->dropForeign(['divisi_id']);
                $table->dropColumn('divisi_id');
            }
            if (Schema::hasColumn('users', 'jabatan_id')) {
                $table->dropForeign(['jabatan_id']);
                $table->dropColumn('jabatan_id');
            }
        });
    }
};
