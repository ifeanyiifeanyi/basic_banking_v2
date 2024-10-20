<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\LoginTracker;
use Spatie\Activitylog\LogOptions;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity, SoftDeletes, LoginTracker;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['username', 'email', 'two_factor_enabled'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function kycResponse()
    {
        return $this->hasOne(KycResponse::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name . ' ' . $this->other_name ?? '';
    }

    public function getPhotoAttribute($value)
    {
        return empty($value) ? asset('users/assets/images/users/avatar-9.jpg') : asset($value);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes' => 'array',

    ];
    /**
     * Get the recovery codes
     *
     * @return array
     */
    public function getRecoveryCodes()
    {
        return $this->two_factor_recovery_codes ?? [];
    }

    /**
     * Set the recovery codes
     *
     * @param array $codes
     * @return void
     */
    public function setRecoveryCodes(array $codes)
    {
        $this->two_factor_recovery_codes = $codes;
    }

    /**
     * Use a recovery code
     *
     * @param string $code
     * @return bool
     */
    public function useRecoveryCode(string $code)
    {
        $codes = $this->getRecoveryCodes();
        $position = array_search($code, $codes);

        if ($position !== false) {
            unset($codes[$position]);
            $this->setRecoveryCodes(array_values($codes));
            $this->save();
            return true;
        }

        return false;
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'account_status' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'two_factor_secret' => 'string',
            'two_factor_recovery_codes' => 'array',
            'can_transfer' => 'boolean',
            'can_receive' => 'boolean',
            'is_archived' => 'boolean',
            'kyc_status' => 'string',
            'kyc_verified_at' => 'datetime',
            'archived_at' => 'datetime',
            'last_login' => 'datetime'
        ];
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    // In User model
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
