<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'work_phone',
        'house_phone',
        'phone',
        'n_code',
        'full_name',
        'position',
        'create_meeting',
        'is_phoneList_allowed',
        'is_blog_allowed',
        'is_dictionary_allowed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
