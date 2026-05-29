<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\RestaurantService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    public function create(): View
    {
        return view('auth.register');
    }

    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name'              => ['required', 'string', 'max:255'],
    //         'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'password'          => ['required', 'confirmed', Rules\Password::defaults()],
    //         'restaurant_name'   => ['required', 'string', 'max:255'],
    //         'restaurant_phone'  => ['nullable', 'string', 'max:20'],
    //         'restaurant_address' => ['nullable', 'string', 'max:500'],
    //     ]);

    //     // Create restaurant with pending status
    //     $service    = new RestaurantService();
    //     $restaurant = $service->createWithOwner(
    //         [
    //             'name'    => $request->restaurant_name,
    //             'phone'   => $request->restaurant_phone,
    //             'address' => $request->restaurant_address,
    //             'status'  => 'pending',   // ← pending until admin approves
    //         ],
    //         [
    //             'name'     => $request->name,
    //             'email'    => $request->email,
    //             'password' => $request->password,
    //         ]
    //     );

    //     // Log the user in
    //     $user = User::query()->where('email', $request->email)->first();

    //     if (!$user) {
    //         return back()->withErrors([
    //             'email' => 'User creation failed.',
    //         ]);
    //     }

    //     Auth::guard('web')->login($user, true);

    //     $request->session()->regenerate();

    //     event(new Registered($user));

    //     return redirect()->route('pending');
    // }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_phone' => ['nullable', 'string', 'max:20'],
            'restaurant_address' => ['nullable', 'string', 'max:500'],
        ]);

        $service = new RestaurantService();

        $result = $service->createWithOwner(
            [
                'name' => $request->restaurant_name,
                'phone' => $request->restaurant_phone,
                'address' => $request->restaurant_address,
                'status' => 'pending',
            ],
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]
        );

        $user = $result['owner'];

        Auth::login($user, true);
        $request->session()->regenerate();

        event(new Registered($user));

        return redirect()->route('pending');
    }
}
