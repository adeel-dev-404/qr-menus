<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MenuController extends Controller
{
    // Main menu page: /r/{restaurant}
    public function show(Request $request, Restaurant $restaurant)
    {
        // Only show active restaurants
        abort_if($restaurant->status !== 'active', 404);

        // Language handling
        $supportedLangs = $restaurant->supported_languages ?? ['en'];
        $defaultLang = $restaurant->default_language ?? 'en';
        $lang = $request->get('lang');
        if (!$lang || !in_array($lang, $supportedLangs)) {
            $lang = $defaultLang;
        }
        app()->setLocale($lang);

        $categories = $restaurant->categories()
            ->withoutGlobalScopes()
            ->with('translations')
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $deals = collect();
        if ($restaurant->isDealsEnabled()) {
            $deals = \App\Models\Deal::where('restaurant_id', $restaurant->id)
                ->where('is_available', true)
                ->with(['items.product', 'items.variant'])
                ->get();
        }

        // Active category filter
        $activeCategorySlug = $request->get('category');
        $hasDeals = $deals->isNotEmpty();

        // If deals exist and no category is specified, default to deals
        if (!$activeCategorySlug && $hasDeals) {
            $activeCategorySlug = 'deals';
        }

        $activeCategory = $activeCategorySlug && $activeCategorySlug !== 'deals'
            ? $categories->firstWhere('slug', $activeCategorySlug)
            : ($activeCategorySlug === 'deals' ? null : $categories->first());

        $products = collect();
        if ($activeCategory) {
            $products = Product::withoutGlobalScopes()
                ->with(['variants.translations', 'translations'])
                ->where('restaurant_id', $restaurant->id)
                ->where('category_id', $activeCategory->id)
                ->where('is_available', true)
                ->get();
        }

        // Decrypt QR context (table/branch/type) from encrypted ctx parameter
        $qrContext  = null;
        $qrTampered = false;

        if ($request->has('ctx')) {
            try {
                $decrypted = Crypt::decryptString($request->get('ctx'));
                $parsed    = json_decode($decrypted, true);

                // Basic validation: must be an array with a valid type
                if (is_array($parsed) && isset($parsed['type']) && in_array($parsed['type'], ['table', 'branch', 'restaurant'])) {
                    $qrContext = $parsed;
                } else {
                    $qrTampered = true;
                }
            } catch (\Exception $e) {
                $qrTampered = true;
            }
        }

        return view('menu.show', compact('restaurant', 'categories', 'products', 'deals', 'activeCategory', 'qrContext', 'qrTampered', 'lang'));
    }

    // Single category page: /r/{restaurant}/category/{category}
    public function category(Request $request, Restaurant $restaurant, Category $category)
    {
        abort_if($restaurant->status !== 'active', 404);
        abort_if($category->restaurant_id !== $restaurant->id, 404);

        // Language handling
        $supportedLangs = $restaurant->supported_languages ?? ['en'];
        $defaultLang = $restaurant->default_language ?? 'en';
        $lang = $request->get('lang');
        if (!$lang || !in_array($lang, $supportedLangs)) {
            $lang = $defaultLang;
        }
        app()->setLocale($lang);

        $categories = $restaurant->categories()
            ->withoutGlobalScopes()
            ->with('translations')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::withoutGlobalScopes()
            ->with(['variants.translations', 'translations'])
            ->where('restaurant_id', $restaurant->id)
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->get();

        $deals = collect();
        if ($restaurant->isDealsEnabled()) {
            $deals = \App\Models\Deal::where('restaurant_id', $restaurant->id)
                ->where('is_available', true)
                ->with(['items.product', 'items.variant'])
                ->get();
        }

        return view('menu.show', compact('restaurant', 'categories', 'products', 'deals', 'lang'))
            ->with('activeCategory', $category)
            ->with('qrContext', null)
            ->with('qrTampered', false);
    }

    // Serving dynamic PWA manifest
    public function manifest(Request $request, Restaurant $restaurant)
    {
        $qrToken = $request->get('qr_token');
        
        // Start URL is either the tracking scan redirect URL or the public menu page
        $startUrl = $qrToken ? '/m/' . $qrToken : route('menu.show', $restaurant->slug, false);

        // Get logo URL, fall back to a default high-quality asset if not present
        $logoUrl = $restaurant->logo 
            ? \Illuminate\Support\Facades\Storage::url($restaurant->logo)
            : asset('images/placeholder.png');

        $logoPath = $restaurant->logo ?: 'images/placeholder.png';
        $extension = pathinfo($logoPath, PATHINFO_EXTENSION);
        $iconType = match (strtolower($extension)) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            default => 'image/png',
        };

        $manifest = [
            'name'             => $restaurant->name . ' Menu',
            'short_name'       => $restaurant->name,
            'description'      => 'Browse the digital menu of ' . $restaurant->name . ' and place your orders.',
            'start_url'        => $startUrl,
            'display'          => 'standalone',
            'background_color' => '#080808',
            'theme_color'      => '#e8502a',
            'orientation'      => 'portrait',
            'icons'            => [
                [
                    'src'   => $logoUrl,
                    'sizes' => '192x192',
                    'type'  => $iconType,
                ],
                [
                    'src'   => $logoUrl,
                    'sizes' => '512x512',
                    'type'  => $iconType,
                ],
            ],
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }
}

