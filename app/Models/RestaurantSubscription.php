<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSubscription extends Model
{
    protected $fillable = [
        'restaurant_id', 'subscription_id', 'status',
        'payment_proof', 'transaction_ref', 'amount_paid',
        'starts_at', 'expires_at', 'notes',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'expires_at'  => 'datetime',
        'approved_at' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at?->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'active' && $this->expires_at?->isPast();
    }
}