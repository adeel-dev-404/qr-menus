<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPlanResource\Pages;
use App\Models\Subscription;
use App\Models\SubscriptionPeriod;
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

            // ── Plan Identity ──────────────────────────────────────────────────
            Forms\Components\Section::make('Plan Details')
                ->description('Define the plan tier, features, and visibility')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Plan Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., Starter, Professional, Enterprise'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Lower numbers display first'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Visible to Restaurants')
                        ->helperText('Inactive plans are hidden on the subscription page')
                        ->default(true)
                        ->inline(false),
                ])->columns(3),

            // ── Free Trial ─────────────────────────────────────────────────────
            Forms\Components\Section::make('Free Trial')
                ->description('Set a free trial duration for this plan. Leave 0 for no trial.')
                ->schema([
                    Forms\Components\TextInput::make('trial_days')
                        ->label('Trial Duration (days)')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->suffix('days')
                        ->helperText('0 = No free trial. New restaurants automatically receive a trial on the plan with the highest trial_days value.'),
                ])->columns(1),

            // ── Feature Limits ─────────────────────────────────────────────────
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

            // ── Billing Periods ────────────────────────────────────────────────
            Forms\Components\Section::make('Billing Periods & Pricing')
                ->description('Define prices for each billing cycle. Add as many cycles as needed.')
                ->schema([
                    Forms\Components\Repeater::make('periods')
                        ->relationship('periods')
                        ->label('')
                        ->schema([
                            Forms\Components\Select::make('billing_cycle')
                                ->label('Billing Cycle')
                                ->options(SubscriptionPeriod::CYCLE_LABELS)
                                ->required()
                                ->native(false)
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    $days = SubscriptionPeriod::CYCLE_DAYS[$state] ?? null;
                                    if ($days) {
                                        $set('duration_days', $days);
                                    }
                                }),

                            Forms\Components\TextInput::make('price')
                                ->label('Price')
                                ->numeric()
                                ->prefix('Rs.')
                                ->minValue(0)
                                ->required(fn (Forms\Get $get) => $get('billing_cycle') === 'monthly')
                                ->disabled(fn (Forms\Get $get) => $get('billing_cycle') !== 'monthly' && $get('billing_cycle') !== null)
                                ->dehydrated()
                                ->placeholder(fn (Forms\Get $get) => $get('billing_cycle') !== 'monthly' ? 'Auto-calculated' : ''),

                            Forms\Components\TextInput::make('discount_percent')
                                ->label('Discount %')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffix('%')
                                ->required(fn (Forms\Get $get) => $get('billing_cycle') !== 'monthly' && $get('billing_cycle') !== null)
                                ->disabled(fn (Forms\Get $get) => $get('billing_cycle') === 'monthly')
                                ->dehydrated()
                                ->placeholder(fn (Forms\Get $get) => $get('billing_cycle') === 'monthly' ? '0' : ''),

                            Forms\Components\TextInput::make('duration_days')
                                ->label('Duration (days)')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->suffix('days')
                                ->helperText('Auto-filled on cycle select'),

                            Forms\Components\TextInput::make('sort_order')
                                ->label('Sort Order')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),
                        ])
                        ->columns(5)
                        ->addActionLabel('+ Add Billing Period')
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string =>
                            isset($state['billing_cycle'])
                                ? (SubscriptionPeriod::CYCLE_LABELS[$state['billing_cycle']] ?? $state['billing_cycle'])
                                  . ' — Rs. ' . number_format($state['price'] ?? 0, 0)
                                : null
                        ),
                ]),
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
                    ->icon('heroicon-o-rectangle-stack')
                    ->description(fn (Subscription $r): string =>
                        $r->trial_days > 0 ? "🎁 {$r->trial_days}-day free trial" : 'No free trial'
                    ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('trial_days')
                    ->label('Trial')
                    ->formatStateUsing(fn ($state): string => $state > 0 ? "{$state} days" : '—')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('periods_summary')
                    ->label('Periods & Prices')
                    ->state(fn (Subscription $record): string =>
                        $record->periods->map(fn ($p) =>
                            SubscriptionPeriod::CYCLE_LABELS[$p->billing_cycle] . ': Rs.' . number_format($p->price, 0)
                        )->join(' | ') ?: '—'
                    )
                    ->color('gray')
                    ->wrap(),

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
