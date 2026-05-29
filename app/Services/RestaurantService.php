<?php

namespace App\Services;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RestaurantService
{
    /**
     * Create a new restaurant and assign an owner.
     */
    // public function createWithOwner(array $restaurantData, array $ownerData): Restaurant
    // {
    //     return DB::transaction(function () use ($restaurantData, $ownerData) {
    //         // Create restaurant
    //         $restaurant = Restaurant::create($restaurantData);

    //         // Create owner user
    //         $owner = User::create([
    //             'name'          => $ownerData['name'],
    //             'email'         => $ownerData['email'],
    //             'password'      => Hash::make($ownerData['password']),
    //             'restaurant_id' => $restaurant->id,
    //         ]);

    //         $owner->assignRole('restaurant_owner');

    //         return $restaurant;
    //     });
    // }

    public function createWithOwner(array $restaurantData, array $ownerData): array
    {
        return DB::transaction(function () use ($restaurantData, $ownerData) {

            $restaurant = Restaurant::create($restaurantData);

            $owner = User::create([
                'name'          => $ownerData['name'],
                'email'         => $ownerData['email'],
                'password'      => Hash::make($ownerData['password']),
                'restaurant_id' => $restaurant->id,
            ]);

            $owner->assignRole('restaurant_owner');

            return [
                'restaurant' => $restaurant,
                'owner'      => $owner,
            ];
        });
    }
    /**
     * Get current authenticated user's restaurant.
     */
    public function currentRestaurant(): ?Restaurant
    {
        return auth()->check()
            ? auth()->user()->restaurant
            : null;
    }

    /**
     * Check if user owns or belongs to a restaurant.
     */
    public function userBelongsTo(User $user, Restaurant $restaurant): bool
    {
        return $user->restaurant_id === $restaurant->id
            || $user->hasRole('super_admin');
    }
}
