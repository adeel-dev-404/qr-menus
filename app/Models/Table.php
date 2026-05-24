<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['branch_id', 'table_number', 'capacity'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}