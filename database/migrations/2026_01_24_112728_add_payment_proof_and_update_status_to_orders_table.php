<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('proof_of_payment')->nullable()->after('payment_method');
        });

        // Update ENUM to include 'waiting_payment' and 'waiting_confirmation'
        // Note: 'pending' might still be used for legacy or as alias, but better to be explicit.
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending', 
            'waiting_payment', 
            'waiting_confirmation', 
            'processing', 
            'shipped', 
            'completed', 
            'cancelled', 
            'cancel_requested'
        ) NOT NULL DEFAULT 'waiting_payment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('proof_of_payment');
        });

        // Revert ENUM if needed, but risky if data exists with new statuses.
        // For now we just keep it or revert to previous set.
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending', 
            'processing', 
            'shipped', 
            'completed', 
            'cancelled', 
            'cancel_requested'
        ) NOT NULL DEFAULT 'pending'");
    }
};
