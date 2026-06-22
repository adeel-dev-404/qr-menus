<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = auth()->user();

        // If not logged in, let authentication middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Super admins bypass all module restriction checks
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        $restaurant = $user->restaurant;
        if (!$restaurant) {
            return $next($request);
        }

        $mapping = [
            'orders'       => 'ordering_allowed',
            'waiter-calls' => 'waiter_call_allowed',
            'deals'        => 'deals_allowed',
            'categories'   => 'categories_allowed',
            'products'     => 'products_allowed',
            'qr-codes'     => 'qr_codes_allowed',
            'branches'     => 'branches_allowed',
            'staff'        => 'staff_allowed',
        ];

        $field = $mapping[$module] ?? null;

        if ($field && !$restaurant->$field) {
            abort(403, 'This module has been disabled by the system administrator.');
        }

        return $next($request);
    }
}
