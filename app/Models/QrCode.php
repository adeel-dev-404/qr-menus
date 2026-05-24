<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Scopes\RestaurantScope;

class QrCode extends Model
{
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'table_id',
        'token',
        'type',
        'scan_count',
    ];

    protected static function booted()
    {
        // Auto-generate token on creation
        static::creating(function ($qrCode) {
            $qrCode->token = $qrCode->token ?? strtoupper(Str::random(8));
        });

        // Apply global scope
        static::addGlobalScope(new RestaurantScope());
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
}
