<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;

class HomeController extends Controller
{
    // public function index()
    // {
    //     $restaurant = auth()->user()->restaurant;
    //     $analytics  = new AnalyticsService($restaurant->id);

    //     $stats = [
    //         'products'   => $restaurant->products()->count(),
    //         'categories' => $restaurant->categories()->count(),
    //         'qr_codes'   => $restaurant->qrCodes()->count(),
    //         'branches'   => $restaurant->branches()->count(),
    //     ];

    //     $scanStats = [
    //         'today'      => $analytics->scansToday(),
    //         'this_week'  => $analytics->scansThisWeek(),
    //         'this_month' => $analytics->totalScansThisMonth(),
    //         'all_time'   => $analytics->totalScansAllTime(),
    //     ];

    //     $scansPerDay = $analytics->scansPerDay();
    //     $topQrCodes  = $analytics->topQrCodes();

    //     return view('dashboard.home', compact(
    //         'restaurant', 'stats', 'scanStats', 'scansPerDay', 'topQrCodes'
    //     ));
    // }
    public function index()
    {
        $restaurant = auth()->user()->restaurant;
        $analytics  = new AnalyticsService($restaurant->id);

        $stats = [
            'products'   => $restaurant->products()->count(),
            'categories' => $restaurant->categories()->count(),
            'qr_codes'   => $restaurant->qrCodes()->count(),
            'branches'   => $restaurant->branches()->count(),
        ];

        // Add plan limits to stats
        $limits = [
            'products'  => $restaurant->limitFor('products'),
            'qr_codes'  => $restaurant->limitFor('qr_codes'),
            'branches'  => $restaurant->limitFor('branches'),
        ];

        $scanStats = [
            'today'      => $analytics->scansToday(),
            'this_week'  => $analytics->scansThisWeek(),
            'this_month' => $analytics->totalScansThisMonth(),
            'all_time'   => $analytics->totalScansAllTime(),
        ];

        $scansPerDay = $analytics->scansPerDay();
        $topQrCodes  = $analytics->topQrCodes();

        return view('dashboard.home', compact(
            'restaurant',
            'stats',
            'limits',
            'scanStats',
            'scansPerDay',
            'topQrCodes'
        ));
    }
}
