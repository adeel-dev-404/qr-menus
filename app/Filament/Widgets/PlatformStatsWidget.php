<?php

namespace App\Filament\Widgets;

use App\Models\Restaurant;
use App\Models\ScanLog;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Restaurants', Restaurant::count())
                ->description(Restaurant::where('status', 'active')->count() . ' active')
                ->color('success')
                ->icon('heroicon-o-building-storefront'),

            Stat::make('Pending Approvals', Restaurant::where('status', 'pending')->count())
                ->description('Awaiting review')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Total Users', User::count())
                ->description('All roles')
                ->color('info')
                ->icon('heroicon-o-users'),

            Stat::make('Scans Today', ScanLog::withoutGlobalScopes()->whereDate('visited_at', today())->count())
                ->description('Across all restaurants')
                ->color('primary')
                ->icon('heroicon-o-qr-code'),

            Stat::make('Total Scans', ScanLog::withoutGlobalScopes()->count())
                ->description('All time')
                ->color('gray')
                ->icon('heroicon-o-eye'),
        ];
    }
}