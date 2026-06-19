<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\RestaurantSubscription;
use App\Models\SubscriptionPeriod;
use Illuminate\Support\Facades\Hash;

class TestRestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or update the test restaurant
        $restaurant = Restaurant::updateOrCreate(
            ['slug' => 'starter-restaurant'],
            [
                'name'                => 'Starter Restaurant',
                'email'               => 'starter@restaurant.com',
                'phone'               => '03001234567',
                'address'             => 'DHA Phase 6, Lahore, Pakistan',
                'status'              => 'active',
                'default_language'    => 'en',
                'supported_languages' => ['en', 'ur'],
                'ordering_enabled'    => true,
                'waiter_call_enabled' => true,
                'subscription_id'     => 2, // Starter Plan
                'subscription_expires_at' => now()->addDays(30),
            ]
        );

        // 2. Create the RestaurantSubscription record for Starter Plan Monthly Period
        $period = SubscriptionPeriod::where('subscription_id', 2)
            ->where('billing_cycle', 'monthly')
            ->first();

        $subRecord = RestaurantSubscription::updateOrCreate(
            [
                'restaurant_id'   => $restaurant->id,
                'subscription_id' => 2,
                'is_trial'        => false,
            ],
            [
                'subscription_period_id' => $period ? $period->id : null,
                'status'                 => 'active',
                'starts_at'              => now(),
                'expires_at'             => now()->addDays(30),
                'amount_paid'            => 999.00,
                'transaction_ref'        => 'SEEDER-STARTER-PLAN',
            ]
        );

        // Link the active subscription back to the restaurant
        $restaurant->update([
            'active_subscription_id' => $subRecord->id,
        ]);

        // 3. Create or update the test owner user
        $owner = User::updateOrCreate(
            ['email' => 'starter@restaurant.com'],
            [
                'name'          => 'Starter Restaurant Owner',
                'password'      => Hash::make('password'),
                'restaurant_id' => $restaurant->id,
            ]
        );

        // Assign Spatie role
        if (!$owner->hasRole('restaurant_owner')) {
            $owner->assignRole('restaurant_owner');
        }

        // 4. Seed 5 Categories & 30 Products
        $menuData = [
            'Karahi & Handi' => [
                ['name' => 'Chicken Karahi Half', 'price' => 1199, 'desc' => 'Traditional Lahore style stir-fried chicken in Karahi masala.', 'is_deal' => true],
                ['name' => 'Mutton Karahi Half', 'price' => 1799, 'desc' => 'Flesh mutton cooked in spices and tomato gravy.'],
                ['name' => 'Chicken White Handi', 'price' => 1399, 'desc' => 'Mild creamy white sauce chicken cooked in clay pot.'],
                ['name' => 'Paneer Reshmi Handi', 'price' => 999, 'desc' => 'Soft cottage cheese cubes in silky cream masala gravy.'],
                ['name' => 'Daal Makhni', 'price' => 599, 'desc' => 'Creamy slow-cooked black lentils with butter and cream.'],
                ['name' => 'Mix Vegetables', 'price' => 699, 'desc' => 'Seasoned seasonal vegetables cooked in Pakistani spices.'],
            ],
            'BBQ Specials' => [
                ['name' => 'Chicken Boti', 'price' => 799, 'desc' => 'Charcoal-grilled chicken cubes marinated in spicy yogurt.'],
                ['name' => 'Chicken Tikka Chest', 'price' => 449, 'desc' => 'Quarter chicken chest grilled over live coals.'],
                ['name' => 'Beef Seekh Kabab', 'price' => 899, 'desc' => 'Finely minced beef skewers with herbs and spices.'],
                ['name' => 'Malai Boti', 'price' => 899, 'desc' => 'Creamy melt-in-mouth boneless chicken skewers.'],
                ['name' => 'Fish Tikka', 'price' => 1299, 'desc' => 'Grilled seasonal fish chunks marinated in BBQ spices.'],
                ['name' => 'BBQ Platter Large', 'price' => 2499, 'desc' => 'An assortment of our best kababs, botis, and tikkas.'],
            ],
            'Rice & Biryani' => [
                ['name' => 'Special Chicken Biryani', 'price' => 499, 'desc' => 'Fragrant basmati rice layered with spiced chicken gravy.'],
                ['name' => 'Special Mutton Biryani', 'price' => 899, 'desc' => 'Traditional layered rice with tender mutton chunks.'],
                ['name' => 'Egg Fried Rice', 'price' => 699, 'desc' => 'Wok-tossed basmati rice with eggs and vegetables.'],
                ['name' => 'Chicken Manchurian with Rice', 'price' => 999, 'desc' => 'Sweet and sour chicken gravy served with egg fried rice.'],
                ['name' => 'Kabuli Pulao', 'price' => 1099, 'desc' => 'Traditional sweet-savory rice with mutton, carrots, and raisins.'],
                ['name' => 'Plain Steamed Rice', 'price' => 299, 'desc' => 'Fluffy boiled white basmati rice.'],
            ],
            'Tandoor & Breads' => [
                ['name' => 'Sada Roti', 'price' => 30, 'desc' => 'Fresh whole wheat flatbread baked in clay oven.'],
                ['name' => 'Roghni Naan', 'price' => 80, 'desc' => 'Soft yeast-leavened naan topped with sesame seeds and butter.'],
                ['name' => 'Garlic Naan', 'price' => 100, 'desc' => 'Leavened bread topped with fresh chopped garlic and butter.'],
                ['name' => 'Cheese Naan', 'price' => 250, 'desc' => 'Stuffed naan with melting mozzarella cheese inside.'],
                ['name' => 'Kalonji Naan', 'price' => 90, 'desc' => 'Flatbread sprinkled with nigella (black) seeds.'],
                ['name' => 'Aloo Naan', 'price' => 180, 'desc' => 'Stuffed naan with seasoned spiced mashed potatoes.'],
            ],
            'Beverages & Drinks' => [
                ['name' => 'Soft Drink Can', 'price' => 150, 'desc' => 'Chilled carbonated soft drink cans.'],
                ['name' => 'Mineral Water Large', 'price' => 120, 'desc' => 'Clean bottled drinking mineral water.'],
                ['name' => 'Fresh Lime Soda', 'price' => 220, 'desc' => 'Refreshing club soda with freshly squeezed lime juice.'],
                ['name' => 'Sweet Lassi', 'price' => 199, 'desc' => 'Traditional rich Pakistani yogurt drink served sweet.'],
                ['name' => 'Mint Margarita', 'price' => 299, 'desc' => 'Blend of fresh mint leaves, lime, soda, and crushed ice.'],
                ['name' => 'Karak Chai', 'price' => 120, 'desc' => 'Strong and aromatic milk tea brewed with cardamom.'],
            ],
        ];

        $this->createMenu($restaurant, $menuData);

        $this->command->info('✅ Test Restaurant "Starter Restaurant" with 5 categories & 30 products seeded successfully.');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   - Email: starter@restaurant.com');
        $this->command->info('   - Password: password');
    }

    private function createMenu(Restaurant $restaurant, array $menuData): void
    {
        $catOrder = 0;
        foreach ($menuData as $catName => $products) {
            $category = Category::updateOrCreate(
                [
                    'restaurant_id' => $restaurant->id,
                    'name'          => $catName
                ],
                [
                    'status'     => true,
                    'sort_order' => ++$catOrder
                ]
            );

            foreach ($products as $prodData) {
                Product::updateOrCreate(
                    [
                        'restaurant_id' => $restaurant->id,
                        'name'          => $prodData['name']
                    ],
                    [
                        'category_id'    => $category->id,
                        'description'    => $prodData['desc'],
                        'price'          => $prodData['price'],
                        'is_available'   => true,
                        'is_deal'        => $prodData['is_deal'] ?? false,
                    ]
                );
            }
        }
    }
}
