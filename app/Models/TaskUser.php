<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskUser extends Model
{
     use HasFactory;

     protected $fillable = [
         'task_id',
         'user_id',
         'sent_date',
         'task_status',
         'body_task',
         'request_task',
     ];
    protected $casts = [
        'task_status' => TaskStatus::class,
    ];

    public function task():BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function taskUserFiles():HasMany
    {
        return $this->hasMany(TaskUserFile::class);
    }
}
