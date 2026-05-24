<?php

namespace App\Services;

use App\Models\ScanLog;
use App\Models\Product;
use App\Models\QrCode;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    protected int $restaurantId;

    public function __construct(int $restaurantId)
    {
        $this->restaurantId = $restaurantId;
    }

    // Scans per day for last 14 days
    public function scansPerDay(): array
    {
        $rows = ScanLog::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId)
            ->where('visited_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(visited_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Fill in missing days with 0
        $result = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $result[$date] = $rows[$date] ?? 0;
        }

        return $result;
    }

    // Total scans this month
    public function totalScansThisMonth(): int
    {
        return ScanLog::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId)
            ->whereMonth('visited_at', now()->month)
            ->count();
    }

    // Total scans all time
    public function totalScansAllTime(): int
    {
        return ScanLog::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId)
            ->count();
    }

    // Top 5 most scanned QR codes
    public function topQrCodes(): \Illuminate\Support\Collection
    {
        return QrCode::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId)
            ->orderByDesc('scan_count')
            ->take(5)
            ->get(['token', 'type', 'scan_count']);
    }

    // Scans today
    public function scansToday(): int
    {
        return ScanLog::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId)
            ->whereDate('visited_at', today())
            ->count();
    }

    // Scans this week
    public function scansThisWeek(): int
    {
        return ScanLog::withoutGlobalScopes()
            ->where('restaurant_id', $this->restaurantId)
            ->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }
}