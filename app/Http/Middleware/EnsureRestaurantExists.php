<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRestaurantExists
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        if (!$user->restaurant_id || !$user->restaurant) {
            abort(403, 'No restaurant assigned to your account.');
        }

        if ($user->restaurant->status !== 'active') {
            abort(403, 'Your restaurant account is inactive or pending approval.');
        }

        return $next($request);
    }
}