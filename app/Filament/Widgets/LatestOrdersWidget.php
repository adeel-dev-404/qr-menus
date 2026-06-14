<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?string $heading = '🛒 Latest Orders (All Restaurants)';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::withoutGlobalScopes()
                    ->with(['restaurant', 'branch'])
                    ->latest('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->fontFamily('mono')
                    ->color('primary')
                    ->size('sm')
                    ->searchable(),

                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Restaurant')
                    ->weight('bold')
                    ->searchable()
                    ->icon('heroicon-o-building-storefront'),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->color('gray')
                    ->limit(20),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'dine_in'  => '🍽 Dine-in',
                        'takeaway' => '🥡 Takeaway',
                        default    => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'dine_in'  => 'info',
                        'takeaway' => 'warning',
                        default    => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('PKR')
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'confirmed',
                        'primary' => 'preparing',
                        'success' => 'ready',
                        'gray'    => 'delivered',
                        'danger'  => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Time')
                    ->since()
                    ->color('gray')
                    ->size('sm'),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('No orders yet')
            ->emptyStateDescription('Orders from all restaurants will appear here.')
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }
}
