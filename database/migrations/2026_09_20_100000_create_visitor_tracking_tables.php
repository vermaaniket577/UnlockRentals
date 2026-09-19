<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for visitor analytics and session tracking.
     */
    public function up(): void
    {
        // 1. Visitors Table
        if (!Schema::hasTable('visitors')) {
            Schema::create('visitors', function (Blueprint $table) {
                $table->id();
                $table->uuid('visitor_uuid')->unique();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('first_seen_at')->useCurrent()->index();
                $table->timestamp('last_seen_at')->useCurrent()->index();
                $table->string('first_landing_url', 500)->nullable();
                $table->string('last_url', 500)->nullable();
                $table->string('referrer', 500)->nullable();
                $table->string('utm_source')->nullable()->index();
                $table->string('utm_medium')->nullable()->index();
                $table->string('utm_campaign')->nullable()->index();
                $table->string('utm_term')->nullable();
                $table->string('utm_content')->nullable();
                $table->string('device_type', 20)->default('desktop')->index(); // mobile, tablet, desktop
                $table->string('browser', 50)->nullable();
                $table->string('operating_system', 50)->nullable();
                $table->string('country', 60)->nullable();
                $table->string('state', 80)->nullable()->index();
                $table->string('city', 80)->nullable()->index();
                $table->unsignedInteger('total_sessions')->default(1);
                $table->unsignedInteger('total_page_views')->default(1);
                $table->unsignedInteger('total_property_views')->default(0);
                $table->foreignId('first_property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('last_property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->unsignedInteger('engagement_score')->default(0)->index();
                $table->string('engagement_tier', 20)->default('low')->index(); // low, medium, high
                $table->boolean('has_converted_lead')->default(false)->index();
                $table->timestamps();
            });
        }

        // 2. Visitor Sessions Table
        if (!Schema::hasTable('visitor_sessions')) {
            Schema::create('visitor_sessions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
                $table->string('session_uuid', 64)->index();
                $table->string('landing_page', 500);
                $table->string('referrer', 500)->nullable();
                $table->timestamp('started_at')->useCurrent();
                $table->timestamp('ended_at')->nullable();
                $table->timestamp('last_activity_at')->useCurrent();
                $table->unsignedInteger('page_views')->default(1);
                $table->unsignedInteger('property_views')->default(0);
                $table->string('device_type', 20)->nullable();
                $table->string('browser', 50)->nullable();
                $table->string('operating_system', 50)->nullable();
                $table->string('utm_source')->nullable();
                $table->string('utm_medium')->nullable();
                $table->string('utm_campaign')->nullable();
                $table->timestamps();
            });
        }

        // 3. Visitor Events Table
        if (!Schema::hasTable('visitor_events')) {
            Schema::create('visitor_events', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
                $table->foreignId('session_id')->nullable()->constrained('visitor_sessions')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('event_name', 60)->index();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->unsignedInteger('city_id')->nullable();
                $table->unsignedInteger('locality_id')->nullable();
                $table->string('page_url', 500);
                $table->json('metadata')->nullable();
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }

        // 4. Visitor Daily Statistics (Aggregated for blazing fast dashboard analytics)
        if (!Schema::hasTable('visitor_daily_statistics')) {
            Schema::create('visitor_daily_statistics', function (Blueprint $table) {
                $table->id();
                $table->date('date')->index();
                $table->string('city', 80)->nullable()->index();
                $table->string('source', 50)->nullable()->index();
                $table->unsignedInteger('visitors_count')->default(0);
                $table->unsignedInteger('sessions_count')->default(0);
                $table->unsignedInteger('page_views_count')->default(0);
                $table->unsignedInteger('property_views_count')->default(0);
                $table->unsignedInteger('leads_count')->default(0);
                $table->unsignedInteger('whatsapp_clicks_count')->default(0);
                $table->unsignedInteger('enquiries_count')->default(0);
                $table->unsignedInteger('scheduled_visits_count')->default(0);
                $table->unsignedInteger('conversions_count')->default(0);
                $table->timestamps();

                $table->unique(['date', 'city', 'source'], 'v_daily_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_daily_statistics');
        Schema::dropIfExists('visitor_events');
        Schema::dropIfExists('visitor_sessions');
        Schema::dropIfExists('visitors');
    }
};
