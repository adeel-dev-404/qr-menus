<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPeriod extends Model
{
    protected static function booted()
    {
        static::saving(function (SubscriptionPeriod $period) {
            $period->calculatePriceFromDiscount();
        });

        static::saved(function (SubscriptionPeriod $period) {
            if ($period->billing_cycle === 'monthly' && $period->wasChanged('price')) {
                $period->recalculateSiblings();
            }
        });
    }

    public function calculatePriceFromDiscount()
    {
        if ($this->billing_cycle === 'monthly') {
            return;
        }

        $monthlyPeriod = null;
        if ($this->subscription) {
            $monthlyPeriod = $this->subscription->periods->firstWhere('billing_cycle', 'monthly');
        }

        if (!$monthlyPeriod && $this->subscription_id) {
            $monthlyPeriod = self::where('subscription_id', $this->subscription_id)
                ->where('billing_cycle', 'monthly')
                ->first();
        }

        if ($monthlyPeriod) {
            $months = match ($this->billing_cycle) {
                'quarterly'   => 3,
                'half_yearly' => 6,
                'yearly'      => 12,
                default       => 1,
            };

            $discount = $this->discount_percent ?? 0;
            $basePrice = $monthlyPeriod->price * $months;
            $this->price = round($basePrice * (1 - ($discount / 100)), 2);
        }
    }

    public function recalculateSiblings()
    {
        $siblings = self::where('subscription_id', $this->subscription_id)
            ->where('billing_cycle', '!=', 'monthly')
            ->get();

        foreach ($siblings as $sibling) {
            $months = match ($sibling->billing_cycle) {
                'quarterly'   => 3,
                'half_yearly' => 6,
                'yearly'      => 12,
                default       => 1,
            };
            $discount = $sibling->discount_percent ?? 0;
            $basePrice = $this->price * $months;
            $sibling->price = round($basePrice * (1 - ($discount / 100)), 2);
            $sibling->saveQuietly();
        }
    }

    protected $fillable = [
        'subscription_id',
        'billing_cycle',
        'duration_days',
        'price',
        'discount_percent',
        'sort_order',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'duration_days'    => 'integer',
        'discount_percent' => 'integer',
        'sort_order'       => 'integer',
    ];

    // Ordered labels for UI display
    const CYCLE_LABELS = [
        'monthly'     => 'Monthly',
        'quarterly'   => '3 Months',
        'half_yearly' => '6 Months',
        'yearly'      => 'Yearly',
    ];

    // Default duration in days per cycle
    const CYCLE_DAYS = [
        'monthly'     => 30,
        'quarterly'   => 90,
        'half_yearly' => 180,
        'yearly'      => 365,
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function restaurantSubscriptions()
    {
        return $this->hasMany(RestaurantSubscription::class);
    }

    /** Human-readable billing cycle label */
    public function billingLabel(): string
    {
        return self::CYCLE_LABELS[$this->billing_cycle] ?? ucfirst($this->billing_cycle);
    }

    /** Per-month equivalent price (for display) */
    public function perMonthPrice(): float
    {
        $months = match ($this->billing_cycle) {
            'monthly'     => 1,
            'quarterly'   => 3,
            'half_yearly' => 6,
            'yearly'      => 12,
            default       => 1,
        };

        return round($this->price / $months, 0);
    }

    /** Savings percentage vs monthly (if monthly period exists) */
    public function savingsPercent(?self $monthlyPeriod): ?int
    {
        if ($this->discount_percent !== null) {
            return $this->discount_percent > 0 ? (int) $this->discount_percent : null;
        }

        if (!$monthlyPeriod || $this->billing_cycle === 'monthly') {
            return null;
        }

        $months = match ($this->billing_cycle) {
            'quarterly'   => 3,
            'half_yearly' => 6,
            'yearly'      => 12,
            default       => 1,
        };

        $fullPrice = $monthlyPeriod->price * $months;
        if ($fullPrice <= 0) return null;

        return (int) round((1 - ($this->price / $fullPrice)) * 100);
    }
}
