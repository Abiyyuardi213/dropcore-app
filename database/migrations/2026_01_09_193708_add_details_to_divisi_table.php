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
        Schema::table('divisi', function (Blueprint $table) {
            $table->uuid('parent_id')->nullable()->after('id');
            $table->foreign('parent_id')->references('id')->on('divisi')->onDelete('set null');
            $table->string('kepala_divisi')->nullable()->after('deskripsi');
            $table->string('lokasi')->nullable()->after('kepala_divisi');
            $table->string('email')->nullable()->after('lokasi');
            $table->string('logo')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('divisi', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'kepala_divisi', 'lokasi', 'email', 'logo']);
        });
    }
};
