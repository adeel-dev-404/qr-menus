<?php

namespace App\Filament\Widgets;

use App\Models\Restaurant;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'New Restaurants — Last 12 Months';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data   = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[]   = Restaurant::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'datasets' => [[
                'label'           => 'New Restaurants',
                'data'            => $data,
                'backgroundColor' => 'rgba(59,130,246,0.1)',
                'borderColor'     => '#3b82f6',
                'fill'            => true,
                'tension'         => 0.4,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}