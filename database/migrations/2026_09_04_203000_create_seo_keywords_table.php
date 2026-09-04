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
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 255);
            $table->string('search_intent', 50)->nullable();
            $table->string('category', 100)->nullable()->index();
            $table->string('priority', 20)->default('Medium')->index();
            $table->string('city', 100)->nullable()->index();
            $table->string('locality', 150)->nullable()->index();
            $table->string('recommended_page', 100)->nullable()->index();
            $table->string('seo_title', 255);
            $table->text('meta_description');
            $table->string('url_slug', 255)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_keywords');
    }
};
