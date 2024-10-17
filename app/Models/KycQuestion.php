<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'response_type', 'options', 'is_required', 'order'];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function responses()
    {
        return $this->hasMany(KycResponse::class);
    }
}
