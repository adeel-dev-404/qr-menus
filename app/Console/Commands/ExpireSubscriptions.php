<?php

namespace App\Console\Commands;

use App\Services\SubscriptionService;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature   = 'subscriptions:expire';
    protected $description = 'Mark expired restaurant subscriptions';

    public function handle(): void
    {
        $count = (new SubscriptionService())->markExpired();
        $this->info("Marked {$count} subscriptions as expired.");
    }
}