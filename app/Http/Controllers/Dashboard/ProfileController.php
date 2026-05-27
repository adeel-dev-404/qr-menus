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

        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $openingHours = $restaurant->opening_hours ?? [];

        return view('dashboard.profile.index',
            compact('user', 'restaurant', 'days', 'openingHours'));
    }

    // ── Update personal info ──
    public function updatePersonal(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => ['required','email', Rule::unique('users')->ignore($user->id)],
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

        return back()->with('success', 'Personal info updated.');
    }

    // ── Update password ──
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    // ── Update restaurant info ──
    public function updateRestaurant(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string|max:500',
            'about'     => 'nullable|string|max:1000',
            'whatsapp'  => 'nullable|string|max:20',
            'instagram' => 'nullable|string|max:100',
            'facebook'  => 'nullable|string|max:100',
            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->only([
            'name','phone','email','address','about','whatsapp','instagram','facebook'
        ]);

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

        // Opening hours
        $hours = [];
        $days  = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        foreach ($days as $day) {
            $hours[$day] = [
                'open'   => $request->boolean("hours_{$day}_open"),
                'from'   => $request->input("hours_{$day}_from", '09:00'),
                'to'     => $request->input("hours_{$day}_to",   '22:00'),
            ];
        }
        $data['opening_hours'] = $hours;

        $restaurant->update($data);

        // Clear menu cache
        \Illuminate\Support\Facades\Cache::forget("restaurant:slug:{$restaurant->slug}");

        return back()->with('success', 'Restaurant profile updated.');
    }
}