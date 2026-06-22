<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;
    protected static ?string $navigationIcon  = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payment Methods';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int    $navigationSort  = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Payment Method Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Method Name')
                        ->required()
                        ->placeholder('e.g., Easypaisa, JazzCash, Allied Bank')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('account_title')
                        ->label('Account Title')
                        ->required()
                        ->placeholder('e.g., Adeel Ahmed')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('account_number')
                        ->label('Account Number / IBAN')
                        ->required()
                        ->placeholder('e.g., 03448371946')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Is Active')
                        ->default(true)
                        ->inline(false),
                    Forms\Components\Textarea::make('instructions')
                        ->label('Payment Instructions')
                        ->placeholder('Instructions for the user on how to pay...')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Method Name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_title')
                    ->label('Account Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->label('Account Number / IBAN')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No payment methods yet')
            ->emptyStateDescription('Create payment methods that users can select during checkout.')
            ->emptyStateIcon('heroicon-o-credit-card');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
