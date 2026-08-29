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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name')->nullable();
            $table->string('author_role')->nullable();
            $table->string('author_avatar')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->string('category')->default('General')->index();
            $table->json('tags')->nullable();
            $table->string('read_time')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
