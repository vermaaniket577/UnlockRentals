<?php
header('Content-Type: text/plain');

$key = $_GET['key'] ?? '';
if ($key !== 'UnlockRentalsSecurePull2026') {
    http_response_code(403);
    die("Forbidden: Invalid key\n");
}

chdir(__DIR__ . '/..');
echo "Current directory: " . getcwd() . "\n\n";

function runCmd($cmd) {
    echo "Executing: $cmd\n";
    $output = [];
    $ret = 0;
    exec($cmd . ' 2>&1', $output, $ret);
    echo "Exit Code: $ret\n";
    echo "Output:\n" . implode("\n", $output) . "\n";
    echo "----------------------------------------\n\n";
    return $ret;
}

runCmd('git fetch origin');
runCmd('git reset --hard origin/main');
runCmd('git pull origin main');

// Also widen column and clean database directly
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "Widening leads.message column to LONGTEXT...\n";
    \Illuminate\Support\Facades\DB::statement('ALTER TABLE leads MODIFY message LONGTEXT NULL');
    echo "Column successfully altered to LONGTEXT!\n";
    
    echo "Cleaning bloated messages in leads table...\n";
    \App\Models\Lead::cleanDuplicates();
    echo "Clean duplicates and messages finished successfully!\n";
} catch (\Throwable $e) {
    echo "Laravel execution notice: " . $e->getMessage() . "\n";
}

echo "=== Deployment Finished ===\n";
