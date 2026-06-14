<?php

namespace App\Filament\Widgets;

use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Platform Growth';
    protected static ?string $description = 'New restaurants & subscription revenue over time';
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = ['md' => 2, 'xl' => 2];

    public ?string $filter = '12';

    protected function getFilters(): ?array
    {
        return [
            '6'  => 'Last 6 Months',
            '12' => 'Last 12 Months',
        ];
    }

    protected function getData(): array
    {
        $months = (int) $this->filter;
        $restaurantData = [];
        $revenueData = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $restaurantData[] = Restaurant::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $revenueData[] = (int) RestaurantSubscription::where('status', 'active')
                ->whereYear('approved_at', $month->year)
                ->whereMonth('approved_at', $month->month)
                ->sum('amount_paid');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'New Restaurants',
                    'data'            => $restaurantData,
                    'backgroundColor' => 'rgba(232, 80, 42, 0.08)',
                    'borderColor'     => '#e8502a',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'borderWidth'     => 2,
                    'pointBackgroundColor' => '#e8502a',
                    'pointBorderColor'     => '#e8502a',
                    'pointRadius'          => 4,
                    'pointHoverRadius'     => 6,
                    'yAxisID'              => 'y',
                ],
                [
                    'label'           => 'Revenue (Rs.)',
                    'data'            => $revenueData,
                    'backgroundColor' => 'rgba(74, 222, 128, 0.06)',
                    'borderColor'     => '#4ade80',
                    'fill'            => true,
                    'tension'         => 0.4,
                    'borderWidth'     => 2,
                    'pointBackgroundColor' => '#4ade80',
                    'pointBorderColor'     => '#4ade80',
                    'pointRadius'          => 3,
                    'pointHoverRadius'     => 5,
                    'yAxisID'              => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'color' => '#999',
                        'usePointStyle' => true,
                        'padding' => 20,
                        'font' => ['size' => 11],
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => '#1a1a1a',
                    'borderColor' => '#2a2a2a',
                    'borderWidth' => 1,
                    'titleColor' => '#ccc',
                    'bodyColor' => '#aaa',
                    'padding' => 12,
                    'cornerRadius' => 8,
                ],
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'position' => 'left',
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => '#555',
                        'stepSize' => 1,
                    ],
                    'grid' => [
                        'color' => '#1a1a1a',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'position' => 'right',
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => '#4ade80',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'color' => '#555',
                        'maxTicksLimit' => 7,
                    ],
                    'grid' => [
                        'color' => '#1a1a1a',
                    ],
                ],
            ],
        ];
    }
}