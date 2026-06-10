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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('locality')->nullable()->after('location');
            $table->enum('type', ['house', 'shop', 'pg-hostel', 'hotel'])->default('house')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('locality');
            $table->enum('type', ['house', 'shop'])->default('house')->change();
        });
    }
};
