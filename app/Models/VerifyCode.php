<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyCode extends Model
{
    use HasFactory;

    protected $fillable =[
        'phone',
        'code_hash',
        'expires_at',
        'verified_at'
    ];

}
