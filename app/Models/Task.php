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

    public function full_name()
    {
        return UserInfo::where('user_id',$this->user_id)->value('full_name');
    }
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
    public function deadLineTask()
    {
        return Task::where('user_id',$this->user_id)->where('meeting_id',$this->meeting_id)->value('time_out');
    }
    public function sentDate()
    {
        return Task::where('user_id',$this->user_id)->where('meeting_id',$this->meeting_id)->value('sent_date');
    }

}
