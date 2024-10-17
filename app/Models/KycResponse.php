<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycResponse extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'kyc_question_id', 'text_response'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(KycQuestion::class, 'kyc_question_id');
    }

    public function documents()
    {
        return $this->hasMany(KycDocument::class);
    }
}
