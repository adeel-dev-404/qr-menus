<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Restaurant extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'phone',
        'email',
        'address',
        'status',
        'subscription_id',
        'active_subscription_id',
        'subscription_expires_at',
    ];
    protected $casts = [
        'subscription_expires_at' => 'datetime',
    ];

    // Auto-generate slug from name
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    public function owner()
    {
        return $this->hasOne(User::class)->whereHas('roles', function ($q) {
            $q->where('name', 'restaurant_owner');
        });
    }
    public function activeSubscription()
    {
        return $this->belongsTo(RestaurantSubscription::class, 'active_subscription_id');
    }

    public function restaurantSubscriptions()
    {
        return $this->hasMany(RestaurantSubscription::class);
    }

    public function subscriptionDaysLeft(): int
    {
        if (!$this->subscription_expires_at) return 0;
        return max(0, (int) now()->diffInDays($this->subscription_expires_at, false));
    }
    public function hasActiveSubscription(): bool
    {
        // If column doesn't exist yet or is null, return false (not throw error)
        if (!isset($this->subscription_expires_at)) {
            return false;
        }

        return $this->subscription_expires_at?->isFuture() ?? false;
    }

    public function isOnFreePlan(): bool
    {
        return is_null($this->active_subscription_id);
    }
    public function currentPlanId(): ?int
    {
        if (!$this->hasActiveSubscription()) return null;
        return $this->subscription_id;
    }
    // Get the feature limits for the current plan
    public function planFeatures(): array
    {
        if (!$this->subscription_id) {
            // Free plan defaults
            return ['products' => 10, 'qr_codes' => 1, 'branches' => 1];
        }

        $subscription = \App\Models\Subscription::find($this->subscription_id);
        return $subscription?->features ?? ['products' => 10, 'qr_codes' => 1, 'branches' => 1];
    }

    // Check if restaurant can add more of a given resource
    public function canAdd(string $resource): bool
    {
        $features = $this->planFeatures();
        $limit    = $features[$resource] ?? 999;

        // 999 = unlimited
        if ($limit >= 999) return true;

        $current = match ($resource) {
            'products'  => $this->products()->count(),
            'qr_codes'  => $this->qrCodes()->count(),
            'branches'  => $this->branches()->count(),
            default     => 0,
        };

        return $current < $limit;
    }

    // Get the limit for a resource
    public function limitFor(string $resource): int
    {
        return $this->planFeatures()[$resource] ?? 999;
    }

    // Get current count for a resource
    public function countOf(string $resource): int
    {
        return match ($resource) {
            'products'  => $this->products()->count(),
            'qr_codes'  => $this->qrCodes()->count(),
            'branches'  => $this->branches()->count(),
            default     => 0,
        };
    }
}
