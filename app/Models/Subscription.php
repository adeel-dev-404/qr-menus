<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['name', 'price', 'duration', 'features'];

    protected $casts = [
        'features' => 'array',
        'price'    => 'decimal:2',
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}