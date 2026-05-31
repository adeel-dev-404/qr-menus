<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Restaurant;
use App\Services\OrderService;
use Illuminate\Http\Request;

// class OrderController extends Controller
// {
//     public function place(Request $request, Restaurant $restaurant)
//     {
//         abort_if(!$restaurant->ordering_enabled, 404, 'Ordering is not enabled for this restaurant.');
//         abort_if($restaurant->status !== 'active', 404);

//         $request->validate([
//             'customer_name'  => 'required|string|max:100',
//             'customer_phone' => 'required|string|max:20',
//             'customer_email' => 'nullable|email',
//             'type'           => 'required|in:dine_in,takeaway',
//             'payment_method' => 'required|in:cash,jazzcash,easypaisa',
//             'notes'          => 'nullable|string|max:500',
//             'items'          => 'required|array|min:1',
//             'items.*.product_id'  => 'required|exists:products,id',
//             'items.*.variant_id'  => 'nullable|exists:product_variants,id',
//             'items.*.quantity'    => 'required|integer|min:1|max:50',
//             'items.*.notes'       => 'nullable|string|max:200',
//         ]);

//         // Build items with price snapshot
//         $orderItems = [];
//         foreach ($request->items as $item) {
//             $product = Product::withoutGlobalScopes()->findOrFail($item['product_id']);
//             abort_if($product->restaurant_id !== $restaurant->id, 403);
//             abort_if(!$product->is_available, 422, "{$product->name} is not available.");

//             $variantName = null;
//             $unitPrice   = $product->discount_price ?? $product->price;

//             if (!empty($item['variant_id'])) {
//                 $variant     = ProductVariant::findOrFail($item['variant_id']);
//                 $variantName = $variant->name;
//                 $unitPrice   = $variant->discount_price ?? $variant->price;
//                 abort_if(!$variant->is_available, 422, "{$product->name} ({$variant->name}) is not available.");
//             }

//             $orderItems[] = [
//                 'product_id'   => $product->id,
//                 'variant_id'   => $item['variant_id'] ?? null,
//                 'product_name' => $product->name,
//                 'variant_name' => $variantName,
//                 'unit_price'   => $unitPrice,
//                 'quantity'     => $item['quantity'],
//                 'notes'        => $item['notes'] ?? null,
//             ];
//         }

//         $service = new OrderService();
//         $order   = $service->placeOrder($request->all(), $orderItems, $restaurant);

//         // Send WhatsApp notification to restaurant
//         $whatsappMsg = $service->buildWhatsAppMessage($order);
//         $waNumber    = preg_replace('/[^0-9]/', '', $restaurant->whatsapp_number ?? '');

//         // Return JSON for AJAX cart submit
//         return response()->json([
//             'success'       => true,
//             'order_number'  => $order->order_number,
//             'order_id'      => $order->id,
//             'total'         => $order->total,
//             'payment_method' => $order->payment_method,
//             'confirm_url'   => route('order.confirm', [$restaurant->slug, $order->id]),
//             'track_url'     => route('order.track',   [$restaurant->slug, $order->order_number]),
//             'whatsapp_url'  => $waNumber
//                 ? "https://wa.me/{$waNumber}?text={$whatsappMsg}"
//                 : null,
//         ]);
//     }

//     public function confirm(Restaurant $restaurant, Order $order)
//     {
//         abort_if($order->restaurant_id !== $restaurant->id, 404);
//         return view('order.confirm', compact('restaurant', 'order'));
//     }

//     public function submitPayment(Request $request, Restaurant $restaurant, Order $order)
//     {
//         abort_if($order->restaurant_id !== $restaurant->id, 404);

//         $request->validate([
//             'transaction_ref' => 'required|string|max:100',
//             'payment_proof'   => 'required|image|mimes:jpg,jpeg,png|max:3072',
//         ]);

//         $path = $request->file('payment_proof')
//             ->store('payment-proofs/orders', 'public');

//         (new OrderService())->confirmPayment($order, $request->transaction_ref, $path);

//         // Notify restaurant on WhatsApp about payment
//         $waNumber = preg_replace('/[^0-9]/', '', $restaurant->whatsapp_number ?? '');
//         $msg = urlencode(
//             "💳 *Payment Received — {$order->order_number}*\n" .
//                 "Ref: {$request->transaction_ref}\n" .
//                 "Amount: Rs. " . number_format($order->total, 0)
//         );
//         $waUrl = $waNumber ? "https://wa.me/{$waNumber}?text={$msg}" : null;

//         return response()->json([
//             'success'      => true,
//             'track_url'    => route('order.track', [$restaurant->slug, $order->order_number]),
//             'whatsapp_url' => $waUrl,
//         ]);
//     }

//     public function track(Restaurant $restaurant, string $orderNumber)
//     {
//         $order = Order::withoutGlobalScopes()
//             ->with('items')
//             ->where('restaurant_id', $restaurant->id)
//             ->where('order_number', $orderNumber)
//             ->firstOrFail();

//         return view('order.track', compact('restaurant', 'order'));
//     }
// }
class OrderController extends Controller
{
    // Show checkout page
    public function checkout(Restaurant $restaurant)
    {
        abort_if(!$restaurant->ordering_enabled, 404);
        abort_if($restaurant->status !== 'active', 404);

        $cartData = session('cart_' . $restaurant->id, []);
        if (empty($cartData)) {
            return redirect()->route('menu.show', $restaurant->slug)
                ->with('error', 'Your cart is empty.');
        }

        return view('menu.checkout', compact('restaurant', 'cartData'));
    }

    // Add item to cart
    public function addToCart(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'integer|min:1|max:20',
        ]);

        $product  = Product::withoutGlobalScopes()->findOrFail($request->product_id);
        $variant  = $request->variant_id
            ? ProductVariant::find($request->variant_id)
            : null;

        $price = $variant
            ? ($variant->discount_price ?? $variant->price)
            : ($product->discount_price ?? $product->price);

        $key  = $request->product_id . '_' . ($request->variant_id ?? 0);
        $cart = session('cart_' . $restaurant->id, []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += ($request->quantity ?? 1);
        } else {
            $cart[$key] = [
                'product_id'   => $product->id,
                'variant_id'   => $variant?->id,
                'product_name' => $product->name,
                'variant_name' => $variant?->name,
                'price'        => (float) $price,
                'quantity'     => (int) ($request->quantity ?? 1),
                'image'        => $product->image_url,
            ];
        }

        session(['cart_' . $restaurant->id => $cart]);

        return response()->json([
            'success'    => true,
            'cart_count' => collect($cart)->sum('quantity'),
            'message'    => $product->name . ' added to cart',
        ]);
    }

    // Update cart item quantity
    public function updateCart(Request $request, Restaurant $restaurant)
    {
        $key  = $request->key;
        $cart = session('cart_' . $restaurant->id, []);

        if ($request->quantity <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = (int) $request->quantity;
        }

        session(['cart_' . $restaurant->id => $cart]);

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        return response()->json([
            'cart_count' => collect($cart)->sum('quantity'),
            'total'      => number_format($total, 0),
        ]);
    }

    // Get cart count for badge
    public function cartCount(Restaurant $restaurant)
    {
        $cart = session('cart_' . $restaurant->id, []);
        return response()->json(['count' => collect($cart)->sum('quantity')]);
    }

    // Place the order
    public function placeOrder(Request $request, Restaurant $restaurant)
    {
        abort_if(!$restaurant->ordering_enabled, 404);

        $request->validate([
            'order_type'      => 'required|in:dine_in,takeaway',
            'customer_name'   => 'required|string|max:100',
            'customer_phone'  => 'required|string|max:20',
            'payment_method'  => 'required|in:jazzcash,easypaisa,cash',
            'table_number'    => 'required_if:order_type,dine_in',
            'payment_ref'     => 'required_if:payment_method,jazzcash,easypaisa|nullable|string|max:100',
            'payment_proof'   => 'required_if:payment_method,jazzcash,easypaisa|nullable|image|max:3072',
            'notes'           => 'nullable|string|max:500',
        ]);

        $cart = session('cart_' . $restaurant->id, []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'order_number'    => Order::generateNumber($restaurant->id),
                'restaurant_id'   => $restaurant->id,
                'order_type'      => $request->order_type,
                'table_number'    => $request->table_number,
                'customer_name'   => $request->customer_name,
                'customer_phone'  => $request->customer_phone,
                'payment_method'  => $request->payment_method,
                'payment_status'  => in_array($request->payment_method, ['jazzcash', 'easypaisa']) ? 'pending' : 'unpaid',
                'payment_ref'     => $request->payment_ref,
                'subtotal'        => $subtotal,
                'total'           => $subtotal,
                'notes'           => $request->notes,
                'status'          => 'pending',
            ]);

            // Handle payment proof upload
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('payment-proofs', 'public');
                $order->update(['payment_proof' => $path]);
            }

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'],
                    'variant_id'   => $item['variant_id'],
                    'product_name' => $item['product_name'],
                    'variant_name' => $item['variant_name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // Clear cart
            session()->forget('cart_' . $restaurant->id);

            // Generate WhatsApp notification URL for restaurant
            $whatsappUrl = WhatsAppService::sendOrderNotification($order);

            return redirect()->route('order.confirmation', [
                'restaurant' => $restaurant->slug,
                'order'      => $order->order_number,
            ])->with('whatsapp_url', $whatsappUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    // Order confirmation page
    public function confirmation(Restaurant $restaurant, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('restaurant_id', $restaurant->id)
            ->with('items')
            ->firstOrFail();

        return view('menu.order-confirmation', compact('restaurant', 'order'));
    }
}
