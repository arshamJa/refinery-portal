<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Traits\MessageReceived;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Message extends Component
{
//    use MessageReceived;

    public function render()
    {
        return view('livewire.message');
    }
    #[Computed]
    public function invitation()
    {
        return MeetingUser::where('user_id', auth()->id())
            ->where('is_present', '0')
            ->count();
    }
    #[Computed]
    public function read_by_user()
    {
        return MeetingUser::whereHas('meeting', function ($query) {
            $query->where('is_cancelled', '!=', 0);
        })
            ->where('user_id', auth()->id())
            ->where('read_by_user', false)
            ->count();
    }
    #[Computed]
    public function sentTaskCount()
    {
        $fullName = auth()->user()->user_info->full_name;

        return \App\Models\Task::whereHas('meeting', function ($query) use ($fullName) {
            $query->where('scriptorium', $fullName);
        })
            ->where('is_completed', true)
            ->count();
    }
    #[Computed]
    public function meetingCount()
    {
        return Meeting::where('scriptorium', auth()->user()->user_info->full_name)->count();
    }
    #[Computed]
    public function unreadMeetingUsersCount()
    {
        return MeetingUser::where('is_present', '!=', '0')
            ->where('read_by_scriptorium', false)
            ->whereHas('meeting', function ($query) {
                $query->where('scriptorium', auth()->user()->user_info->full_name);
            })
            ->count();
    }



}
