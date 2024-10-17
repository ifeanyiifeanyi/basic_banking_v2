<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AccountType extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'account_type',
        'code',
        'description',
        'minimum_balance',
        'interest_rate',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'minimum_balance' => 'decimal:2',
        'interest_rate' => 'decimal:2'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['account_type', 'code', 'description', 'minimum_balance', 'interest_rate', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    public function accounts(){
        return $this->hasMany(Account::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
