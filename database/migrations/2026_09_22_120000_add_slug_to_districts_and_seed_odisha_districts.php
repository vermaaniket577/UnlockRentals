<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure states table exists
        if (!Schema::hasTable('states')) {
            Schema::create('states', function (Blueprint $table) {
                $table->id();
                $table->string('code', 5)->unique();
                $table->string('name', 100);
            });
        }

        // 2. Ensure districts table exists
        if (!Schema::hasTable('districts')) {
            Schema::create('districts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
                $table->string('name', 100);
                $table->string('slug', 100)->nullable()->index();
                $table->index('name');
            });
        } elseif (!Schema::hasColumn('districts', 'slug')) {
            Schema::table('districts', function (Blueprint $table) {
                $table->string('slug', 100)->nullable()->index()->after('name');
            });
        }

        // 3. Ensure localities table exists
        if (!Schema::hasTable('localities')) {
            Schema::create('localities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
                $table->string('name', 100);
                $table->index('name');
            });
        }

        // 4. Upsert Odisha State
        $state = DB::table('states')->where('code', 'OR')->first();
        if (!$state) {
            $stateId = DB::table('states')->insertGetId([
                'code' => 'OR',
                'name' => 'Odisha',
            ]);
        } else {
            $stateId = $state->id;
        }

        // 5. Official 30 Districts of Odisha
        $districts = [
            ['name' => 'Angul', 'slug' => 'angul'],
            ['name' => 'Balangir', 'slug' => 'balangir'],
            ['name' => 'Balasore', 'slug' => 'balasore'],
            ['name' => 'Bargarh', 'slug' => 'bargarh'],
            ['name' => 'Bhadrak', 'slug' => 'bhadrak'],
            ['name' => 'Boudh', 'slug' => 'boudh'],
            ['name' => 'Cuttack', 'slug' => 'cuttack'],
            ['name' => 'Deogarh', 'slug' => 'deogarh'],
            ['name' => 'Dhenkanal', 'slug' => 'dhenkanal'],
            ['name' => 'Gajapati', 'slug' => 'gajapati'],
            ['name' => 'Ganjam', 'slug' => 'ganjam'],
            ['name' => 'Jagatsinghpur', 'slug' => 'jagatsinghpur'],
            ['name' => 'Jajpur', 'slug' => 'jajpur'],
            ['name' => 'Jharsuguda', 'slug' => 'jharsuguda'],
            ['name' => 'Kalahandi', 'slug' => 'kalahandi'],
            ['name' => 'Kandhamal', 'slug' => 'kandhamal'],
            ['name' => 'Kendrapara', 'slug' => 'kendrapara'],
            ['name' => 'Keonjhar', 'slug' => 'keonjhar'],
            ['name' => 'Khordha', 'slug' => 'khordha'],
            ['name' => 'Koraput', 'slug' => 'koraput'],
            ['name' => 'Malkangiri', 'slug' => 'malkangiri'],
            ['name' => 'Mayurbhanj', 'slug' => 'mayurbhanj'],
            ['name' => 'Nabarangpur', 'slug' => 'nabarangpur'],
            ['name' => 'Nayagarh', 'slug' => 'nayagarh'],
            ['name' => 'Nuapada', 'slug' => 'nuapada'],
            ['name' => 'Puri', 'slug' => 'puri'],
            ['name' => 'Rayagada', 'slug' => 'rayagada'],
            ['name' => 'Sambalpur', 'slug' => 'sambalpur'],
            ['name' => 'Subarnapur', 'slug' => 'subarnapur'],
            ['name' => 'Sundargarh', 'slug' => 'sundargarh'],
        ];

        foreach ($districts as $d) {
            $existing = DB::table('districts')
                ->where('state_id', $stateId)
                ->where(function ($q) use ($d) {
                    $q->where('name', $d['name'])
                      ->orWhere('slug', $d['slug']);
                })
                ->first();

            if ($existing) {
                DB::table('districts')->where('id', $existing->id)->update([
                    'name' => $d['name'],
                    'slug' => $d['slug'],
                ]);
            } else {
                DB::table('districts')->insert([
                    'state_id' => $stateId,
                    'name'     => $d['name'],
                    'slug'     => $d['slug'],
                ]);
            }
        }

        Cache::forget('indian_location_data');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $state = DB::table('states')->where('code', 'OR')->first();
        if ($state) {
            DB::table('districts')->where('state_id', $state->id)->delete();
        }
        Cache::forget('indian_location_data');
    }
};
