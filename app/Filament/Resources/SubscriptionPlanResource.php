<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionPlanResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static ?string $navigationIcon  = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Subscription Plans';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Plan Details')
                ->description('Define the subscription plan pricing and duration')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Plan Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., Basic, Pro, Enterprise'),
                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->required()
                        ->numeric()
                        ->prefix('Rs.')
                        ->minValue(0),
                    Forms\Components\TextInput::make('duration')
                        ->label('Duration (days)')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->default(30)
                        ->suffix('days')
                        ->helperText('How many days this plan lasts'),
                ])->columns(3),

            Forms\Components\Section::make('Feature Limits')
                ->description('Set resource limits for this plan. Use 999 for unlimited.')
                ->schema([
                    Forms\Components\TextInput::make('features.products')
                        ->label('Max Products')
                        ->numeric()
                        ->default(10)
                        ->minValue(1)
                        ->helperText('999 = unlimited'),
                    Forms\Components\TextInput::make('features.qr_codes')
                        ->label('Max QR Codes')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->helperText('999 = unlimited'),
                    Forms\Components\TextInput::make('features.branches')
                        ->label('Max Branches')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->helperText('999 = unlimited'),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Plan Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-rectangle-stack'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('PKR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->suffix(' days')
                    ->sortable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('features.products')
                    ->label('Products')
                    ->formatStateUsing(fn ($state): string => $state >= 999 ? '∞' : (string) $state)
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('features.qr_codes')
                    ->label('QR Codes')
                    ->formatStateUsing(fn ($state): string => $state >= 999 ? '∞' : (string) $state)
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('features.branches')
                    ->label('Branches')
                    ->formatStateUsing(fn ($state): string => $state >= 999 ? '∞' : (string) $state)
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('restaurants_count')
                    ->label('Restaurants')
                    ->counts('restaurants')
                    ->sortable()
                    ->color('gray')
                    ->alignCenter()
                    ->icon('heroicon-o-building-storefront'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Subscription $record, Tables\Actions\DeleteAction $action) {
                        if ($record->restaurants()->count() > 0) {
                            $action->cancel();
                            $action->failureNotificationTitle(
                                'Cannot delete: ' . $record->restaurants()->count() . ' restaurants are using this plan.'
                            );
                        }
                    }),
            ])
            ->emptyStateHeading('No subscription plans')
            ->emptyStateDescription('Create subscription plans that restaurants can subscribe to.')
            ->emptyStateIcon('heroicon-o-rectangle-stack');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubscriptionPlans::route('/'),
            'create' => Pages\CreateSubscriptionPlan::route('/create'),
            'edit'   => Pages\EditSubscriptionPlan::route('/{record}/edit'),
        ];
    }
}
