<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Providers\AppServiceProvider;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('districts') && Schema::hasTable('localities')) {
            // Find existing Gurugram / Gurgaon districts
            $districts = DB::table('districts')
                ->whereIn(DB::raw('LOWER(name)'), ['gurugram', 'gurgaon'])
                ->get();

            // If none found but Haryana state exists, link or create Gurugram district
            if ($districts->isEmpty() && Schema::hasTable('states')) {
                $hrState = DB::table('states')->where('code', 'HR')->orWhere('name', 'Haryana')->first();
                if ($hrState) {
                    $hasSlug = Schema::hasColumn('districts', 'slug');
                    $distData = [
                        'state_id' => $hrState->id,
                        'name' => 'Gurugram',
                    ];
                    if ($hasSlug) {
                        $distData['slug'] = 'gurugram';
                    }
                    $distId = DB::table('districts')->insertGetId($distData);
                    $districts = DB::table('districts')->where('id', $distId)->get();
                }
            }

            // Ensure Sector 13 is added to all matching Gurugram/Gurgaon districts
            foreach ($districts as $district) {
                $exists = DB::table('localities')
                    ->where('district_id', $district->id)
                    ->whereRaw('LOWER(name) = ?', ['sector 13'])
                    ->exists();

                if (!$exists) {
                    DB::table('localities')->insert([
                        'district_id' => $district->id,
                        'name' => 'Sector 13',
                    ]);
                }
            }

            AppServiceProvider::clearLocationCache();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('districts') && Schema::hasTable('localities')) {
            $districtIds = DB::table('districts')
                ->whereIn(DB::raw('LOWER(name)'), ['gurugram', 'gurgaon'])
                ->pluck('id');

            if ($districtIds->isNotEmpty()) {
                DB::table('localities')
                    ->whereIn('district_id', $districtIds)
                    ->whereRaw('LOWER(name) = ?', ['sector 13'])
                    ->delete();
            }

            AppServiceProvider::clearLocationCache();
        }
    }
};
