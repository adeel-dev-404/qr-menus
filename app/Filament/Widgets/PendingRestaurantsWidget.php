<?php

namespace App\Filament\Widgets;

use App\Models\Restaurant;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingRestaurantsWidget extends BaseWidget
{
    protected static ?string $heading = '🔔 Pending Restaurant Approvals';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Restaurant::query()
                    ->where('status', 'pending')
                    ->latest('created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Restaurant')
                    ->weight('bold')
                    ->searchable()
                    ->icon('heroicon-o-building-storefront'),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->color('gray')
                    ->default('—'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->color('gray')
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->color('gray')
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('subscription.name')
                    ->label('Plan')
                    ->badge()
                    ->color('info')
                    ->default('Free'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->since()
                    ->color('warning')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Restaurant')
                    ->modalDescription(fn (Restaurant $record) => "Approve \"{$record->name}\"? This will activate their account and allow them to set up their menu.")
                    ->action(function (Restaurant $record): void {
                        $record->update(['status' => 'active']);
                    })
                    ->successNotificationTitle('Restaurant approved! ✅'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Restaurant')
                    ->modalDescription(fn (Restaurant $record) => "Reject \"{$record->name}\"? This will mark their application as inactive.")
                    ->action(function (Restaurant $record): void {
                        $record->update(['status' => 'inactive']);
                    })
                    ->successNotificationTitle('Restaurant rejected'),
            ])
            ->emptyStateHeading('No pending approvals')
            ->emptyStateDescription('All restaurant applications have been reviewed.')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}