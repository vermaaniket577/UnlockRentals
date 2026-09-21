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
        if (Schema::hasTable('blogs') && !Schema::hasColumn('blogs', 'show_in_slider')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->boolean('show_in_slider')->default(false)->after('is_featured')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'show_in_slider')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->dropColumn('show_in_slider');
            });
        }
    }
};
