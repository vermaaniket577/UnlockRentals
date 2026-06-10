<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->integer('contacts_used')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('payment_reference')->nullable(); // for future payment gateway
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_plans');
    }
};
