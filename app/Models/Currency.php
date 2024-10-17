<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = [
        'currency',
        'code',
        'symbol',
        'exchange_rate',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'exchange_rate' => 'decimal:4'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


}
