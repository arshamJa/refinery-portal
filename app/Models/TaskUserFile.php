<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskUserFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_user_id',
        'original_name',
        'file_path',
    ];

    public function taskUser():BelongsTo
    {
        return $this->belongsTo(TaskUser::class);
    }
}
