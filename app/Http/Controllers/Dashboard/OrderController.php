<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $restaurantId = auth()->user()->restaurant_id;

        // ── Build query with all filters ──
        $query = Order::with(['items', 'table', 'branch'])
            ->where('restaurant_id', $restaurantId)
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type (dine_in / takeaway)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by payment status
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        $orders = $query->paginate(20)->withQueryString();

        // ── Stats (always unfiltered — show totals) ──
        $stats = [
            'pending'   => Order::where('restaurant_id', $restaurantId)->where('status', 'pending')->count(),
            'preparing' => Order::where('restaurant_id', $restaurantId)->where('status', 'preparing')->count(),
            'ready'     => Order::where('restaurant_id', $restaurantId)->where('status', 'ready')->count(),
            'today'     => Order::where('restaurant_id', $restaurantId)->whereDate('created_at', today())->count(),
            'revenue'   => Order::where('restaurant_id', $restaurantId)
                                ->whereDate('created_at', today())
                                ->where('payment_status', 'paid')
                                ->sum('total'),
        ];

        return view('dashboard.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);
        $order->load('items', 'table', 'branch');
        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);

        $request->validate([
            'status' => 'required|in:confirmed,preparing,ready,delivered,cancelled',
        ]);

        (new OrderService())->updateStatus($order, $request->status);

        if ($request->expectsJson()) {
            return response()->json(['status' => $order->fresh()->status_label]);
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status) . '.');
    }

    public function confirmPayment(Order $order)
    {
        abort_if($order->restaurant_id !== auth()->user()->restaurant_id, 403);

        $order->update(['payment_status' => 'paid']);

        return back()->with('success', 'Payment confirmed successfully.');
    }
}