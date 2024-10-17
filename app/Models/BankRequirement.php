<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankRequirement extends Model
{
    use HasFactory;

    protected $fillable = ['bank_id', 'field_name', 'field_type', 'field_options', 'is_required', 'description', 'order'];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
