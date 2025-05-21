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
        'user_id',
        'sent_date',
        'time_out',
        'task_status',
        'body_task',
        'request_task',
    ];

    protected $casts = [
        'task_status' => TaskStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taskUserFiles(): HasMany
    {
        return $this->hasMany(TaskUserFile::class, 'task_id');
    }

    public function full_name()
    {
        return UserInfo::where('user_id', $this->user_id)->value('full_name');
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
