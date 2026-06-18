<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTranslations;

class WaiterCallOption extends Model
{
    use HasTranslations;
    protected $fillable = [
        'restaurant_id',
        'label',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
