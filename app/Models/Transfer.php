<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'user_id',
        'from_account_id',
        'to_account_number',
        'bank_id',
        'amount',
        'transfer_type',
        'status',
        'meta_data',
        'narration',
        'completed_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->reference = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $model->user_id = request()->user()->id;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
