<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WaiterCall;
use App\Services\AnalyticsService;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $restaurant   = auth()->user()->restaurant;
        $restaurantId = $restaurant->id;
        $analytics    = new AnalyticsService($restaurantId);

        // ── Menu stats ──
        $stats = [
            'products'   => $restaurant->products()->count(),
            'categories' => $restaurant->categories()->count(),
            'qr_codes'   => $restaurant->qrCodes()->count(),
            'branches'   => $restaurant->branches()->count(),
        ];

        // ── Plan limits ──
        $limits = [
            'products'  => $restaurant->limitFor('products'),
            'qr_codes'  => $restaurant->limitFor('qr_codes'),
            'branches'  => $restaurant->limitFor('branches'),
        ];

        // ── QR Scan stats ──
        $scanStats = [
            'today'      => $analytics->scansToday(),
            'this_week'  => $analytics->scansThisWeek(),
            'this_month' => $analytics->totalScansThisMonth(),
            'all_time'   => $analytics->totalScansAllTime(),
        ];

        $scansPerDay = $analytics->scansPerDay();
        $topQrCodes  = $analytics->topQrCodes();

        // ── Order stats ──
        $orderStats = [
            'pending'        => Order::where('restaurant_id', $restaurantId)->where('status', 'pending')->count(),
            'confirmed'      => Order::where('restaurant_id', $restaurantId)->where('status', 'confirmed')->count(),
            'preparing'      => Order::where('restaurant_id', $restaurantId)->where('status', 'preparing')->count(),
            'ready'          => Order::where('restaurant_id', $restaurantId)->where('status', 'ready')->count(),
            'today_total'    => Order::where('restaurant_id', $restaurantId)->whereDate('created_at', today())->count(),
            'today_revenue'  => Order::where('restaurant_id', $restaurantId)
                                     ->whereDate('created_at', today())
                                     ->where('payment_status', 'paid')
                                     ->sum('total'),
            'month_revenue'  => Order::where('restaurant_id', $restaurantId)
                                     ->whereMonth('created_at', now()->month)
                                     ->where('payment_status', 'paid')
                                     ->sum('total'),
            'dine_in_today'  => Order::where('restaurant_id', $restaurantId)->whereDate('created_at', today())->where('type', 'dine_in')->count(),
            'takeaway_today' => Order::where('restaurant_id', $restaurantId)->whereDate('created_at', today())->where('type', 'takeaway')->count(),
        ];

        // ── Recent orders (last 8) ──
        $recentOrders = Order::with(['table', 'branch'])
            ->where('restaurant_id', $restaurantId)
            ->latest()
            ->take(8)
            ->get();

        // ── Revenue per day — last 14 days ──
        $revenueRows = Order::where('restaurant_id', $restaurantId)
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $revenuePerDay = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenuePerDay[$date] = round($revenueRows[$date] ?? 0, 2);
        }

        // ── Top 5 products by order count ──
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('COUNT(*) as order_count'))
            ->whereHas('order', fn($q) => $q->where('restaurant_id', $restaurantId))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // ── Waiter call stats ──
        $waiterStats = null;
        if ($restaurant->isWaiterCallEnabled()) {
            $waiterStats = [
                'pending'  => WaiterCall::forRestaurant($restaurantId)->where('status', 'pending')->count(),
                'seen'     => WaiterCall::forRestaurant($restaurantId)->where('status', 'seen')->count(),
                'resolved_today' => WaiterCall::forRestaurant($restaurantId)
                                              ->where('status', 'resolved')
                                              ->whereDate('created_at', today())
                                              ->count(),
            ];
        }

        return view('dashboard.home', compact(
            'restaurant',
            'stats',
            'limits',
            'scanStats',
            'scansPerDay',
            'topQrCodes',
            'orderStats',
            'recentOrders',
            'revenuePerDay',
            'topProducts',
            'waiterStats'
        ));
    }
}
