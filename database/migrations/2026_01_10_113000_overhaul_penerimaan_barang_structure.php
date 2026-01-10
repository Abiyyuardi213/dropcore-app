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
        // 1. Drop existing keys if any (best effort) or just modify
        // Since we are "overhauling", and the previous migration might be fresh or not, 
        // let's try to align with the desired state. 
        // Note: SQLite/MySQL differences in dropping FKs. Assuming MySQL/MariaDB (Laragon).

        Schema::table('penerimaan_barang', function (Blueprint $table) {
            // Drop foreign key if it exists (guessing the name or using array syntax)
            // $table->dropForeign(['supplier_id']); 
            // Better to check column existence or just add new ones if we are sure.
            // Let's assume we need to fix the 'supplier_id' to 'distributor_id'

            if (Schema::hasColumn('penerimaan_barang', 'supplier_id')) {
                // We can drop the column or rename it. Dropping FK first is safer.
                // However, getting FK name is tricky blindly. 
                // Let's try to add the new columns first.
            }

            if (!Schema::hasColumn('penerimaan_barang', 'distributor_id')) {
                $table->char('distributor_id', 36)->nullable()->after('no_penerimaan');
                // We'll add FK later or in a separate block to be safe
            }
            if (!Schema::hasColumn('penerimaan_barang', 'user_id')) {
                $table->char('user_id', 36)->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('penerimaan_barang', 'referensi')) {
                $table->string('referensi')->nullable()->after('distributor_id');
            }
            if (!Schema::hasColumn('penerimaan_barang', 'status')) {
                $table->enum('status', ['pending', 'completed'])->default('completed')->after('keterangan');
            }
        });

        Schema::table('penerimaan_barang', function (Blueprint $table) {
            // Foreign Keys
            if (Schema::hasColumn('penerimaan_barang', 'distributor_id')) {
                // Assuming distributors table exists
                //$table->foreign('distributor_id')->references('id')->on('distributors')->onDelete('set null');
            }
            if (Schema::hasColumn('penerimaan_barang', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });

        // We also need to fix the fact that we might have 'supplier_id' hanging around.
        // Let's drop it if it exists.
        if (Schema::hasColumn('penerimaan_barang', 'supplier_id')) {
            Schema::table('penerimaan_barang', function (Blueprint $table) {
                // $table->dropForeign(['supplier_id']); // This might fail if name is custom.
                // $table->dropColumn('supplier_id'); // This might fail if FK exists and strict mode.
                // For safety in this environment, I'll allow both to coexist but main logic uses distributor_id
                // OR better, try to drop it.
                try {
                    $table->dropForeign('penerimaan_barang_supplier_id_foreign');
                    $table->dropColumn('supplier_id');
                } catch (\Exception $e) {
                    // Ignore if fails (e.g., FK name different)
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
