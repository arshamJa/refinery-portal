<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskUser extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'time_out',
        'sent_date',
        'task_status',
        'body_task',
        'request_task',
    ];

    protected $casts = [
        'task_status' => TaskStatus::class,
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taskUserFiles(): HasMany
    {
        return $this->hasMany(TaskUserFile::class)->chaperone();
    }
    public function full_name()
    {
        return UserInfo::where('user_id', $this->user_id)->value('full_name');
    }
}
