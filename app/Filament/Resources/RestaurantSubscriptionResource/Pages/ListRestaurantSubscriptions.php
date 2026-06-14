<?php

namespace App\Filament\Resources\RestaurantSubscriptionResource\Pages;

use App\Filament\Resources\RestaurantSubscriptionResource;
use App\Models\RestaurantSubscription;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListRestaurantSubscriptions extends ListRecords
{
    protected static string $resource = RestaurantSubscriptionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(RestaurantSubscription::count())
                ->badgeColor('gray'),
            'pending' => Tab::make('Pending')
                ->badge(RestaurantSubscription::where('status', 'pending')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),
            'active' => Tab::make('Active')
                ->badge(RestaurantSubscription::where('status', 'active')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active')),
            'rejected' => Tab::make('Rejected')
                ->badge(RestaurantSubscription::where('status', 'rejected')->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
            'expired' => Tab::make('Expired')
                ->badge(RestaurantSubscription::where('status', 'expired')->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'expired')),
        ];
    }
}
