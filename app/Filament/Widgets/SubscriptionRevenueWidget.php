<?php

namespace App\Filament\Widgets;

use App\Models\RestaurantSubscription;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionRevenueWidget extends ChartWidget
{
    protected static ?string $heading = 'Subscription Distribution';
    protected static ?string $description = 'Active restaurants by plan';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = ['md' => 1, 'xl' => 1];

    protected function getData(): array
    {
        $plans = Subscription::withCount(['restaurants' => function ($q) {
            $q->where('status', 'active');
        }])->get();

        // Add "Free" plan count (restaurants without a subscription)
        $freeCount = \App\Models\Restaurant::where('status', 'active')
            ->whereNull('subscription_id')
            ->count();

        $labels = ['Free Plan'];
        $data = [$freeCount];
        $colors = [
            '#6b7280', // gray for free
        ];

        $planColors = ['#e8502a', '#3b82f6', '#8b5cf6', '#f59e0b', '#10b981', '#ec4899'];

        foreach ($plans as $i => $plan) {
            $labels[] = $plan->name;
            $data[] = $plan->restaurants_count;
            $colors[] = $planColors[$i % count($planColors)];
        }

        return [
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => $colors,
                    'borderColor'     => '#111111',
                    'borderWidth'     => 2,
                    'hoverOffset'     => 8,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                    'labels'   => [
                        'color'         => '#999',
                        'usePointStyle' => true,
                        'padding'       => 16,
                        'font'          => ['size' => 11],
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => '#1a1a1a',
                    'borderColor'     => '#2a2a2a',
                    'borderWidth'     => 1,
                    'titleColor'      => '#ccc',
                    'bodyColor'       => '#aaa',
                    'padding'         => 10,
                    'cornerRadius'    => 8,
                ],
            ],
            'cutout' => '60%',
        ];
    }
}
