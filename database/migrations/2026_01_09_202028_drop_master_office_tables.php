<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('jabatan');
        Schema::dropIfExists('divisi');
        Schema::dropIfExists('kantor');
    }

    public function down(): void
    {
        // Leaving empty as recreating them requires original schema definitions
    }
};
