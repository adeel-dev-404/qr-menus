<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user       = auth()->user();
        $restaurant = $user->restaurant;

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $openingHours = $restaurant->opening_hours ?? [];

        return view(
            'dashboard.profile.index',
            compact('user', 'restaurant', 'days', 'openingHours')
        );
    }

    // ── Update personal info ──
    public function updatePersonal(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            // Delete old
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Personal info updated.')->with('section', 'personal');
    }

    // ── Update password ──
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.')->with('section', 'password');
    }

    // ── Update restaurant info ──
    public function updateRestaurant(Request $request)
    {
        $restaurant = auth()->user()->restaurant;
        $section = $request->input('section', 'restaurant');

        if ($section === 'hours') {
            // Opening hours
            $hours = [];
            $days  = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($days as $day) {
                $hours[$day] = [
                    'open'   => $request->boolean("hours_{$day}_open"),
                    'from'   => $request->input("hours_{$day}_from", '09:00'),
                    'to'     => $request->input("hours_{$day}_to",   '22:00'),
                ];
            }

            $restaurant->update([
                'opening_hours' => $hours,
            ]);

            // Clear menu cache
            \Illuminate\Support\Facades\Cache::forget("restaurant:slug:{$restaurant->slug}");

            return back()->with('success', 'Opening hours updated.')->with('section', 'hours');
        }

        if ($section === 'social') {
            // Social links
            $request->validate([
                'whatsapp'  => 'nullable|string|max:20',
                'instagram' => 'nullable|string|max:100',
                'facebook'  => 'nullable|string|max:100',
            ]);

            $restaurant->update($request->only([
                'whatsapp',
                'instagram',
                'facebook',
            ]));

            // Clear menu cache
            \Illuminate\Support\Facades\Cache::forget("restaurant:slug:{$restaurant->slug}");

            return back()->with('success', 'Social links updated.')->with('section', 'social');
        }

        if ($section === 'wifi') {
            // Wi-Fi Configuration
            $request->validate([
                'wifi_ssid'     => 'nullable|string|max:100',
                'wifi_password' => 'nullable|string|max:100',
            ]);

            $restaurant->update($request->only([
                'wifi_ssid',
                'wifi_password',
            ]));

            // Clear menu cache
            \Illuminate\Support\Facades\Cache::forget("restaurant:slug:{$restaurant->slug}");

            return back()->with('success', 'Wi-Fi settings updated.')->with('section', 'wifi');
        }

        // Default / Restaurant Profile section
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:500',
            'about'     => 'nullable|string|max:1000',

            'ordering_enabled'    => 'nullable',
            'deals_enabled'       => 'nullable',
            'waiter_call_enabled' => 'nullable',
            'jazzcash_number'  => 'nullable|string|max:20',
            'easypaisa_number' => 'nullable|string|max:20',
            'whatsapp_number'  => 'nullable|string|max:20',

            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->only([
            'name',
            'phone',
            'email',
            'address',
            'about',
            'jazzcash_number',
            'easypaisa_number',
            'whatsapp_number',
        ]);

        $data['ordering_enabled']    = $restaurant->ordering_allowed ? $request->boolean('ordering_enabled') : false;
        $data['deals_enabled']       = $restaurant->deals_allowed ? $request->boolean('deals_enabled') : false;
        $data['menu_layout']         = $request->input('menu_layout', 'list');
        $data['waiter_call_enabled'] = $restaurant->waiter_call_allowed ? $request->boolean('waiter_call_enabled') : false;

        // Logo upload
        if ($request->hasFile('logo')) {
            if ($restaurant->logo) Storage::disk('public')->delete($restaurant->logo);
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Cover image upload
        if ($request->hasFile('cover_image')) {
            if ($restaurant->cover_image) Storage::disk('public')->delete($restaurant->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $restaurant->update($data);

        // Clear menu cache
        \Illuminate\Support\Facades\Cache::forget("restaurant:slug:{$restaurant->slug}");

        return back()->with('success', 'Restaurant profile updated.')->with('section', 'restaurant');
    }

    // ── Update language settings ──
    public function updateLanguages(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        $request->validate([
            'supported_languages'   => 'required|array|min:1',
            'supported_languages.*' => 'string|in:' . implode(',', array_keys(\App\Models\Restaurant::AVAILABLE_LANGUAGES)),
            'default_language'      => 'required|string|in:' . implode(',', array_keys(\App\Models\Restaurant::AVAILABLE_LANGUAGES)),
        ]);

        $langs = $request->input('supported_languages', ['en']);

        // Ensure 'en' is always included
        if (!in_array('en', $langs)) {
            array_unshift($langs, 'en');
        }

        // Ensure default language is in the supported list
        $defaultLang = $request->input('default_language', 'en');
        if (!in_array($defaultLang, $langs)) {
            $defaultLang = 'en';
        }

        $restaurant->update([
            'supported_languages' => array_values(array_unique($langs)),
            'default_language'    => $defaultLang,
        ]);

        return back()->with('success', 'Language settings updated.')->with('section', 'languages');
    }
}
