<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSubscription extends Model
{
    protected $fillable = [
        'restaurant_id', 'subscription_id', 'subscription_period_id', 'status',
        'payment_proof', 'transaction_ref', 'amount_paid',
        'starts_at', 'expires_at', 'notes', 'is_trial',
        'approved_by', 'approved_at',
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'expires_at'  => 'datetime',
        'approved_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'is_trial'    => 'boolean',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /** The billing period (monthly, quarterly, etc.) chosen at checkout */
    public function period()
    {
        return $this->belongsTo(SubscriptionPeriod::class, 'subscription_period_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at?->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->status === 'active' && $this->expires_at?->isPast();
    }

    public function isTrial(): bool
    {
        return (bool) $this->is_trial;
    }

    /** Billing cycle label for display (e.g. "Monthly", "Yearly") */
    public function cycleLabel(): string
    {
        return $this->period?->billingLabel() ?? '—';
    }
}