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
        if (Schema::hasTable('properties') && !Schema::hasColumn('properties', 'alternate_phone')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('alternate_phone', 255)->nullable()->after('contact_phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties') && Schema::hasColumn('properties', 'alternate_phone')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropColumn('alternate_phone');
            });
        }
    }
};
