<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Leads, Follow-ups, Communication Logs, and Consent.
     */
    public function up(): void
    {
        // 1. Leads Table
        if (!Schema::hasTable('leads')) {
            Schema::create('leads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->nullable()->constrained('visitors')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
                $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->string('lead_source', 60)->default('website')->index();
                $table->string('lead_status', 30)->default('new')->index(); // new, contacted, interested, follow_up, visit_scheduled, negotiation, converted, closed, lost, invalid
                $table->string('lead_stage', 30)->default('enquiry')->index(); // visitor, prospect, enquiry, qualified, visit, negotiation, converted
                $table->string('name', 100);
                $table->string('mobile', 25)->index();
                $table->string('email', 150)->nullable()->index();
                $table->string('preferred_city', 100)->nullable()->index();
                $table->string('preferred_locality', 150)->nullable();
                $table->string('property_type', 50)->nullable(); // 1bhk, 2bhk, 3bhk, villa, pg, flat, shop, etc.
                $table->enum('purpose', ['rent', 'buy', 'sell'])->default('rent')->index();
                $table->decimal('budget_min', 14, 2)->nullable();
                $table->decimal('budget_max', 14, 2)->nullable();
                $table->string('bedrooms', 20)->nullable();
                $table->string('furnished_status', 50)->nullable();
                $table->date('move_in_date')->nullable();
                $table->text('message')->nullable();
                $table->boolean('whatsapp_opt_in')->default(false)->index();
                $table->boolean('marketing_opt_in')->default(false);
                $table->text('consent_text')->nullable();
                $table->timestamp('consent_at')->nullable();
                $table->unsignedInteger('engagement_score')->default(20)->index();
                $table->timestamp('next_follow_up_at')->nullable()->index();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Lead Follow-Ups Table
        if (!Schema::hasTable('lead_follow_ups')) {
            Schema::create('lead_follow_ups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
                $table->dateTime('scheduled_at')->index();
                $table->string('status', 20)->default('pending')->index(); // pending, completed, skipped, rescheduled
                $table->text('note');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        // 3. Communication Logs Table
        if (!Schema::hasTable('communication_logs')) {
            Schema::create('communication_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('leads')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('type', 20)->default('outgoing'); // incoming, outgoing
                $table->string('channel', 20)->index(); // whatsapp, call, email, sms, internal_note
                $table->text('message');
                $table->string('status', 20)->default('sent')->index(); // queued, sent, delivered, read, failed
                $table->string('provider_message_id', 191)->nullable()->index();
                $table->json('metadata')->nullable();
                $table->timestamp('sent_at')->useCurrent();
                $table->timestamp('delivered_at')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Consent Records Table (Privacy compliance & auditability)
        if (!Schema::hasTable('consent_records')) {
            Schema::create('consent_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('visitor_id')->nullable()->constrained('visitors')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
                $table->string('consent_type', 50)->index(); // service_enquiry, whatsapp_updates, marketing_promotions, cookie_analytics
                $table->boolean('is_granted')->default(true);
                $table->text('consent_text');
                $table->string('form_source', 100);
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('granted_at')->useCurrent();
                $table->timestamp('withdrawn_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. CRM Audit Logs Table (Administrative action logging)
        if (!Schema::hasTable('crm_audit_logs')) {
            Schema::create('crm_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('action', 50)->index(); // lead_viewed, lead_edited, lead_assigned, lead_exported, consent_changed, whatsapp_sent, lead_anonymized
                $table->string('target_type', 50)->nullable();
                $table->unsignedBigInteger('target_id')->nullable();
                $table->json('details')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_audit_logs');
        Schema::dropIfExists('consent_records');
        Schema::dropIfExists('communication_logs');
        Schema::dropIfExists('lead_follow_ups');
        Schema::dropIfExists('leads');
    }
};
