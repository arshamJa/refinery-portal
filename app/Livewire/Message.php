<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Trait\MessageReceived;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Message extends Component
{
    use MessageReceived;

    public function render()
    {
        return view('livewire.message');
    }
    #[Computed]
    public function invitation()
    {
        return MeetingUser::where('user_id',auth()->user()->id)->where('is_present','0')->count();
    }
    #[Computed]
    public function read_by_user()
    {
        return MeetingUser::with('meeting:id,is_cancelled')
            ->whereRelation('meeting','is_cancelled','!=','0')
            ->where('user_id',auth()->user()->id)
            ->where('read_by_user',false)
            ->count();
    }


    #[Computed]
    public function sentTaskCount()
    {
//        $meetingIds = Meeting::where('scriptorium', auth()->user()->user_info->full_name)
//            ->pluck('id');
        // Count the completed tasks that belong to those meetings
//        return \App\Models\Task::whereIn('meeting_id', $meetingIds)
//            ->where('is_completed', true)
//            ->count();
        $meetingIds = Meeting::where('scriptorium', auth()->user()->user_info->full_name)
            ->pluck('id')
            ->toArray(); // Convert to array for whereIn
        return \App\Models\Task::whereIn('meeting_id', $meetingIds)
            ->where('is_completed', true)
            ->count();
    }

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with([
            'meeting:id,title,scriptorium,date,time,is_cancelled',
            'user:id',
            'user.user_info:user_id,full_name'
        ])
            ->where('is_present','!=','0')
            ->where('read_by_scriptorium',false)
            ->whereRelation('meeting','scriptorium','=',auth()->user()->user_info->full_name)
            ->get(['id','meeting_id','user_id','is_present','reason_for_absent','replacement']);
    }
    #[Computed]
    public function meetingCount()
    {
        return Meeting::where('scriptorium', auth()->user()->user_info->full_name)->count();
    }
    #[Computed]
    public function unreadMeetingUsersCount()
    {
        return MeetingUser::with('meeting:id,scriptorium')
            ->where('is_present', '!=', '0')
            ->where('read_by_scriptorium', false)
            ->whereRelation('meeting', 'scriptorium', auth()->user()->user_info->full_name)
            ->count();
    }



}
