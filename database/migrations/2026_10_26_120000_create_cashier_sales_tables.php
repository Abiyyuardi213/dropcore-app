<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kasir_sales', function (Blueprint $t) {
            $t->uuid('id')->primary(); $t->string('invoice')->unique(); $t->uuid('user_id')->index();
            $t->uuid('submission_key'); $t->unique(['user_id', 'submission_key']);
            $t->uuid('pengeluaran_id')->unique(); $t->uuid('sumber_id'); $t->string('cashier_name');
            $t->string('customer_name'); $t->string('customer_phone', 30)->nullable();
            $t->date('sale_date')->index(); $t->decimal('discount_percent', 5, 2)->default(0);
            foreach (['subtotal','discount_amount','total','paid_amount','change_amount'] as $column) $t->decimal($column, 18, 2);
            $t->decimal('profit', 18, 2)->nullable(); $t->string('payment_type', 20); $t->timestamps();
        });
        Schema::create('kasir_sale_items', function (Blueprint $t) {
            $t->bigIncrements('id'); $t->uuid('sale_id')->index(); $t->uuid('product_id'); $t->uuid('stock_id');
            $t->uuid('category_id')->nullable(); $t->string('product_name'); $t->string('sku')->nullable();
            $t->string('unit')->nullable(); $t->string('location'); $t->unsignedInteger('quantity');
            $t->decimal('unit_price', 15, 2); $t->decimal('sale_unit_price', 15, 2);
            $t->decimal('purchase_price', 15, 2)->nullable(); $t->decimal('total', 18, 2);
        });
    }
    public function down(): void { Schema::dropIfExists('kasir_sale_items'); Schema::dropIfExists('kasir_sales'); }
};
