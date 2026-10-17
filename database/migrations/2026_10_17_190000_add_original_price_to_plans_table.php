<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('plans') && !Schema::hasColumn('plans', 'original_price')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->decimal('original_price', 10, 2)->nullable()->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('plans') && Schema::hasColumn('plans', 'original_price')) {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn('original_price');
            });
        }
    }
};
