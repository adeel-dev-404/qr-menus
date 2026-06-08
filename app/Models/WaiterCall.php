<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class WaiterCall extends Model
{
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'table_id',
        'waiter_call_option_id',
        'table_label',
        'call_label',
        'call_icon',
        'status',
        'seen_at',
        'resolved_at',
    ];

    protected $casts = [
        'seen_at'     => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'seen']);
    }

    public function scopeForRestaurant($query, int $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
