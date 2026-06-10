<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_reference');
            $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_proof');
            $table->string('transaction_id')->nullable()->after('amount_paid');
        });
    }

    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'amount_paid', 'transaction_id']);
        });
    }
};
