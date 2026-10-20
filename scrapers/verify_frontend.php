<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Total Approved Properties: " . \App\Models\Property::approved()->count() . " ===\n\n";

$latest = \App\Models\Property::approved()->with(['images', 'primaryImage'])->latest()->take(6)->get();

foreach ($latest as $p) {
    echo "ID: #" . $p->id . "\n";
    echo "Title: " . $p->title . "\n";
    echo "Price: " . $p->formatted_price . "\n";
    echo "Location: " . $p->locality . ", " . $p->location . "\n";
    echo "Phone: " . $p->contact_phone . "\n";
    echo "Primary Image: " . $p->primaryImageUrl() . "\n";
    echo "Status: " . $p->status . "\n";
    echo "--------------------------------------------------------\n";
}
