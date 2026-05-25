<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

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

    // public function store(StoreProductRequest $request)
    // {
    //     $restaurant = auth()->user()->restaurant;
    //     if (!$restaurant->canAdd('products')) {
    //         return redirect()->route('dashboard.products.index')
    //             ->with('error', "Product limit reached. Please upgrade your plan.");
    //     }

    //     $product = Product::create([
    //         ...$request->safe()->except(['variant_names', 'variant_prices', 'variant_discount_prices', 'variant_available']),
    //         'restaurant_id' => $restaurant->id,
    //         'is_available'  => $request->boolean('is_available', true),
    //     ]);

    //     if ($request->hasFile('image')) {
    //         $product->addMediaFromRequest('image')->toMediaCollection('image');
    //     }

    //     // Save variants
    //     $this->saveVariants($product, $request);

    //     return redirect()->route('dashboard.products.index')
    //         ->with('success', 'Product created successfully.');
    // }

    public function edit(Product $product)
    {
        $product->load('variants');  // ✅ Must be here
        $categories = Category::active()->orderBy('name')->get();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }
    // public function update(UpdateProductRequest $request, Product $product)
    // {
    //     $product->update([
    //         ...$request->safe()->except(['variant_names', 'variant_prices', 'variant_discount_prices', 'variant_available']),
    //         'is_available' => $request->boolean('is_available', true),
    //     ]);

    //     if ($request->hasFile('image')) {
    //         $product->clearMediaCollection('image');
    //         $product->addMediaFromRequest('image')->toMediaCollection('image');
    //     }

    //     // Delete old variants and re-save
    //     $product->variants()->delete();
    //     $this->saveVariants($product, $request);

    //     return redirect()->route('dashboard.products.index')
    //         ->with('success', 'Product updated successfully.');
    // }

    public function update(Request $request, Product $product)
    {
        // Validate manually to avoid Form Request stripping variant arrays
        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'category_id'               => 'required|exists:categories,id',
            'description'               => 'nullable|string|max:1000',
            'price'                     => 'required|numeric|min:0',
            'discount_price'            => 'nullable|numeric|min:0',
            'is_available'              => 'boolean',
            'image'                     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variant_names'             => 'nullable|array',
            'variant_names.*'           => 'nullable|string|max:100',
            'variant_prices'            => 'nullable|array',
            'variant_prices.*'          => 'nullable|numeric|min:0',
            'variant_discount_prices'   => 'nullable|array',
            'variant_discount_prices.*' => 'nullable|numeric|min:0',
            'variant_available'         => 'nullable|array',
        ]);

        // Update base product
        $product->update([
            'name'           => $validated['name'],
            'category_id'    => $validated['category_id'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'is_available'   => $request->boolean('is_available', true),
        ]);

        // Replace image if new one uploaded
        if ($request->hasFile('image')) {
            $product->clearMediaCollection('image');
            $product->addMediaFromRequest('image')->toMediaCollection('image');
        }

        // Delete ALL old variants then re-save fresh
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

    public function store(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        if (!$restaurant->canAdd('products')) {
            return redirect()->route('dashboard.products.index')
                ->with('error', "Product limit reached. Please upgrade your plan.");
        }

        $validated = $request->validate([
            'name'                      => 'required|string|max:255',
            'category_id'               => 'required|exists:categories,id',
            'description'               => 'nullable|string|max:1000',
            'price'                     => 'required|numeric|min:0',
            'discount_price'            => 'nullable|numeric|min:0',
            'is_available'              => 'boolean',
            'image'                     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variant_names'             => 'nullable|array',
            'variant_names.*'           => 'nullable|string|max:100',
            'variant_prices'            => 'nullable|array',
            'variant_prices.*'          => 'nullable|numeric|min:0',
            'variant_discount_prices'   => 'nullable|array',
            'variant_discount_prices.*' => 'nullable|numeric|min:0',
            'variant_available'         => 'nullable|array',
        ]);

        $product = Product::create([
            'name'          => $validated['name'],
            'category_id'   => $validated['category_id'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'restaurant_id' => $restaurant->id,
            'is_available'  => $request->boolean('is_available', true),
        ]);

        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection('image');
        }

        $this->saveVariants($product, $request);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created successfully.');
    }

    // ✅ Fixed saveVariants — reads directly from $request not $validated
    private function saveVariants(Product $product, Request $request): void
    {
        $names     = $request->input('variant_names', []);
        $prices    = $request->input('variant_prices', []);
        $discounts = $request->input('variant_discount_prices', []);
        $available = $request->input('variant_available', []);

        if (empty($names)) return;

        foreach ($names as $i => $name) {
            // Skip empty rows
            if (empty(trim((string) $name))) continue;
            if (empty($prices[$i])) continue;

            \App\Models\ProductVariant::create([
                'product_id'     => $product->id,
                'name'           => trim($name),
                'price'          => (float) $prices[$i],
                'discount_price' => !empty($discounts[$i]) ? (float) $discounts[$i] : null,
                'is_available'   => isset($available[$i]) && $available[$i] == '1',
                'sort_order'     => $i,
            ]);
        }
    }
}
