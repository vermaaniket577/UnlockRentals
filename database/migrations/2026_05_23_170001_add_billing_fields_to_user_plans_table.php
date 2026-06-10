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
        Schema::table('user_plans', function (Blueprint $table) {
            $table->string('invoice_id')->nullable()->after('transaction_id');
            $table->string('billing_period')->default('monthly')->after('invoice_id');
            $table->decimal('subtotal_amount', 10, 2)->default(0.00)->after('billing_period');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('subtotal_amount');
            $table->decimal('gst_amount', 10, 2)->default(0.00)->after('discount_amount');
            $table->decimal('final_amount', 10, 2)->default(0.00)->after('gst_amount');
            $table->string('payment_method')->nullable()->after('final_amount');
            $table->boolean('auto_renew')->default(false)->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_id',
                'billing_period',
                'subtotal_amount',
                'discount_amount',
                'gst_amount',
                'final_amount',
                'payment_method',
                'auto_renew',
            ]);
        });
    }
};
