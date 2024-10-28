<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('is_suspended', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
