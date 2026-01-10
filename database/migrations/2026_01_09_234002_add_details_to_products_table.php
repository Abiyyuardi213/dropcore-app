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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('name');
            }
            if (!Schema::hasColumn('products', 'merk')) {
                $table->string('merk')->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('products', 'dimensi')) {
                $table->string('dimensi')->nullable()->after('merk');
            }
            if (!Schema::hasColumn('products', 'berat')) {
                $table->decimal('berat', 8, 2)->nullable()->after('dimensi');
            }
            if (!Schema::hasColumn('products', 'min_stock')) {
                $table->integer('min_stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'max_stock')) {
                $table->integer('max_stock')->nullable()->after('min_stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'merk', 'dimensi', 'berat', 'min_stock', 'max_stock']);
        });
    }
};
