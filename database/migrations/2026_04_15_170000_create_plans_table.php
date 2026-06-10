<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g. "Basic", "Premium", "Gold"
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);     // in INR
            $table->integer('duration_days');     // how long the plan lasts
            $table->integer('contact_limit');     // how many owner contacts can be viewed
            $table->json('features')->nullable(); // extra features list
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
