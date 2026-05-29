<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRestaurantExists
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // No restaurant assigned at all
        if (!$user->restaurant_id || !$user->restaurant) {
            return redirect()->route('pending')
                ->with('error', 'No restaurant assigned to your account.');
        }

        // Restaurant pending approval
        if ($user->restaurant->status === 'pending') {
            return redirect()->route('pending');
        }

        // Restaurant inactive/suspended
        if ($user->restaurant->status === 'inactive') {
            return redirect()->route('pending')
                ->with('error', 'Your restaurant account has been suspended. Contact support.');
        }

        return $next($request);
    }
}