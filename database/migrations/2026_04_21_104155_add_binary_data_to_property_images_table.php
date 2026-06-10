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
        Schema::table('property_images', function (Blueprint $table) {
            $table->binary('image_data')->nullable();
            $table->string('path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            if (Schema::hasColumn('property_images', 'image_data')) {
                $table->dropColumn('image_data');
            }
            $table->string('path')->nullable(false)->change();
        });
    }
};
