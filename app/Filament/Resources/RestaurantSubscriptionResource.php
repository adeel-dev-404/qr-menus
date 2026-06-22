<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantSubscriptionResource\Pages;
use App\Models\RestaurantSubscription;
use App\Services\SubscriptionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RestaurantSubscriptionResource extends Resource
{
    protected static ?string $model = RestaurantSubscription::class;
    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Payment Requests';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int    $navigationSort  = 1;

    // Show badge count for pending
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Payment Details')
                ->schema([
                    Forms\Components\TextInput::make('transaction_ref')
                        ->label('Transaction Reference')
                        ->disabled()
                        ->prefix('#'),
                    Forms\Components\TextInput::make('paymentMethod.name')
                        ->label('Payment Method')
                        ->disabled()
                        ->placeholder('N/A')
                        ->prefix('💳'),
                    Forms\Components\TextInput::make('amount_paid')
                        ->label('Amount Paid')
                        ->disabled()
                        ->prefix('Rs.'),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending'  => '⏳ Pending',
                            'active'   => '✅ Active',
                            'rejected' => '❌ Rejected',
                            'expired'  => '⏰ Expired',
                        ])
                        ->native(false),
                    Forms\Components\Textarea::make('notes')
                        ->label('Admin Notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(4),

            Forms\Components\Section::make('Payment Proof')
                ->schema([
                    Forms\Components\ViewField::make('payment_proof_preview')
                        ->view('filament.forms.payment-proof-preview')
                        ->columnSpanFull(),
                ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Restaurant')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-o-building-storefront')
                    ->description(fn (RestaurantSubscription $record): ?string =>
                        $record->restaurant?->owner?->email
                    ),

                Tables\Columns\TextColumn::make('subscription.name')
                    ->label('Plan')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-credit-card'),

                Tables\Columns\TextColumn::make('subscription_period.billing_cycle')
                    ->label('Period')
                    ->formatStateUsing(fn ($state) => \App\Models\SubscriptionPeriod::CYCLE_LABELS[$state] ?? '—')
                    ->badge()
                    ->color('purple')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_trial')
                    ->label('Trial')
                    ->boolean()
                    ->trueIcon('heroicon-o-gift')
                    ->trueColor('success')
                    ->falseIcon('heroicon-o-minus')
                    ->falseColor('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Amount')
                    ->money('PKR')
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('transaction_ref')
                    ->label('Reference')
                    ->fontFamily('mono')
                    ->color('gray')
                    ->copyable()
                    ->limit(15),

                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->label('Method')
                    ->badge()
                    ->color('primary')
                    ->placeholder('N/A')
                    ->icon('heroicon-o-credit-card'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger'  => 'rejected',
                        'gray'    => 'expired',
                    ]),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Proof')
                    ->disk('public')
                    ->height(40)
                    ->width(60)
                    ->circular(false)
                    ->extraAttributes(['class' => 'rounded-lg cursor-pointer']),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(30)
                    ->tooltip(fn (RestaurantSubscription $record): ?string => $record->notes)
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Approved')
                    ->dateTime('M d, Y H:i')
                    ->color('gray')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => '⏳ Pending',
                        'active'   => '✅ Active',
                        'rejected' => '❌ Rejected',
                        'expired'  => '⏰ Expired',
                    ])
                    ->multiple()
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (RestaurantSubscription $r) => $r->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Payment')
                    ->modalDescription(fn (RestaurantSubscription $r): string =>
                        "Approve payment of Rs." . number_format($r->amount_paid, 0) . " from \"{$r->restaurant?->name}\" for the \"{$r->subscription?->name}\" plan?"
                    )
                    ->modalIcon('heroicon-o-check-circle')
                    ->action(function (RestaurantSubscription $record) {
                        (new SubscriptionService())->approve($record);
                    })
                    ->successNotificationTitle('Subscription activated! ✅'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (RestaurantSubscription $r) => $r->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3)
                            ->placeholder('Explain why this payment is being rejected...'),
                    ])
                    ->action(function (RestaurantSubscription $record, array $data) {
                        (new SubscriptionService())->reject($record, $data['reason']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Reject Payment')
                    ->modalIcon('heroicon-o-x-circle'),

                Tables\Actions\ViewAction::make()
                    ->color('gray'),
            ])
            ->emptyStateHeading('No payment requests')
            ->emptyStateDescription('Payment requests from restaurants will appear here.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestaurantSubscriptions::route('/'),
        ];
    }
}