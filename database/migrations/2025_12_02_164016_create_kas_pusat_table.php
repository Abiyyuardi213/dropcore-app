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
        Schema::create('kas_pusat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('saldo_awal', 18, 2)->default(0);
            $table->decimal('saldo_saat_ini', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_pusat');
    }
};
