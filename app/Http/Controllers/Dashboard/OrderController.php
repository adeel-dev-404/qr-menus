<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

// class OrderController extends Controller
// {
//     public function index()
//     {
//         $orders = Order::with(['items', 'table', 'branch'])
//             ->when(request('status'),  fn($q) => $q->where('status', request('status')))
//             ->when(request('type'),    fn($q) => $q->where('type', request('type')))
//             ->when(request('search'),  fn($q) => $q->where(function ($q) {
//                 $q->where('order_number', 'like', '%' . request('search') . '%')
//                     ->orWhere('customer_name', 'like', '%' . request('search') . '%')
//                     ->orWhere('customer_phone', 'like', '%' . request('search') . '%');
//             }))
//             ->latest()
//             ->paginate(25);

//         $stats = [
//             'pending'   => Order::where('status', 'pending')->count(),
//             'preparing' => Order::where('status', 'preparing')->count(),
//             'ready'     => Order::where('status', 'ready')->count(),
//             'today'     => Order::whereDate('created_at', today())->count(),
//             'revenue'   => Order::whereDate('created_at', today())
//                 ->where('status', 'completed')->sum('total'),
//         ];

//         return view('dashboard.orders.index', compact('orders', 'stats'));
//     }

//     public function show(Order $order)
//     {
//         abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);
//         $order->load(['items.product', 'items.productVariant', 'table', 'branch']);
//         return view('dashboard.orders.show', compact('order'));
//     }

//     public function updateStatus(Request $request, Order $order)
//     {
//         abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);

//         $request->validate([
//             'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
//         ]);

//         (new OrderService())->updateStatus($order, $request->status);

//         if ($request->expectsJson()) {
//             return response()->json(['success' => true, 'status' => $request->status]);
//         }

//         return back()->with('success', "Order {$order->order_number} marked as " . ucfirst($request->status));
//     }

//     public function confirmPayment(Request $request, Order $order)
//     {
//         abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);
//         $order->update(['payment_status' => 'paid']);
//         return response()->json(['success' => true]);
//     }
// }
class OrderController extends Controller
{
    public function index()
    {
        $restaurantId = auth()->user()->restaurant_id;

        $orders = Order::query()->where('restaurant_id', $restaurantId)
            ->with(['items', 'branch'])
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->when(request('date'),   fn($q) => $q->whereDate('created_at', request('date')))
            ->latest()
            ->paginate(20);

        $stats = [
            'pending'   => Order::query()->where('restaurant_id', $restaurantId)->where('status', 'pending')->count(),
            'preparing' => Order::query()->where('restaurant_id', $restaurantId)->whereIn('status', ['confirmed', 'preparing'])->count(),
            'today'     => Order::query()->where('restaurant_id', $restaurantId)->whereDate('created_at', today())->count(),
            'revenue'   => Order::query()->where('restaurant_id', $restaurantId)->whereDate('created_at', today())->where('status', 'completed')->sum('total'),
        ];

        return view('dashboard.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load('items.product', 'items.variant', 'branch');
        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,completed,cancelled',
        ]);

        $data = ['status' => $request->status];
        if ($request->status === 'confirmed') $data['confirmed_at'] = now();
        if ($request->status === 'ready')     $data['ready_at']     = now();

        $order->update($data);

        // Send WhatsApp notification when order is ready
        if ($request->status === 'ready' && $order->customer_phone) {
            $phone   = '92' . ltrim(preg_replace('/[^0-9]/', '', $order->customer_phone), '0');
            $message = "🔔 Your order *{$order->order_number}* is ready! " .
                ($order->order_type === 'takeaway' ? "Please come to collect your order." : "It will be served shortly.");
            // This URL is opened by the restaurant owner in their browser
            $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        }

        if (request()->wantsJson()) {
            return response()->json([
                'status'       => $order->status,
                'status_label' => $order->status_label,
                'whatsapp_url' => $whatsappUrl ?? null,
            ]);
        }

        return back()->with('success', 'Order status updated.');
    }

    public function approvePayment(Order $order)
    {
        $this->authorizeOrder($order);
        $order->update(['payment_status' => 'paid']);
        return back()->with('success', 'Payment verified.');
    }

    private function authorizeOrder(Order $order): void
    {
        abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);
    }
}
