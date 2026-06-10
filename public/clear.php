<?php
/**
 * Clear Laravel caches on cPanel live server.
 * Upload this file to your `public_html/` folder and visit `https://unlockrentals.com/clear.php`
 * 
 * MAKE SURE TO DELETE THIS FILE AFTER USE FOR SECURITY!
 */

define('LARAVEL_START', microtime(true));

// cPanel structure: vendor and bootstrap are inside `/laravel_project/` (sibling of `/public_html/`)
$autoloadPath = __DIR__ . '/../laravel_project/vendor/autoload.php';
$bootstrapPath = __DIR__ . '/../laravel_project/bootstrap/app.php';

// Fallback to local structure if not in cPanel
if (!file_exists($autoloadPath)) {
    $autoloadPath = __DIR__ . '/../vendor/autoload.php';
    $bootstrapPath = __DIR__ . '/../bootstrap/app.php';
}

if (!file_exists($autoloadPath)) {
    die("Error: Could not locate autoload.php at " . htmlspecialchars($autoloadPath));
}

require $autoloadPath;
$app = require_once $bootstrapPath;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h2>Laravel Cache Cleaner</h2>";

try {
    $rClear = $kernel->call('route:clear');
    echo "• Route cache cleared: " . ($rClear === 0 ? "SUCCESS" : "ERROR ($rClear)") . "<br>";
    
    $vClear = $kernel->call('view:clear');
    echo "• View cache cleared: " . ($vClear === 0 ? "SUCCESS" : "ERROR ($vClear)") . "<br>";
    
    $cfClear = $kernel->call('config:clear');
    echo "• Config cache cleared: " . ($cfClear === 0 ? "SUCCESS" : "ERROR ($cfClear)") . "<br>";
    
    $chClear = $kernel->call('cache:clear');
    echo "• Application cache cleared: " . ($chClear === 0 ? "SUCCESS" : "ERROR ($chClear)") . "<br>";
    
    echo "<br><strong style='color:green;'>All caches successfully cleared!</strong>";
} catch (\Throwable $e) {
    echo "<br><strong style='color:red;'>Failed to clear cache: " . htmlspecialchars($e->getMessage()) . "</strong>";
}
