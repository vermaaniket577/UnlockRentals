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
        // Table for storing subscriber devices / web push endpoints / FCM tokens
        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('endpoint');
                $table->string('endpoint_hash', 64)->unique()->index();
                $table->text('public_key')->nullable(); // p256dh key
                $table->text('auth_token')->nullable(); // auth secret
                $table->string('fcm_token')->nullable()->index(); // Mobile FCM token if available
                $table->string('device_type', 20)->default('web'); // 'web', 'android', 'ios'
                $table->text('user_agent')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('last_active_at')->nullable();
                $table->timestamps();
            });
        }

        // Table for tracking sent push notification campaigns
        if (!Schema::hasTable('push_notifications')) {
            Schema::create('push_notifications', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('body');
                $table->string('icon')->nullable();
                $table->string('image_url')->nullable();
                $table->string('action_url')->nullable();
                $table->string('target_type', 30)->default('all'); // 'all', 'role', 'specific_user', 'topic'
                $table->string('target_value')->nullable(); // role name or user_id or topic
                $table->unsignedInteger('sent_count')->default(0);
                $table->unsignedInteger('failed_count')->default(0);
                $table->string('status', 20)->default('sent'); // 'sent', 'failed', 'draft'
                $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notifications');
        Schema::dropIfExists('push_subscriptions');
    }
};
