<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\Subscription::all() as $s) {
    echo $s->name . ' Features: ' . json_encode($s->features) . PHP_EOL;
}
