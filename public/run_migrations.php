<?php

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

header('Content-Type: text/html; charset=utf-8');

echo "<html><head><title>UnlockRentals Database Setup</title></head><body style='font-family: sans-serif; padding: 20px; line-height: 1.6;'>";
echo "<h2>UnlockRentals Database Migrations & Seeding</h2>";

try {
    echo "<strong>Step 1: Running migrations...</strong><br>";
    $migrateExitCode = Artisan::call('migrate', ['--force' => true]);
    echo "Exit Code: " . $migrateExitCode . "<br>";
    echo "Output:<br><pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>" . htmlspecialchars(Artisan::output()) . "</pre>";
    
    echo "<strong>Step 2: Running database seeder...</strong><br>";
    $seedExitCode = Artisan::call('db:seed', ['--force' => true]);
    echo "Exit Code: " . $seedExitCode . "<br>";
    echo "Output:<br><pre style='background: #f4f4f4; padding: 10px; border-radius: 5px;'>" . htmlspecialchars(Artisan::output()) . "</pre>";
    
    echo "<h3 style='color: green;'>✓ Database setup completed successfully!</h3>";
    echo "<p style='color: red;'><strong>Important:</strong> Please delete this file (<code>public/run_migrations.php</code>) from your production server immediately for security reasons.</p>";
} catch (\Exception $e) {
    echo "<h3 style='color: red;'>✗ Error occurred:</h3>";
    echo "<pre style='background: #fff0f0; border: 1px solid red; padding: 10px; color: red;'>" . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
