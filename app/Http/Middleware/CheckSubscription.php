<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Not logged in — let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Super admins bypass everything
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $restaurant = $user->restaurant;

        // No restaurant assigned — let EnsureRestaurantExists handle this
        if (!$restaurant) {
            return $next($request);
        }

        // Free plan (no subscription) — allow access
        if ($restaurant->isOnFreePlan()) {
            return $next($request);
        }

        // Has paid before but expired — redirect to subscription page
        // BUT don't redirect if they're already ON the subscription page (infinite loop prevention)
        if (!$restaurant->hasActiveSubscription()) {
            if ($request->routeIs('dashboard.subscription.*')) {
                return $next($request);
            }

            return redirect()->route('dashboard.subscription.index')
                ->with('error', 'Your subscription has expired. Please renew to continue.');
        }

        return $next($request);
    }
}