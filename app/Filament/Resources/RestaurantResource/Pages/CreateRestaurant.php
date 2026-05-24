<?php
namespace App\Filament\Resources\RestaurantResource\Pages;

use App\Filament\Resources\RestaurantResource;
use App\Mail\RestaurantInviteMail;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateRestaurant extends CreateRecord
{
    protected static string $resource = RestaurantResource::class;

    protected function afterCreate(): void
    {
        $data = $this->data; // raw form data (includes dehydrated:false fields)

        // 1. Generate invite token
        $token = Str::random(64);

        // 2. Create the owner user (no real password yet)
        $user = User::create([
            'name'          => $data['owner_name'],
            'email'         => $data['owner_email'],
            'password'      => bcrypt(Str::random(32)), // placeholder, never used
            'restaurant_id' => $this->record->id,
            'invite_token'  => $token,
        ]);

        // 3. Assign restaurant_owner role (Spatie)
        $user->assignRole('restaurant_owner');

        // 4. Send invite email
        Mail::to($user->email)->send(
            new RestaurantInviteMail($user, $this->record)
        );
    }
}