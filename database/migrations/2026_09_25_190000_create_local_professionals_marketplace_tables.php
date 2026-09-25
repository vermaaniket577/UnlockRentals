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
        // 0. Update users role if on MySQL
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('tenant', 'owner', 'admin', 'professional') DEFAULT 'tenant'");
            } catch (\Throwable $e) {
                // If column cannot be altered or table doesn't have role enum, skip
            }
        }

        // 1. Professional Categories
        if (!Schema::hasTable('professional_categories')) {
            Schema::create('professional_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 120)->unique();
                $table->string('short_description', 255)->nullable();
                $table->text('description')->nullable();
                $table->string('icon', 100)->nullable();
                $table->string('image', 255)->nullable();
                $table->string('seo_title', 255)->nullable();
                $table->text('seo_description')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->index('status');
                $table->index('sort_order');
            });
        }

        // 2. Professional Specific Services
        if (!Schema::hasTable('professional_services')) {
            Schema::create('professional_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('professional_categories')->onDelete('cascade');
                $table->string('name', 150);
                $table->string('slug', 180)->unique();
                $table->text('description')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->index('category_id');
                $table->index('status');
                $table->index('sort_order');
            });
        }

        // 3. Professionals Directory Table
        if (!Schema::hasTable('professionals')) {
            Schema::create('professionals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('category_id')->constrained('professional_categories')->onDelete('restrict');
                $table->string('business_name', 190);
                $table->string('slug', 220)->unique();
                $table->string('full_name', 150);
                $table->string('profile_photo', 255)->nullable();
                $table->string('phone', 25);
                $table->string('whatsapp_number', 25)->nullable();
                $table->string('email', 150)->nullable();
                $table->text('description')->nullable();
                $table->unsignedSmallInteger('years_experience')->default(1);
                $table->decimal('starting_price', 10, 2)->nullable();
                $table->enum('price_type', ['hourly', 'per_visit', 'per_service', 'negotiable', 'contact'])->default('negotiable');
                
                // Preferences
                $table->boolean('home_visit')->default(true);
                $table->boolean('emergency_service')->default(false);
                $table->boolean('available_today')->default(true);

                // Base Location
                $table->text('address')->nullable();
                $table->string('state', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('locality', 150)->nullable();
                $table->string('pincode', 20)->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->unsignedSmallInteger('service_radius_km')->default(15);

                // Moderation & Verification
                $table->enum('status', ['pending', 'approved', 'rejected', 'suspended', 'blocked'])->default('pending');
                $table->enum('verification_status', ['unverified', 'verified'])->default('unverified');
                $table->text('rejection_reason')->nullable();
                
                // Aggregates & Analytics
                $table->decimal('average_rating', 3, 2)->default(0.00);
                $table->unsignedInteger('review_count')->default(0);
                $table->unsignedInteger('views_count')->default(0);
                $table->unsignedInteger('whatsapp_clicks')->default(0);
                $table->unsignedInteger('call_clicks')->default(0);
                $table->unsignedInteger('lead_count')->default(0);
                $table->boolean('featured')->default(false);

                $table->timestamp('approved_at')->nullable();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();

                // Composite & Single Indexes
                $table->index('category_id');
                $table->index('status');
                $table->index('verification_status');
                $table->index('city');
                $table->index('locality');
                $table->index('pincode');
                $table->index(['latitude', 'longitude']);
                $table->index('featured');
                $table->index(['status', 'city', 'category_id']);
                $table->index('average_rating');
            });
        }

        // 4. Pivot: Professional to Services (Explicit short foreign key names)
        if (!Schema::hasTable('professional_professional_service')) {
            Schema::create('professional_professional_service', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('professional_id');
                $table->unsignedBigInteger('professional_service_id');
                $table->timestamps();

                $table->foreign('professional_id', 'pps_prof_fk')
                    ->references('id')->on('professionals')
                    ->onDelete('cascade');

                $table->foreign('professional_service_id', 'pps_service_fk')
                    ->references('id')->on('professional_services')
                    ->onDelete('cascade');

                $table->unique(['professional_id', 'professional_service_id'], 'pps_prof_serv_uq');
            });
        }

        // 5. Professional Additional Service Locations / Localities
        if (!Schema::hasTable('professional_locations')) {
            Schema::create('professional_locations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->string('state', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('city', 100);
                $table->string('locality', 150)->nullable();
                $table->string('pincode', 20)->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->unsignedSmallInteger('service_radius_km')->default(15);
                $table->timestamps();

                $table->index('professional_id');
                $table->index('city');
                $table->index('locality');
                $table->index('pincode');
            });
        }

        // 6. Professional Gallery Photos
        if (!Schema::hasTable('professional_photos')) {
            Schema::create('professional_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->string('image', 255);
                $table->string('caption', 255)->nullable();
                $table->integer('sort_order')->default(0);
                $table->enum('status', ['active', 'hidden'])->default('active');
                $table->timestamps();

                $table->index('professional_id');
            });
        }

        // 7. Professional KYC & Documents (Private & Secure)
        if (!Schema::hasTable('professional_documents')) {
            Schema::create('professional_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->enum('document_type', ['id_proof', 'address_proof', 'certificate', 'business_registration', 'other']);
                $table->string('document_number', 100)->nullable();
                $table->string('file_path', 255);
                $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
                $table->timestamp('verified_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();

                $table->index('professional_id');
                $table->index('status');
            });
        }

        // 8. Customer Service Requests (Leads Source)
        if (!Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('category_id')->constrained('professional_categories')->onDelete('restrict');
                $table->foreignId('service_id')->nullable()->constrained('professional_services')->nullOnDelete();
                $table->string('name', 150);
                $table->string('phone', 25);
                $table->string('email', 150)->nullable();
                $table->text('description');
                $table->text('address')->nullable();
                $table->string('state', 100)->nullable();
                $table->string('district', 100)->nullable();
                $table->string('city', 100);
                $table->string('locality', 150)->nullable();
                $table->string('pincode', 20)->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->date('preferred_date')->nullable();
                $table->string('preferred_time', 50)->nullable();
                $table->string('budget', 50)->nullable();
                $table->enum('status', ['new', 'contacted', 'accepted', 'in_progress', 'completed', 'cancelled', 'closed'])->default('new');
                $table->timestamps();

                $table->index('category_id');
                $table->index('city');
                $table->index('status');
            });
        }

        // 9. Service Request Attachments
        if (!Schema::hasTable('service_request_attachments')) {
            Schema::create('service_request_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
                $table->string('file_path', 255);
                $table->timestamps();
            });
        }

        // 10. Professional Leads Dispatch System
        if (!Schema::hasTable('professional_leads')) {
            Schema::create('professional_leads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('lead_source', ['search', 'profile', 'call', 'whatsapp', 'request_service', 'homepage', 'seo_page'])->default('request_service');
                $table->enum('status', ['new', 'viewed', 'contacted', 'accepted', 'rejected', 'completed', 'cancelled'])->default('new');
                $table->text('professional_response')->nullable();
                $table->timestamp('contacted_at')->nullable();
                $table->timestamp('accepted_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->index('professional_id');
                $table->index('status');
                $table->index(['professional_id', 'status']);
            });
        }

        // 11. Professional Reviews System
        if (!Schema::hasTable('professional_reviews')) {
            Schema::create('professional_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
                $table->string('reviewer_name', 120);
                $table->string('reviewer_phone', 25)->nullable();
                $table->unsignedTinyInteger('rating');
                $table->string('title', 190)->nullable();
                $table->text('review');
                $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('approved');
                $table->text('admin_response')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('professional_id');
                $table->index('status');
                $table->index(['professional_id', 'status']);
            });
        }

        // 12. Customer Saved / Favorite Professionals
        if (!Schema::hasTable('professional_favorites')) {
            Schema::create('professional_favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'professional_id']);
            });
        }

        // 13. Reports & Fraud Detection
        if (!Schema::hasTable('professional_reports')) {
            Schema::create('professional_reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('reporter_name', 120)->nullable();
                $table->string('reporter_contact', 100)->nullable();
                $table->enum('reason', ['fake_profile', 'wrong_info', 'fraud', 'abusive_behaviour', 'spam', 'incorrect_contact', 'other'])->default('other');
                $table->text('description');
                $table->enum('status', ['pending', 'investigating', 'resolved', 'dismissed'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamps();

                $table->index('professional_id');
                $table->index('status');
            });
        }

        // 14. Availability Schedule
        if (!Schema::hasTable('professional_availability')) {
            Schema::create('professional_availability', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
                $table->time('start_time')->default('09:00:00');
                $table->time('end_time')->default('19:00:00');
                $table->boolean('is_available')->default(true);
                $table->timestamps();

                $table->unique(['professional_id', 'day_of_week']);
            });
        }

        // 15. Click & Engagement Analytics Logs
        if (!Schema::hasTable('professional_click_logs')) {
            Schema::create('professional_click_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('click_type', ['call', 'whatsapp', 'view']);
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['professional_id', 'click_type']);
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professional_click_logs');
        Schema::dropIfExists('professional_availability');
        Schema::dropIfExists('professional_reports');
        Schema::dropIfExists('professional_favorites');
        Schema::dropIfExists('professional_reviews');
        Schema::dropIfExists('professional_leads');
        Schema::dropIfExists('service_request_attachments');
        Schema::dropIfExists('service_requests');
        Schema::dropIfExists('professional_documents');
        Schema::dropIfExists('professional_photos');
        Schema::dropIfExists('professional_locations');
        Schema::dropIfExists('professional_professional_service');
        Schema::dropIfExists('professionals');
        Schema::dropIfExists('professional_services');
        Schema::dropIfExists('professional_categories');
    }
};
