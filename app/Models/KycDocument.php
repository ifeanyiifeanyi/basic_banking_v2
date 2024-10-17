<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycDocument extends Model
{
    use HasFactory;
    protected $fillable = ['kyc_response_id', 'file_path', 'file_type', 'original_filename'];

    public function response()
    {
        return $this->belongsTo(KycResponse::class, 'kyc_response_id');
    }
}
