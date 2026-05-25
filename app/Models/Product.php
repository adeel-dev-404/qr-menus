<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Scopes\RestaurantScope;

class Product extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'is_available',
    ];

    protected $casts = [
        'is_available'   => 'boolean',
        'price'          => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new RestaurantScope());
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('image') ?: asset('images/placeholder.png');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Final price after discount
    public function getFinalPriceAttribute(): float
    {
        return $this->discount_price ?? $this->price;
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
    // Add relationship
    // public function variants()
    // {
    //     return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    // }
    public function variants()
    {
        return $this->hasMany(\App\Models\ProductVariant::class)->orderBy('sort_order');
    }

    // Check if product has variants
    // public function hasVariants(): bool
    // {
    //     return $this->variants()->exists();
    // }
    public function hasVariants(): bool
    {
        // Use loaded relationship if available to avoid extra query
        if ($this->relationLoaded('variants')) {
            return $this->variants->isNotEmpty();
        }
        return $this->variants()->exists();
    }
    // Get starting price (lowest variant or base price)
    public function getStartingPriceAttribute(): float
    {
        if ($this->hasVariants()) {
            return (float) $this->variants()
                ->where('is_available', true)
                ->min('price') ?? $this->price;
        }
        return (float) ($this->discount_price ?? $this->price);
    }
}
