<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Seed the subscription plans.
     * Safe to re-run: uses upsert so existing plans are updated, not duplicated.
     */
    public function run(): void
    {
        $plans = [
            // ── 1. Free ──────────────────────────────────────────────────
            [
                'id'       => 1,
                'name'     => 'Free',
                'price'    => '0.00',
                'duration' => 30,
                'features' => json_encode([
                    'branches'  => 1,
                    'products'  => 10,
                    'qr_codes'  => 1,
                ]),
            ],

            // ── 2. Starter ───────────────────────────────────────────────
            [
                'id'       => 2,
                'name'     => 'Starter',
                'price'    => '999.00',
                'duration' => 30,
                'features' => json_encode([
                    'branches'  => 1,
                    'products'  => 30,
                    'qr_codes'  => 3,
                ]),
            ],

            // ── 3. Basic ─────────────────────────────────────────────────
            [
                'id'       => 3,
                'name'     => 'Basic',
                'price'    => '1999.00',
                'duration' => 30,
                'features' => json_encode([
                    'branches'  => 2,
                    'products'  => 50,
                    'qr_codes'  => 5,
                ]),
            ],

            // ── 4. Pro ───────────────────────────────────────────────────
            [
                'id'       => 4,
                'name'     => 'Pro',
                'price'    => '4999.00',
                'duration' => 30,
                'features' => json_encode([
                    'branches'  => 10,
                    'products'  => 999,
                    'qr_codes'  => 20,
                ]),
            ],

            // ── 5. Enterprise ────────────────────────────────────────────
            [
                'id'       => 5,
                'name'     => 'Enterprise',
                'price'    => '9999.00',
                'duration' => 30,
                'features' => json_encode([
                    'branches'  => 999,   // unlimited
                    'products'  => 999,   // unlimited
                    'qr_codes'  => 999,   // unlimited
                ]),
            ],
        ];

        // Upsert: update price/features/duration if plan already exists,
        // otherwise insert. Never changes IDs or deletes existing rows.
        DB::table('subscriptions')->upsert(
            $plans,
            ['id'],                           // unique key to match on
            ['name', 'price', 'duration', 'features'] // columns to update
        );

        $this->command->info('✅  ' . count($plans) . ' subscription plans seeded.');
    }
}
