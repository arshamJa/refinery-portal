<?php

namespace App\Models;

use App\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'meeting_id',
        'user_id',
//        'title',
        'body',
        'sent_date',
        'time_out',
        'is_completed',
        'request_task'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
}
