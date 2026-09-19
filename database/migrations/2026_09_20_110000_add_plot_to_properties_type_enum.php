<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('type', ['house', 'shop', 'pg-hostel', 'hotel', 'plot'])->default('house')->change();
        });

        // Ensure "Plots & Land" category exists
        if (Schema::hasTable('categories')) {
            $cat = Category::where('name', 'Plots & Land')->orWhere('slug', 'plots-and-land')->first();
            if (!$cat) {
                Category::create([
                    'name' => 'Plots & Land',
                    'slug' => 'plots-and-land',
                    'icon' => 'map-trifold',
                    'description' => 'Commercial, residential, and agricultural plots and land for sale.',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->enum('type', ['house', 'shop', 'pg-hostel', 'hotel'])->default('house')->change();
        });
    }
};
