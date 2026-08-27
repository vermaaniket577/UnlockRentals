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
        if (!Schema::hasColumn('private_user_offers', 'billing_period')) {
            Schema::table('private_user_offers', function (Blueprint $table) {
                $table->string('billing_period')->default('monthly')->after('discounted_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('private_user_offers', 'billing_period')) {
            Schema::table('private_user_offers', function (Blueprint $table) {
                $table->dropColumn('billing_period');
            });
        }
    }
};
