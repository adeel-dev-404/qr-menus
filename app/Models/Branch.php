<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\RestaurantScope;


class Branch extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'address', 'phone'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function qrCodes()
    {
        return $this->hasMany(QrCode::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new RestaurantScope());
    }
}
