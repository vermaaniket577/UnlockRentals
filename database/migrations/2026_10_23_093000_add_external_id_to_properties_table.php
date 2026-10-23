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
        if (Schema::hasTable('properties') && !Schema::hasColumn('properties', 'external_id')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('external_id', 150)->nullable()->index()->after('slug');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'external_id')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropIndex(['external_id']);
                $table->dropColumn('external_id');
            });
        }
    }
};
