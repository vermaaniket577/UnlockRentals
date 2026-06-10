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
        Schema::table('private_user_offers', function (Blueprint $table) {
            $table->decimal('discounted_price', 8, 2)->nullable()->after('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('private_user_offers', function (Blueprint $table) {
            $table->dropColumn('discounted_price');
        });
    }
};
