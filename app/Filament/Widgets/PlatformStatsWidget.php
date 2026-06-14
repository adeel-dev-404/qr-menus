<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use App\Models\ScanLog;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        // Calculate trends (last 7 days data for sparklines)
        $restaurantTrend = $this->getWeeklyTrend(Restaurant::class);
        $userTrend = $this->getWeeklyTrend(User::class);
        $scanTrend = $this->getWeeklyScanTrend();

        $totalRestaurants = Restaurant::count();
        $activeRestaurants = Restaurant::where('status', 'active')->count();
        $pendingApprovals = Restaurant::where('status', 'pending')->count();
        $totalUsers = User::count();
        $scansToday = ScanLog::withoutGlobalScopes()->whereDate('visited_at', today())->count();
        $totalScans = ScanLog::withoutGlobalScopes()->count();

        // Revenue from subscriptions
        $monthRevenue = RestaurantSubscription::where('status', 'active')
            ->whereMonth('approved_at', now()->month)
            ->whereYear('approved_at', now()->year)
            ->sum('amount_paid');

        // Active subscriptions
        $activeSubscriptions = RestaurantSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->count();

        // Today's orders across all restaurants
        $todayOrders = Order::withoutGlobalScopes()->whereDate('created_at', today())->count();
        $todayRevenue = Order::withoutGlobalScopes()->whereDate('created_at', today())->sum('total');

        // New restaurants this week vs last week
        $thisWeekNew = Restaurant::whereBetween('created_at', [now()->startOfWeek(), now()])->count();
        $lastWeekNew = Restaurant::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();
        $growthDesc = $thisWeekNew > $lastWeekNew
            ? '↑ ' . ($thisWeekNew - $lastWeekNew) . ' more than last week'
            : ($thisWeekNew < $lastWeekNew
                ? '↓ ' . ($lastWeekNew - $thisWeekNew) . ' less than last week'
                : 'Same as last week');

        return [
            Stat::make('Total Restaurants', number_format($totalRestaurants))
                ->description($activeRestaurants . ' active · ' . $growthDesc)
                ->color('success')
                ->icon('heroicon-o-building-storefront')
                ->chart($restaurantTrend),

            Stat::make('Pending Approvals', $pendingApprovals)
                ->description($pendingApprovals > 0 ? '⚡ Needs attention' : 'All clear')
                ->color($pendingApprovals > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clock'),

            Stat::make('Active Subscriptions', $activeSubscriptions)
                ->description('Rs.' . number_format($monthRevenue, 0) . ' this month')
                ->color('info')
                ->icon('heroicon-o-credit-card'),

            Stat::make('Total Users', number_format($totalUsers))
                ->description('Across all restaurants')
                ->color('primary')
                ->icon('heroicon-o-users')
                ->chart($userTrend),

            Stat::make('Today\'s Orders', number_format($todayOrders))
                ->description('Rs.' . number_format($todayRevenue, 0) . ' revenue')
                ->color('success')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make('QR Scans Today', number_format($scansToday))
                ->description(number_format($totalScans) . ' all time')
                ->color('warning')
                ->icon('heroicon-o-qr-code')
                ->chart($scanTrend),
        ];
    }

    private function getWeeklyTrend(string $model): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = $model::whereDate('created_at', now()->subDays($i))->count();
        }
        return $data;
    }

    private function getWeeklyScanTrend(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = ScanLog::withoutGlobalScopes()
                ->whereDate('visited_at', now()->subDays($i))
                ->count();
        }
        return $data;
    }
}