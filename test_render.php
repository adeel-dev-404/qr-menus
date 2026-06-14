<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $plans = \App\Models\Subscription::all();
    $html = view('welcome', compact('plans'))->render();
    echo "Landing page rendered successfully! Length: " . strlen($html) . PHP_EOL;
} catch (\Exception $e) {
    echo "Error rendering landing page: " . $e->getMessage() . PHP_EOL;
}
