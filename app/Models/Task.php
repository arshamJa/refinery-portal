<?php

namespace App\Models;

use App\Enums\MeetingStatus;
use App\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;


    protected $fillable = [
        'meeting_id',
        'body',
        'time_out',
    ];

    public function full_name()
    {
        return UserInfo::where('user_id',$this->user_id)->value('full_name');
    }
    public function meeting():BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
    public function taskUsers():HasMany
    {
        return $this->hasMany(TaskUser::class)->chaperone();
    }
}
