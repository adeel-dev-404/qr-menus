<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestaurantResource\Pages;
use App\Models\Restaurant;
use App\Models\Subscription;
use App\Services\RestaurantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;
    protected static ?string $navigationIcon  = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Platform';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Restaurant Info')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->maxLength(20),
                Forms\Components\Textarea::make('address')
                    ->rows(2)->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Select::make('subscription_id')
                    ->label('Subscription Plan')
                    ->options(Subscription::pluck('name', 'id'))
                    ->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Owner Account')
                ->description('Fill this only when creating a new restaurant.')
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
                ])->columns(3)->hiddenOn('edit'),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()->color('gray'),
                Tables\Columns\TextColumn::make('subscription.name')
                    ->label('Plan')
                    ->badge()
                    ->color('info'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ]),
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'active'   => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('subscription_id')
                    ->label('Plan')
                    ->options(Subscription::pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Restaurant $r) => $r->status !== 'active')
                    ->action(fn (Restaurant $r) => $r->update(['status' => 'active']))
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('suspend')
                    ->label('Suspend')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Restaurant $r) => $r->status === 'active')
                    ->action(fn (Restaurant $r) => $r->update(['status' => 'inactive']))
                    ->requiresConfirmation(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRestaurants::route('/'),
            'create' => Pages\CreateRestaurant::route('/create'),
            'edit'   => Pages\EditRestaurant::route('/{record}/edit'),
        ];
    }

    // Handle owner creation on restaurant create
    protected function handleRecordCreation(array $data): Restaurant
    {
        $service = new RestaurantService();

        return $service->createWithOwner(
            array_except($data, ['owner_name', 'owner_email', 'owner_password']),
            [
                'name'     => $data['owner_name'],
                'email'    => $data['owner_email'],
                'password' => $data['owner_password'],
            ]
        );
    }
}