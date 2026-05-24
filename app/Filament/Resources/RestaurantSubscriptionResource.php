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
    protected static ?string $navigationGroup = 'Platform';
    protected static ?int    $navigationSort  = 4;

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
            Forms\Components\TextInput::make('transaction_ref')->disabled(),
            Forms\Components\TextInput::make('amount_paid')->disabled()->prefix('Rs.'),
            Forms\Components\Select::make('status')
                ->options([
                    'pending'  => 'Pending',
                    'active'   => 'Active',
                    'rejected' => 'Rejected',
                    'expired'  => 'Expired',
                ]),
            Forms\Components\Textarea::make('notes')->label('Admin Notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('restaurant.name')
                    ->label('Restaurant')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('subscription.name')
                    ->label('Plan')->badge()->color('info'),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Amount')->money('PKR'),
                Tables\Columns\TextColumn::make('transaction_ref')
                    ->label('Ref')->fontFamily('mono')->color('gray'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger'  => 'rejected',
                        'gray'    => 'expired',
                    ]),
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Proof')->disk('public')->height(40)->width(60),
                Tables\Columns\TextColumn::make('created_at')->label('Submitted')->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'active'   => 'Active',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (RestaurantSubscription $r) => $r->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (RestaurantSubscription $record) {
                        (new SubscriptionService())->approve($record);
                    })
                    ->successNotificationTitle('Subscription activated!'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (RestaurantSubscription $r) => $r->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->required(),
                    ])
                    ->action(function (RestaurantSubscription $record, array $data) {
                        (new SubscriptionService())->reject($record, $data['reason']);
                    })
                    ->requiresConfirmation(),

                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestaurantSubscriptions::route('/'),
            // 'view'  => Pages\ViewRestaurantSubscription::route('/{record}'),
        ];
    }
}