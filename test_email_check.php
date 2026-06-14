<?php
// Quick script to check DB data for email testing
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== USERS ===" . PHP_EOL;
foreach (\App\Models\User::all() as $u) {
    $roles = $u->getRoleNames()->implode(', ');
    echo "  ID:{$u->id} | {$u->name} | {$u->email} | rest_id:{$u->restaurant_id} | roles: {$roles}" . PHP_EOL;
}

echo PHP_EOL . "=== RESTAURANTS ===" . PHP_EOL;
foreach (\App\Models\Restaurant::all() as $r) {
    echo "  ID:{$r->id} | {$r->name} | slug:{$r->slug} | status:{$r->status}" . PHP_EOL;
}

echo PHP_EOL . "=== SUBSCRIPTION PLANS ===" . PHP_EOL;
foreach (\App\Models\Subscription::all() as $s) {
    echo "  ID:{$s->id} | {$s->name} | price:{$s->price} | duration:{$s->duration}d" . PHP_EOL;
}

echo PHP_EOL . "=== ORDERS ===" . PHP_EOL;
echo "  Count: " . \App\Models\Order::count() . PHP_EOL;

echo PHP_EOL . "=== MAIL CONFIG ===" . PHP_EOL;
echo "  MAIL_MAILER: " . config('mail.default') . PHP_EOL;
echo "  MAIL_HOST: " . config('mail.mailers.smtp.host') . PHP_EOL;
echo "  MAIL_PORT: " . config('mail.mailers.smtp.port') . PHP_EOL;
echo "  MAIL_FROM: " . config('mail.from.address') . PHP_EOL;
echo "  QUEUE_CONNECTION: " . config('queue.default') . PHP_EOL;
