<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'variants'])
            ->when(request('category'), fn($q) => $q->where('category_id', request('category')))
            ->when(request('search'),   fn($q) => $q->where('name', 'like', '%' . request('search') . '%'))
            ->when(request('status') !== null, fn($q) => $q->where('is_available', request('status')))
            ->latest()
            ->paginate(20);

        $categories = Category::active()->orderBy('name')->get();
        return view('dashboard.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $restaurant = auth()->user()->restaurant;
        if (!$restaurant->canAdd('products')) {
            return redirect()->route('dashboard.products.index')
                ->with('error', "Product limit reached ({$restaurant->limitFor('products')}). Please upgrade.");
        }
        $categories = Category::active()->orderBy('name')->get();
        return view('dashboard.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $restaurant = auth()->user()->restaurant;
        if (!$restaurant->canAdd('products')) {
            return redirect()->route('dashboard.products.index')
                ->with('error', "Product limit reached. Please upgrade your plan.");
        }

        $product = Product::create([
            ...$request->safe()->except(['variant_names', 'variant_prices', 'variant_discount_prices', 'variant_available']),
            'restaurant_id' => $restaurant->id,
            'is_available'  => $request->boolean('is_available', true),
        ]);

        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection('image');
        }

        // Save variants
        $this->saveVariants($product, $request);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        $categories = Category::active()->orderBy('name')->get();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            ...$request->safe()->except(['variant_names', 'variant_prices', 'variant_discount_prices', 'variant_available']),
            'is_available' => $request->boolean('is_available', true),
        ]);

        if ($request->hasFile('image')) {
            $product->clearMediaCollection('image');
            $product->addMediaFromRequest('image')->toMediaCollection('image');
        }

        // Delete old variants and re-save
        $product->variants()->delete();
        $this->saveVariants($product, $request);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->variants()->delete();
        $product->clearMediaCollection('image');
        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted.');
    }

    public function toggleAvailability(Product $product)
    {
        $product->update(['is_available' => !$product->is_available]);
        return response()->json(['is_available' => $product->is_available]);
    }

    // Helper: save variants from request arrays
    private function saveVariants(Product $product, $request): void
    {
        $names     = $request->input('variant_names', []);
        $prices    = $request->input('variant_prices', []);
        $discounts = $request->input('variant_discount_prices', []);
        $available = $request->input('variant_available', []);

        foreach ($names as $i => $name) {
            if (empty(trim($name)) || empty($prices[$i])) continue;

            ProductVariant::create([
                'product_id'     => $product->id,
                'name'           => trim($name),
                'price'          => $prices[$i],
                'discount_price' => !empty($discounts[$i]) ? $discounts[$i] : null,
                'is_available'   => isset($available[$i]) ? true : false,
                'sort_order'     => $i,
            ]);
        }
    }
}