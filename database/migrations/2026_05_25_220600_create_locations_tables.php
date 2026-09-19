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
        if (!Schema::hasTable('states')) {
            Schema::create('states', function (Blueprint $table) {
                $table->id();
                $table->string('code', 5)->unique();
                $table->string('name', 100);
            });
        }

        if (!Schema::hasTable('districts')) {
            Schema::create('districts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
                $table->string('name', 100);
                $table->index('name');
            });
        }

        if (!Schema::hasTable('localities')) {
            Schema::create('localities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
                $table->string('name', 100);
                $table->index('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localities');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('states');
    }
};
