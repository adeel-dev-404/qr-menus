<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, Restaurant $restaurant)
    {
        abort_if(!$restaurant->isOrderingEnabled(), 404);
        abort_if($restaurant->status !== 'active', 404);

        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'type'           => 'required|in:dine_in,takeaway',
            'payment_method' => 'required|in:jazzcash,easypaisa,pay_later',
            'cart'           => 'required|json',
            'notes'          => 'nullable|string|max:500',
        ]);

        $cart = json_decode($request->cart, true);

        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $service = new OrderService();
        $order   = $service->placeOrder($restaurant, $request->all(), $cart);

        return redirect()->route('order.show', [$restaurant->slug, $order->id]);
    }

    public function show(Restaurant $restaurant, Order $order)
    {
        abort_if($order->restaurant_id !== $restaurant->id, 404);
        $order->load('items');
        return view('orders.show', compact('restaurant', 'order'));
    }

    public function submitPayment(Request $request, Restaurant $restaurant, Order $order)
    {
        abort_if($order->restaurant_id !== $restaurant->id, 404);

        $request->validate([
            'payment_reference' => 'required|string|max:100',
            'payment_proof'     => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs/orders', 'public');

        $service = new OrderService();
        $service->confirmPayment($order, $request->payment_reference, $path);

        return redirect()->route('order.show', [$restaurant->slug, $order->id])
            ->with('success', 'Payment submitted! The restaurant will confirm your order shortly.');
    }
}
