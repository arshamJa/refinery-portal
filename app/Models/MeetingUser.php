<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class MeetingUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'meeting_id',
        'is_present',
        'reason_for_absent',
        'read_by_scriptorium',
        'read_by_user',
        'replacement'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function invitation()
    {
        return MeetingUser::where('user_id',auth()->user()->id)->where('is_present',0)->count();
    }

    public function holders()
    {
        return UserInfo::where('user_id',$this->user_id)->value('full_name');
    }
    public function deadLineTask()
    {
        return Task::where('user_id',$this->user_id)->where('meeting_id',$this->meeting_id)->value('time_out');
//        if (preg_match("/^(\d+)\/(\d+)\/(\d+)$/", $time_out, $matches)) {
//            $year = $matches[1];
//            $month = $matches[2];
//            $day = $matches[3];
//            if ($day >= 1 && $day <= 9) {
//                $newDay = "0" . $day;
//                dump($year.'/'.$month.'/'.$newDay);
//            }
//        }
    }
    public function sentDate()
    {
        return Task::where('user_id',$this->user_id)->where('meeting_id',$this->meeting_id)->value('sent_date');
    }

    public function is_present()
    {
        return MeetingUser::where('meeting_id',$this->meeting_id)
                ->where('user_id',$this->user_id)
                ->value('is_present') == '1';
    }

    public function is_absent()
    {
        return MeetingUser::where('meeting_id',$this->meeting_id)
                ->where('user_id',$this->user_id)
                ->value('is_present') == '-1';
    }

    public function replacementName()
    {
            return UserInfo::with('user')
                ->whereRelation('user','id','=',$this->replacement)
                ->value('full_name');
    }
}
