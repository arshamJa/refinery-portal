<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class MeetingUser extends Model
{
    use HasFactory, Notifiable , SoftDeletes;


    protected $fillable = [
        'user_id',
        'meeting_id',
        'is_present',
        'reason_for_absent',
        'read_by_scriptorium',
        'read_by_user',
        'replacement',
        'is_guest'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function getIsPresentStatusAttribute(): ?int
    {
        return (int) $this->attributes['is_present'];
    }

    public function is_present(): bool
    {
        return $this->attributes['is_present'] == 1;
    }

    public function is_absent(): bool
    {
        return $this->attributes['is_present'] == -1;
    }
//    public function replacementName(): ?string
//    {
//        if (! $this->replacement) return null;
//
//        return UserInfo::with('user')
//            ->whereRelation('user', 'id', $this->replacement)
//            ->value('full_name');
//    }
    public function replacementUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replacement');
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
