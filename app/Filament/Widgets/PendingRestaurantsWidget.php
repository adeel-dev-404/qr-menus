<?php

namespace App\Filament\Widgets;

use App\Models\Restaurant;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingRestaurantsWidget extends BaseWidget
{
    protected static ?string $heading = 'Pending Restaurant Approvals';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Restaurant::where('status', 'pending'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold'),
                Tables\Columns\TextColumn::make('email')->color('gray'),
                Tables\Columns\TextColumn::make('phone')->color('gray'),
                Tables\Columns\TextColumn::make('created_at')->label('Applied')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(fn (Restaurant $r) => $r->update(['status' => 'active']))
                    ->requiresConfirmation()
                    ->successNotificationTitle('Restaurant approved!'),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->action(fn (Restaurant $r) => $r->update(['status' => 'inactive']))
                    ->requiresConfirmation(),
            ]);
    }
}