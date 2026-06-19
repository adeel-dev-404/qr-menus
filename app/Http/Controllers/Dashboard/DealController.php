<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\DealItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::where('restaurant_id', auth()->user()->restaurant_id)
            ->with('items.product', 'items.variant')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.deals.index', compact('deals'));
    }

    public function create()
    {
        $restaurant = auth()->user()->restaurant;
        // All products for this restaurant (including variants)
        $products = Product::where('restaurant_id', $restaurant->id)
            ->where('is_available', true)
            ->with('variants')
            ->get();

        return view('dashboard.deals.create', compact('products'));
    }

    public function store(Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'is_available'   => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deal_items'     => 'required|array|min:1',
            'deal_items.*.product_id' => 'required|exists:products,id',
            'deal_items.*.variant_id' => 'nullable|exists:product_variants,id',
            'deal_items.*.quantity'   => 'required|integer|min:1',
        ]);

        $deal = Deal::create([
            'restaurant_id' => $restaurant->id,
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'is_available'  => $request->boolean('is_available', true),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('deals', 'public');
            $deal->update(['image' => $path]);
        }

        foreach ($validated['deal_items'] as $item) {
            DealItem::create([
                'deal_id'            => $deal->id,
                'product_id'         => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'quantity'           => $item['quantity'],
            ]);
        }

        return redirect()->route('dashboard.deals.index')
            ->with('success', 'Deal created successfully.');
    }

    public function edit(Deal $deal)
    {
        if ($deal->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403);
        }

        $deal->load('items');

        $restaurant = auth()->user()->restaurant;
        $products = Product::where('restaurant_id', $restaurant->id)
            ->where('is_available', true)
            ->with('variants')
            ->get();

        return view('dashboard.deals.edit', compact('deal', 'products'));
    }

    public function update(Request $request, Deal $deal)
    {
        if ($deal->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'is_available'   => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deal_items'     => 'required|array|min:1',
            'deal_items.*.product_id' => 'required|exists:products,id',
            'deal_items.*.variant_id' => 'nullable|exists:product_variants,id',
            'deal_items.*.quantity'   => 'required|integer|min:1',
        ]);

        $deal->update([
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'is_available'  => $request->boolean('is_available', true),
        ]);

        if ($request->hasFile('image')) {
            if ($deal->image) {
                Storage::disk('public')->delete($deal->image);
            }
            $path = $request->file('image')->store('deals', 'public');
            $deal->update(['image' => $path]);
        }

        // Re-create items
        $deal->items()->delete();
        foreach ($validated['deal_items'] as $item) {
            DealItem::create([
                'deal_id'            => $deal->id,
                'product_id'         => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'quantity'           => $item['quantity'],
            ]);
        }

        return redirect()->route('dashboard.deals.index')
            ->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal)
    {
        if ($deal->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403);
        }

        if ($deal->image) {
            Storage::disk('public')->delete($deal->image);
        }
        $deal->delete();

        return redirect()->route('dashboard.deals.index')
            ->with('success', 'Deal deleted successfully.');
    }

    public function toggleAvailability(Deal $deal)
    {
        if ($deal->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $deal->update(['is_available' => !$deal->is_available]);
        return response()->json(['is_available' => $deal->is_available]);
    }
}
