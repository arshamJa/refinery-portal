<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'body',
        'is_locked'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
    public function taskUsers(): HasMany
    {
        return $this->hasMany(TaskUser::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
