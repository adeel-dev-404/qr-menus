<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'restaurant_id',
        'branch_id',
        'table_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'type',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_proof',
        'status',
        'subtotal',
        'total',
        'notes',
        'confirmed_at',
        'ready_at',
    ];

    protected $casts = [
        'subtotal'     => 'decimal:2',
        'total'        => 'decimal:2',
        'confirmed_at' => 'datetime',
        'ready_at'     => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->order_number = self::generateNumber($order->restaurant_id);
        });
    }

    private static function generateNumber(int $restaurantId): string
    {
        $count = static::query()->where('restaurant_id', $restaurantId)->count() + 1;
        return 'ORD-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'yellow',
            'confirmed' => 'blue',
            'preparing' => 'orange',
            'ready'     => 'green',
            'delivered' => 'gray',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '⏳ Pending',
            'confirmed' => '✅ Confirmed',
            'preparing' => '👨‍🍳 Preparing',
            'ready'     => '🔔 Ready',
            'delivered' => '✓ Delivered',
            'cancelled' => '✗ Cancelled',
            default     => ucfirst($this->status),
        };
    }

    public function scopeForRestaurant($query, int $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['delivered', 'cancelled']);
    }
}
