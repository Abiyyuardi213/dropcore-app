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
            $table->string('nip')->unique()->nullable()->after('id');
            $table->text('alamat')->nullable()->after('no_telepon');

            $table->uuid('divisi_id')->nullable()->after('role_id');
            $table->uuid('jabatan_id')->nullable()->after('divisi_id');

            $table->date('tanggal_bergabung')->nullable()->after('jabatan_id');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('tanggal_bergabung');
            $table->enum('status_kepegawaian', ['aktif', 'nonaktif'])->default('aktif')->after('jenis_kelamin');

            $table
                ->foreign('divisi_id')
                ->references('id')
                ->on('divisi')
                ->nullOnDelete();

            $table
                ->foreign('jabatan_id')
                ->references('id')
                ->on('jabatan')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
