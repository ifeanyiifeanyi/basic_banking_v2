<?php

namespace App\Models;

use App\Models\BankRequirement;
use App\Models\BankTransaction;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'code', 'swift_code', 'is_active', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'code', 'swift_code', 'is_active', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function requirements()
    {
        return $this->hasMany(BankRequirement::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
