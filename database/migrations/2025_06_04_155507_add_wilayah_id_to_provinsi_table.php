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
        Schema::table('provinsi', function (Blueprint $table) {
            $table->uuid('wilayah_id')->after('id');

            $table->foreign('wilayah_id')
                ->references('id')
                ->on('wilayah')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinsi', function (Blueprint $table) {
            $table->dropForeign(['wilayah_id']);
            $table->dropColumn('wilayah_id');
        });
    }
};
