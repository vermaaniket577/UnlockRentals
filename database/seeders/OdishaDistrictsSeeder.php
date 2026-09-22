<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class OdishaDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure states table exists
        if (!Schema::hasTable('states')) {
            Schema::create('states', function ($table) {
                $table->id();
                $table->string('code', 5)->unique();
                $table->string('name', 100);
            });
        }

        // 2. Ensure districts table exists
        if (!Schema::hasTable('districts')) {
            Schema::create('districts', function ($table) {
                $table->id();
                $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
                $table->string('name', 100);
                $table->string('slug', 100)->nullable()->index();
                $table->index('name');
            });
        } elseif (!Schema::hasColumn('districts', 'slug')) {
            Schema::table('districts', function ($table) {
                $table->string('slug', 100)->nullable()->index()->after('name');
            });
        }

        // 3. Upsert State: Odisha
        $state = DB::table('states')->where('code', 'OR')->first();
        if (!$state) {
            $stateId = DB::table('states')->insertGetId([
                'code' => 'OR',
                'name' => 'Odisha',
            ]);
        } else {
            $stateId = $state->id;
        }

        // 4. Official 30 Districts of Odisha
        $districts = [
            ['id' => 1,  'name' => 'Angul',         'slug' => 'angul'],
            ['id' => 2,  'name' => 'Balangir',      'slug' => 'balangir'],
            ['id' => 3,  'name' => 'Balasore',       'slug' => 'balasore'],
            ['id' => 4,  'name' => 'Bargarh',        'slug' => 'bargarh'],
            ['id' => 5,  'name' => 'Bhadrak',        'slug' => 'bhadrak'],
            ['id' => 6,  'name' => 'Boudh',          'slug' => 'boudh'],
            ['id' => 7,  'name' => 'Cuttack',        'slug' => 'cuttack'],
            ['id' => 8,  'name' => 'Deogarh',        'slug' => 'deogarh'],
            ['id' => 9,  'name' => 'Dhenkanal',      'slug' => 'dhenkanal'],
            ['id' => 10, 'name' => 'Gajapati',       'slug' => 'gajapati'],
            ['id' => 11, 'name' => 'Ganjam',         'slug' => 'ganjam'],
            ['id' => 12, 'name' => 'Jagatsinghpur',  'slug' => 'jagatsinghpur'],
            ['id' => 13, 'name' => 'Jajpur',         'slug' => 'jajpur'],
            ['id' => 14, 'name' => 'Jharsuguda',     'slug' => 'jharsuguda'],
            ['id' => 15, 'name' => 'Kalahandi',      'slug' => 'kalahandi'],
            ['id' => 16, 'name' => 'Kandhamal',      'slug' => 'kandhamal'],
            ['id' => 17, 'name' => 'Kendrapara',      'slug' => 'kendrapara'],
            ['id' => 18, 'name' => 'Keonjhar',       'slug' => 'keonjhar'],
            ['id' => 19, 'name' => 'Khordha',        'slug' => 'khordha'],
            ['id' => 20, 'name' => 'Koraput',        'slug' => 'koraput'],
            ['id' => 21, 'name' => 'Malkangiri',     'slug' => 'malkangiri'],
            ['id' => 22, 'name' => 'Mayurbhanj',     'slug' => 'mayurbhanj'],
            ['id' => 23, 'name' => 'Nabarangpur',    'slug' => 'nabarangpur'],
            ['id' => 24, 'name' => 'Nayagarh',       'slug' => 'nayagarh'],
            ['id' => 25, 'name' => 'Nuapada',        'slug' => 'nuapada'],
            ['id' => 26, 'name' => 'Puri',           'slug' => 'puri'],
            ['id' => 27, 'name' => 'Rayagada',       'slug' => 'rayagada'],
            ['id' => 28, 'name' => 'Sambalpur',      'slug' => 'sambalpur'],
            ['id' => 29, 'name' => 'Subarnapur',     'slug' => 'subarnapur'],
            ['id' => 30, 'name' => 'Sundargarh',     'slug' => 'sundargarh'],
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
}
