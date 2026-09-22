<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\OdishaLocalitiesSeeder;

class SeedOdishaLocalitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:seed-odisha-localities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed all localities for all 30 districts of Odisha with clean slugs into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding all Odisha localities across 30 districts...');
        
        $seeder = new OdishaLocalitiesSeeder();
        $seeder->run();

        $this->info('Successfully seeded all Odisha localities!');
        return Command::SUCCESS;
    }
}
