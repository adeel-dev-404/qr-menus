<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name',
        'price',      // kept for backward compatibility; pricing now lives in periods
        'duration',   // kept for backward compatibility
        'features',
        'trial_days',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features'   => 'array',
        'price'      => 'decimal:2',
        'trial_days' => 'integer',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────────

    /** All billing period variants for this plan */
    public function periods()
    {
        return $this->hasMany(SubscriptionPeriod::class)->orderBy('sort_order');
    }

    /** All restaurants currently on this plan */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    /** Whether this plan offers a free trial */
    public function hasTrial(): bool
    {
        return ($this->trial_days ?? 0) > 0;
    }

    /** Get the monthly period (for savings calculation) */
    public function monthlyPeriod(): ?SubscriptionPeriod
    {
        return $this->periods->firstWhere('billing_cycle', 'monthly');
    }
}