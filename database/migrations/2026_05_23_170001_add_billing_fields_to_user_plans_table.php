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
            if (!Schema::hasColumn('user_plans', 'invoice_id')) {
                $table->string('invoice_id')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('user_plans', 'billing_period')) {
                $table->string('billing_period')->default('monthly')->after('invoice_id');
            }
            if (!Schema::hasColumn('user_plans', 'subtotal_amount')) {
                $table->decimal('subtotal_amount', 10, 2)->default(0.00)->after('billing_period');
            }
            if (!Schema::hasColumn('user_plans', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0.00)->after('subtotal_amount');
            }
            if (!Schema::hasColumn('user_plans', 'gst_amount')) {
                $table->decimal('gst_amount', 10, 2)->default(0.00)->after('discount_amount');
            }
            if (!Schema::hasColumn('user_plans', 'final_amount')) {
                $table->decimal('final_amount', 10, 2)->default(0.00)->after('gst_amount');
            }
            if (!Schema::hasColumn('user_plans', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('final_amount');
            }
            if (!Schema::hasColumn('user_plans', 'auto_renew')) {
                $table->boolean('auto_renew')->default(false)->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach ([
                'invoice_id',
                'billing_period',
                'subtotal_amount',
                'discount_amount',
                'gst_amount',
                'final_amount',
                'payment_method',
                'auto_renew',
            ] as $column) {
                if (Schema::hasColumn('user_plans', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
