<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driver = DB::getDriverName();
        // Disable foreign keys check to truncate
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('localities')->truncate();
        DB::table('districts')->truncate();
        DB::table('states')->truncate();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $dir = database_path('data/locations');
        $files = ['south.php', 'west.php', 'north.php', 'central_east.php', 'northeast_islands.php'];

        $dataset = [];
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (file_exists($path)) {
                $data = require $path;
                foreach ($data as $code => $info) {
                    $dataset[$code] = $info;
                }
            }
        }

        $statesData = [];
        $districtsData = [];
        $localitiesData = [];

        $stateId = 1;
        $districtId = 1;
        $localityId = 1;

        foreach ($dataset as $code => $stateInfo) {
            $statesData[] = [
                'id' => $stateId,
                'code' => $code,
                'name' => $stateInfo['name']
            ];

            if (isset($stateInfo['districts'])) {
                foreach ($stateInfo['districts'] as $distName => $locs) {
                    $districtsData[] = [
                        'id' => $districtId,
                        'state_id' => $stateId,
                        'name' => $distName
                    ];

                    $seen = [];
                    foreach ($locs as $locName) {
                        $locTrimmed = trim($locName);
                        if ($locTrimmed === '' || isset($seen[strtolower($locTrimmed)])) {
                            continue;
                        }
                        $seen[strtolower($locTrimmed)] = true;

                        $localitiesData[] = [
                            'id' => $localityId,
                            'district_id' => $districtId,
                            'name' => $locTrimmed
                        ];
                        $localityId++;
                    }
                    $districtId++;
                }
            }
            $stateId++;
        }

        // Insert in bulk chunks
        foreach (array_chunk($statesData, 100) as $chunk) {
            DB::table('states')->insert($chunk);
        }
        foreach (array_chunk($districtsData, 100) as $chunk) {
            DB::table('districts')->insert($chunk);
        }
        foreach (array_chunk($localitiesData, 200) as $chunk) {
            DB::table('localities')->insert($chunk);
        }

        Cache::forget('indian_location_data');
    }
}
