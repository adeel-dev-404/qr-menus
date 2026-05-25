<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Main menu page: /r/{restaurant}
    public function show(Request $request, Restaurant $restaurant)
    {
        // Only show active restaurants
        abort_if($restaurant->status !== 'active', 404);

        $categories = $restaurant->categories()
            ->withoutGlobalScopes()
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Active category filter
        $activeCategorySlug = $request->get('category');
        $activeCategory = $activeCategorySlug
            ? $categories->firstWhere('slug', $activeCategorySlug)
            : $categories->first();

        $products = collect();
        if ($activeCategory) {
            $products = Product::withoutGlobalScopes()
                ->with('variants')                      // ✅ ADD THIS
                ->where('restaurant_id', $restaurant->id)
                ->where('category_id', $activeCategory->id)
                ->where('is_available', true)
                ->get();
        }

        return view('menu.show', compact('restaurant', 'categories', 'products', 'activeCategory'));
    }

    // Single category page: /r/{restaurant}/category/{category}
    public function category(Restaurant $restaurant, Category $category)
    {
        abort_if($restaurant->status !== 'active', 404);
        abort_if($category->restaurant_id !== $restaurant->id, 404);

        $categories = $restaurant->categories()
            ->withoutGlobalScopes()
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        $products = Product::withoutGlobalScopes()
            ->where('restaurant_id', $restaurant->id)
            ->where('category_id', $category->id)
            ->where('is_available', true)
            ->get();

        return view('menu.show', compact('restaurant', 'categories', 'products'))
            ->with('activeCategory', $category);
    }
}
