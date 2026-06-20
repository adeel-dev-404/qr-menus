<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\WaiterCallOption;

class WaiterCallOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            ['label' => 'Call Waiter',   'icon' => '🙋', 'sort_order' => 1],
            ['label' => 'Request Water', 'icon' => '💧', 'sort_order' => 2],
            ['label' => 'Request Bill',  'icon' => '🧾', 'sort_order' => 3],
        ];

        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            foreach ($defaults as $option) {
                WaiterCallOption::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'label'         => $option['label'],
                    ],
                    [
                        'icon'       => $option['icon'],
                        'sort_order' => $option['sort_order'],
                        'is_active'  => true,
                    ]
                );
            }
        }
    }
}
