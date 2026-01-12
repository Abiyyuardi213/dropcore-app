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
        // 1. Recover Divisi Table
        if (!Schema::hasTable('divisi')) {
            Schema::create('divisi', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('parent_id')->nullable(); // Self Reference
                $table->string('kode')->unique();
                $table->string('name')->unique();
                $table->text('deskripsi')->nullable();
                $table->string('kepala_divisi')->nullable();
                $table->string('lokasi')->nullable();
                $table->string('email')->nullable();
                $table->string('logo')->nullable();
                $table->boolean('status')->default(true);
                $table->softDeletes();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('divisi')->onDelete('set null');
            });
        }

        // 2. Recover Jabatan Table
        if (!Schema::hasTable('jabatan')) {
            Schema::create('jabatan', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('divisi_id')->nullable();
                $table->string('kode_jabatan')->unique();
                $table->string('name')->unique();
                $table->text('deskripsi')->nullable();

                // Details
                $table->text('tanggung_jawab')->nullable();
                $table->text('kualifikasi')->nullable();
                $table->decimal('gaji_pokok', 15, 2)->nullable();
                $table->decimal('tunjangan', 15, 2)->nullable();

                $table->boolean('status')->default(true);
                $table->timestamps();

                $table->foreign('divisi_id')->references('id')->on('divisi')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We do not drop tables in down() because this is a recovery migration.
        // If we roll back, we probably want to keep the tables if they were recovered.
        // But for consistency with standard behavior:
        // Schema::dropIfExists('jabatan');
        // Schema::dropIfExists('divisi');
    }
};
