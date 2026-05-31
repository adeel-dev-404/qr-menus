<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// class Order extends Model
// {

//     protected $fillable = [
//         'order_number',
//         'restaurant_id',
//         'branch_id',
//         'table_id',
//         'customer_name',
//         'customer_phone',
//         'customer_email',
//         'type',
//         'status',
//         'payment_method',
//         'payment_status',
//         'payment_reference',
//         'payment_proof',
//         'subtotal',
//         'discount',
//         'total',
//         'customer_notes',
//         'kitchen_notes',
//     ];

//     protected static function booted(): void
//     {
//         static::addGlobalScope(new \App\Models\Scopes\RestaurantScope());
//     }

//     public function restaurant()
//     {
//         return $this->belongsTo(Restaurant::class);
//     }
//     public function branch()
//     {
//         return $this->belongsTo(Branch::class);
//     }
//     public function table()
//     {
//         return $this->belongsTo(Table::class);
//     }
//     public function items()
//     {
//         return $this->hasMany(OrderItem::class);
//     }

//     public static function generateNumber(int $restaurantId): string
//     {
//         $count = static::withoutGlobalScopes()
//             ->where('restaurant_id', $restaurantId)
//             ->count() + 1;
//         return 'ORD-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
//     }

//     public function getStatusColorAttribute(): string
//     {
//         return match ($this->status) {
//             'pending'   => 'yellow',
//             'confirmed' => 'blue',
//             'preparing' => 'orange',
//             'ready'     => 'green',
//             'completed' => 'gray',
//             'cancelled' => 'red',
//             default     => 'gray',
//         };
//     }

//     public function getStatusLabelAttribute(): string
//     {
//         return match ($this->status) {
//             'pending'   => '⏳ Pending',
//             'confirmed' => '✅ Confirmed',
//             'preparing' => '👨‍🍳 Preparing',
//             'ready'     => '🔔 Ready',
//             'completed' => '✓ Completed',
//             'cancelled' => '✗ Cancelled',
//             default     => ucfirst($this->status),
//         };
//     }
// }

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'restaurant_id',
        'branch_id',
        'table_number',
        'order_type',
        'status',
        'payment_method',
        'payment_status',
        'payment_ref',
        'payment_proof',
        'subtotal',
        'total',
        'customer_name',
        'customer_phone',
        'notes',
        'confirmed_at',
        'ready_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'ready_at'     => 'datetime',
        'subtotal'     => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateNumber(int $restaurantId): string
    {
        $count = static::where('restaurant_id', $restaurantId)
            ->whereDate('created_at', today())->count() + 1;
        return 'ORD-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'yellow',
            'confirmed'  => 'blue',
            'preparing'  => 'orange',
            'ready'      => 'green',
            'completed'  => 'gray',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '⏳ Pending',
            'confirmed' => '✅ Confirmed',
            'preparing' => '👨‍🍳 Preparing',
            'ready'     => '🔔 Ready',
            'completed' => '✓ Completed',
            'cancelled' => '✗ Cancelled',
            default     => ucfirst($this->status),
        };
    }
}
