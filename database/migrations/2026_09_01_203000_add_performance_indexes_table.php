<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add performance indexes.
     */
    public function up(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            // Index for fast primaryImage lookup per property
            $table->index(['property_id', 'is_primary'], 'idx_prop_images_prop_primary');
        });

        Schema::table('properties', function (Blueprint $table) {
            // Index for homepage and property listings sorted queries
            $table->index(['status', 'is_booked', 'created_at'], 'idx_properties_status_booked_created');
            $table->index(['status', 'is_featured', 'created_at'], 'idx_properties_status_featured_created');
            $table->index(['status', 'purpose'], 'idx_properties_status_purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropIndex('idx_prop_images_prop_primary');
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex('idx_properties_status_booked_created');
            $table->dropIndex('idx_properties_status_featured_created');
            $table->dropIndex('idx_properties_status_purpose');
        });
    }
};
