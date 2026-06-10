<?php
// Diagnostics script to inspect routes and databases on live server.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(\Illuminate\Http\Request::create('/plans', 'GET'));

echo "<h1>Live Server Route Diagnostics</h1>";

$routes = \Illuminate\Support\Facades\Route::getRoutes();
echo "<p>Total routes registered: " . count($routes) . "</p>";
echo "<pre>";

foreach ($routes as $route) {
    $uri = (string) $route->uri();
    $name = (string) $route->getName();
    $methods = implode('|', $route->methods());
    
    if (str_contains($uri, 'plans') || str_contains($uri, 'razorpay') || str_contains($name, 'plans')) {
        echo "Method: [{$methods}] | URI: /{$uri} | Name: " . ($name ? $name : 'N/A') . "\n";
    }
}
echo "</pre>";
