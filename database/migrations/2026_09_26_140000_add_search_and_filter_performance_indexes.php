<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add search and filter performance indexes.
     */
    public function up(): void
    {
        try {
            Schema::table('properties', function (Blueprint $table) {
                $table->index(['status', 'type', 'created_at'], 'idx_properties_status_type_created');
                $table->index(['status', 'price'], 'idx_properties_status_price');
                $table->index(['status', 'bedrooms'], 'idx_properties_status_bedrooms');
                $table->index(['status', 'state'], 'idx_properties_status_state');
                $table->index(['status', 'location'], 'idx_properties_status_location');
            });
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropIndex('idx_properties_status_type_created');
                $table->dropIndex('idx_properties_status_price');
                $table->dropIndex('idx_properties_status_bedrooms');
                $table->dropIndex('idx_properties_status_state');
                $table->dropIndex('idx_properties_status_location');
            });
        } catch (\Throwable $e) {}
    }
};
