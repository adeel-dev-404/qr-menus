<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Seed the subscription plans and their billing periods.
     * Safe to re-run.
     */
    public function run(): void
    {
        $plans = [
            [
                'id'         => 1,
                'name'       => 'Free',
                'price'      => '0.00',
                'duration'   => 30,
                'trial_days' => 0,
                'is_active'  => true,
                'sort_order' => 1,
                'features'   => [
                    'branches' => 1,
                    'products' => 10,
                    'qr_codes' => 1,
                ],
                'periods'    => []
            ],
            [
                'id'         => 2,
                'name'       => 'Starter',
                'price'      => '999.00',
                'duration'   => 30,
                'trial_days' => 7,
                'is_active'  => true,
                'sort_order' => 2,
                'features'   => [
                    'branches' => 1,
                    'products' => 30,
                    'qr_codes' => 3,
                ],
                'periods'    => [
                    [
                        'billing_cycle'    => 'monthly',
                        'duration_days'    => 30,
                        'price'            => 999.00,
                        'discount_percent' => null,
                        'sort_order'       => 1,
                    ],
                    [
                        'billing_cycle'    => 'quarterly',
                        'duration_days'    => 90,
                        'price'            => 2847.15,
                        'discount_percent' => 5,
                        'sort_order'       => 2,
                    ],
                    [
                        'billing_cycle'    => 'half_yearly',
                        'duration_days'    => 180,
                        'price'            => 5094.90,
                        'discount_percent' => 15,
                        'sort_order'       => 3,
                    ],
                    [
                        'billing_cycle'    => 'yearly',
                        'duration_days'    => 365,
                        'price'            => 8991.00,
                        'discount_percent' => 25,
                        'sort_order'       => 4,
                    ],
                ]
            ],
            [
                'id'         => 3,
                'name'       => 'Basic',
                'price'      => '1999.00',
                'duration'   => 30,
                'trial_days' => 7,
                'is_active'  => true,
                'sort_order' => 3,
                'features'   => [
                    'branches' => 2,
                    'products' => 50,
                    'qr_codes' => 5,
                ],
                'periods'    => [
                    [
                        'billing_cycle'    => 'monthly',
                        'duration_days'    => 30,
                        'price'            => 1999.00,
                        'discount_percent' => null,
                        'sort_order'       => 0,
                    ],
                    [
                        'billing_cycle'    => 'quarterly',
                        'duration_days'    => 90,
                        'price'            => 5697.15,
                        'discount_percent' => 5,
                        'sort_order'       => 0,
                    ],
                    [
                        'billing_cycle'    => 'half_yearly',
                        'duration_days'    => 180,
                        'price'            => 10194.90,
                        'discount_percent' => 15,
                        'sort_order'       => 0,
                    ],
                    [
                        'billing_cycle'    => 'yearly',
                        'duration_days'    => 365,
                        'price'            => 17991.00,
                        'discount_percent' => 25,
                        'sort_order'       => 0,
                    ],
                ]
            ],
            [
                'id'         => 4,
                'name'       => 'Pro',
                'price'      => '4999.00',
                'duration'   => 30,
                'trial_days' => 7,
                'is_active'  => true,
                'sort_order' => 4,
                'features'   => [
                    'branches' => 10,
                    'products' => 199,
                    'qr_codes' => 20,
                ],
                'periods'    => [
                    [
                        'billing_cycle'    => 'monthly',
                        'duration_days'    => 30,
                        'price'            => 3499.00,
                        'discount_percent' => null,
                        'sort_order'       => 0,
                    ],
                    [
                        'billing_cycle'    => 'quarterly',
                        'duration_days'    => 90,
                        'price'            => 9447.30,
                        'discount_percent' => 10,
                        'sort_order'       => 0,
                    ],
                    [
                        'billing_cycle'    => 'half_yearly',
                        'duration_days'    => 180,
                        'price'            => 17844.90,
                        'discount_percent' => 15,
                        'sort_order'       => 0,
                    ],
                    [
                        'billing_cycle'    => 'yearly',
                        'duration_days'    => 365,
                        'price'            => 33590.40,
                        'discount_percent' => 20,
                        'sort_order'       => 0,
                    ],
                ]
            ],
            [
                'id'         => 5,
                'name'       => 'Enterprise',
                'price'      => '9999.00',
                'duration'   => 30,
                'trial_days' => 0,
                'is_active'  => false,
                'sort_order' => 5,
                'features'   => [
                    'branches' => 999,
                    'products' => 999,
                    'qr_codes' => 999,
                ],
                'periods'    => []
            ],
        ];

        foreach ($plans as $planData) {
            $periodsData = $planData['periods'];
            unset($planData['periods']);

            // Update or create the plan
            $plan = \App\Models\Subscription::updateOrCreate(
                ['id' => $planData['id']],
                [
                    'name'       => $planData['name'],
                    'price'      => $planData['price'],
                    'duration'   => $planData['duration'],
                    'trial_days' => $planData['trial_days'],
                    'is_active'  => $planData['is_active'],
                    'sort_order' => $planData['sort_order'],
                    'features'   => $planData['features'],
                ]
            );

            // Seed periods for this plan
            foreach ($periodsData as $pData) {
                \App\Models\SubscriptionPeriod::updateOrCreate(
                    [
                        'subscription_id' => $plan->id,
                        'billing_cycle'   => $pData['billing_cycle']
                    ],
                    $pData
                );
            }
        }

        $this->command->info('✅ ' . count($plans) . ' subscription plans & their periods seeded successfully.');
    }
}
