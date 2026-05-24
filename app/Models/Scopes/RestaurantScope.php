<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class RestaurantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply scope inside dashboard (not super admin)
        if (auth()->check() && ! auth()->user()->hasRole('super_admin')) {
            $builder->where(
                $model->getTable() . '.restaurant_id',
                auth()->user()->restaurant_id
            );
        }
    }
}