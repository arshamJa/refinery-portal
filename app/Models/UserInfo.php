<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInfo extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        'work_phone',
        'house_phone',
        'phone',
        'n_code',
        'full_name',
        'position',
        'signature'
    ];

    public function getImageUrl()
    {
        return url('storage/'.$this->signature);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
