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
        // 1. Drop dependent Foreign Keys in Suppliers (if any)
        // Note: We need to handle this carefully. For now, we assume we can re-create constraints.
        // Or if the constraint names are standard.
        // Let's rely on table dropping.
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('kelurahan');
        Schema::dropIfExists('kecamatan');
        Schema::dropIfExists('kota');
        Schema::dropIfExists('provinsi');
        Schema::dropIfExists('wilayah');

        // 2. Create Lighter Tables with Char IDs (Standard Codes)

        // Wilayah (Optional wrapper for Indonesia)
        Schema::create('wilayah', function (Blueprint $table) {
            $table->char('id', 2)->primary(); // e.g. "62" or "ID"
            $table->string('name');
            $table->timestamps();
        });

        // 11 (Aceh) - 94 (Papua)
        Schema::create('provinsi', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->char('wilayah_id', 2)->default('62'); // Default Indonesia
            $table->string('name');
            $table->timestamps();

            $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('cascade');
        });

        // 1101 (Kab. Aceh Selatan)
        Schema::create('kota', function (Blueprint $table) {
            $table->char('id', 4)->primary();
            $table->char('provinsi_id', 2);
            $table->string('name');
            $table->timestamps();

            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
        });

        // 1101010 (Bakongan)
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->char('id', 7)->primary();
            $table->char('kota_id', 4);
            $table->string('name');
            $table->timestamps();

            $table->foreign('kota_id')->references('id')->on('kota')->onDelete('cascade');
        });

        // 1101012001 (Keude Bakongan)
        Schema::create('kelurahan', function (Blueprint $table) {
            $table->char('id', 10)->primary();
            $table->char('kecamatan_id', 7);
            $table->string('name');
            $table->timestamps();

            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('cascade');
        });

        // 3. Update Suppliers Table Columns to match Char IDs
        // Note: Existing data will be invalid if it used UUIDs. We assume reset or empty.
        // Changing column type from UUID (char 36) to Char (variable) is okayish for size reduction but data is incompatible.
        // We will just alter the columns.

        Schema::table('suppliers', function (Blueprint $table) {
            // Drop old FKs first
            $table->dropForeign(['wilayah_id']);
            $table->dropForeign(['provinsi_id']);
            $table->dropForeign(['kota_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['kelurahan_id']);

            // Drop old columns to avoid data truncation errors (since old UUIDs won't fit in char(2/4/etc))
            $table->dropColumn(['wilayah_id', 'provinsi_id', 'kota_id', 'kecamatan_id', 'kelurahan_id']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            // Create new columns as nullable to prevent constraint errors on empty regional tables
            $table->char('wilayah_id', 2)->nullable();
            $table->char('provinsi_id', 2)->nullable();
            $table->char('kota_id', 4)->nullable();
            $table->char('kecamatan_id', 7)->nullable();
            $table->char('kelurahan_id', 10)->nullable();

            // Re-add FKs
            $table->foreign('wilayah_id')->references('id')->on('wilayah')->onDelete('cascade');
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->onDelete('cascade');
            $table->foreign('kota_id')->references('id')->on('kota')->onDelete('cascade');
            $table->foreign('kecamatan_id')->references('id')->on('kecamatan')->onDelete('cascade');
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
