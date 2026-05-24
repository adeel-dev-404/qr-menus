<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $fillable = [
        'restaurant_id', 'qr_code_id', 'ip_address', 'device', 'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }
}