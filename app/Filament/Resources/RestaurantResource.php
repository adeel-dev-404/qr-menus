<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantResource\Pages;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Mail\RestaurantApprovedMail;
use App\Mail\RestaurantSuspendedMail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Platform';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Restaurant')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Restaurant Info')
                        ->icon('heroicon-o-building-storefront')
                        ->schema([
                            Forms\Components\Section::make()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('slug')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->columnSpan(1)
                                        ->helperText('Auto-generated from name'),
                                    Forms\Components\TextInput::make('email')
                                        ->email()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('phone')
                                        ->maxLength(20)
                                        ->tel(),
                                    Forms\Components\Textarea::make('address')
                                        ->rows(2)
                                        ->columnSpanFull(),
                                    Forms\Components\Textarea::make('about')
                                        ->rows(3)
                                        ->columnSpanFull()
                                        ->helperText('Restaurant description shown on the public menu'),
                                ])->columns(2),

                            Forms\Components\Section::make('Status & Plan')
                                ->schema([
                                    Forms\Components\Select::make('status')
                                        ->options([
                                            'pending'  => '⏳ Pending',
                                            'active'   => '✅ Active',
                                            'inactive' => '🚫 Inactive',
                                        ])
                                        ->default('pending')
                                        ->required()
                                        ->native(false),
                                    Forms\Components\Select::make('subscription_id')
                                        ->label('Subscription Plan')
                                        ->options(Subscription::pluck('name', 'id'))
                                        ->nullable()
                                        ->native(false)
                                        ->searchable(),
                                    Forms\Components\DateTimePicker::make('subscription_expires_at')
                                        ->label('Subscription Expires')
                                        ->nullable(),
                                ])->columns(3),
                        ]),

                    Forms\Components\Tabs\Tab::make('Features & Settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Section::make('Feature Toggles')
                                ->description('Enable or disable restaurant features')
                                ->schema([
                                    Forms\Components\Toggle::make('ordering_enabled')
                                        ->label('Online Ordering')
                                        ->helperText('Allow customers to place orders from the QR menu')
                                        ->default(true),
                                    Forms\Components\Toggle::make('waiter_call_enabled')
                                        ->label('Waiter Call')
                                        ->helperText('Allow customers to call a waiter from the QR menu')
                                        ->default(false),
                                ])->columns(2),

                            Forms\Components\Section::make('Social & Contact')
                                ->schema([
                                    Forms\Components\TextInput::make('whatsapp')
                                        ->label('WhatsApp')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('instagram')
                                        ->label('Instagram')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('facebook')
                                        ->label('Facebook')
                                        ->maxLength(255),
                                ])->columns(3),

                            Forms\Components\Section::make('Payment Methods')
                                ->schema([
                                    Forms\Components\TextInput::make('jazzcash_number')
                                        ->label('JazzCash Number'),
                                    Forms\Components\TextInput::make('easypaisa_number')
                                        ->label('Easypaisa Number'),
                                    Forms\Components\TextInput::make('whatsapp_number')
                                        ->label('WhatsApp Payment Number'),
                                ])->columns(3),
                        ]),

                    Forms\Components\Tabs\Tab::make('Owner Account')
                        ->icon('heroicon-o-user-plus')
                        ->hiddenOn('edit')
                        ->schema([
                            Forms\Components\Section::make()
                                ->description('An invitation email will be sent to the owner to set up their account.')
                                ->schema([
                                    Forms\Components\TextInput::make('owner_name')
                                        ->label('Owner Name')
                                        ->requiredWith('owner_email'),
                                    Forms\Components\TextInput::make('owner_email')
                                        ->label('Owner Email')
                                        ->email()
                                        ->unique('users', 'email'),
                                    Forms\Components\TextInput::make('owner_password')
                                        ->label('Owner Password')
                                        ->password()
                                        ->minLength(8),
                                ])->columns(3),
                        ]),

                    Forms\Components\Tabs\Tab::make('Usage Stats')
                        ->icon('heroicon-o-chart-bar')
                        ->hiddenOn('create')
                        ->schema([
                            Forms\Components\Section::make('Current Usage')
                                ->schema([
                                    Forms\Components\Placeholder::make('products_count')
                                        ->label('Products')
                                        ->content(fn (?Restaurant $record): string =>
                                            $record ? $record->products()->count() . ' / ' . ($record->limitFor('products') >= 999 ? '∞' : $record->limitFor('products')) : '—'
                                        ),
                                    Forms\Components\Placeholder::make('categories_count')
                                        ->label('Categories')
                                        ->content(fn (?Restaurant $record): string =>
                                            $record ? (string) $record->categories()->count() : '—'
                                        ),
                                    Forms\Components\Placeholder::make('qr_codes_count')
                                        ->label('QR Codes')
                                        ->content(fn (?Restaurant $record): string =>
                                            $record ? $record->qrCodes()->count() . ' / ' . ($record->limitFor('qr_codes') >= 999 ? '∞' : $record->limitFor('qr_codes')) : '—'
                                        ),
                                    Forms\Components\Placeholder::make('branches_count')
                                        ->label('Branches')
                                        ->content(fn (?Restaurant $record): string =>
                                            $record ? $record->branches()->count() . ' / ' . ($record->limitFor('branches') >= 999 ? '∞' : $record->limitFor('branches')) : '—'
                                        ),
                                    Forms\Components\Placeholder::make('orders_count')
                                        ->label('Total Orders')
                                        ->content(fn (?Restaurant $record): string =>
                                            $record ? number_format($record->orders()->count()) : '—'
                                        ),
                                    Forms\Components\Placeholder::make('staff_count')
                                        ->label('Staff Members')
                                        ->content(fn (?Restaurant $record): string =>
                                            $record ? (string) $record->users()->count() : '—'
                                        ),
                                ])->columns(3),
                        ]),
                ])
                ->columnSpanFull()
                ->persistTabInQueryString(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn (Restaurant $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=e8502a&color=fff&size=80'
                    )
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('Restaurant')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Restaurant $record): ?string => $record->email),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->color('gray')
                    ->default('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subscription.name')
                    ->label('Plan')
                    ->badge()
                    ->color('info')
                    ->default('Free')
                    ->icon('heroicon-o-credit-card'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ]),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->sortable()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('subscription_expires_at')
                    ->label('Expires')
                    ->date('M d, Y')
                    ->sortable()
                    ->color(fn (?Restaurant $record): string =>
                        $record?->subscription_expires_at?->isPast() ? 'danger' : 'gray'
                    )
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->since()
                    ->sortable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => '⏳ Pending',
                        'active'   => '✅ Active',
                        'inactive' => '🚫 Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('subscription_id')
                    ->label('Plan')
                    ->options(Subscription::pluck('name', 'id'))
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('ordering_enabled')
                    ->label('Ordering'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view_menu')
                        ->label('View Menu ↗')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(fn (Restaurant $r) => url('/r/' . $r->slug))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Restaurant $r) => $r->status !== 'active')
                        ->action(function (Restaurant $r) {
                            $r->update(['status' => 'active']);

                            // Notify the restaurant owner
                            $ownerEmail = $r->users()
                                ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
                                ->value('email');

                            if ($ownerEmail) {
                                Mail::to($ownerEmail)->send(new RestaurantApprovedMail($r));
                            }
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Restaurant $r) => $r->status === 'active')
                        ->action(function (Restaurant $r) {
                            $r->update(['status' => 'inactive']);

                            // Notify the restaurant owner
                            $ownerEmail = $r->users()
                                ->whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))
                                ->value('email');

                            if ($ownerEmail) {
                                Mail::to($ownerEmail)->send(new RestaurantSuspendedMail($r));
                            }
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate_selected')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'active'])),
                    Tables\Actions\BulkAction::make('suspend_selected')
                        ->label('Suspend Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive'])),
                ]),
            ])
            ->emptyStateHeading('No restaurants yet')
            ->emptyStateDescription('Restaurants will appear here once owners sign up.')
            ->emptyStateIcon('heroicon-o-building-storefront');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit'   => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'phone'];
    }
}
