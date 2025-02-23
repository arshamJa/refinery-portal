<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Trait\MessageReceived;
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
        return MeetingUser::with('meeting')
            ->where('user_id',auth()->user()->id)
            ->whereRelation('meeting','is_cancelled','!=','0')
            ->where('read_by_user',false)
            ->count();
    }

    #[Computed]
    public function sentTaskCount()
    {
        $meetingIds = Meeting::where('scriptorium', auth()->user()->user_info->full_name)
            ->pluck('id');

        // Count the completed tasks that belong to those meetings
        return \App\Models\Task::whereIn('meeting_id', $meetingIds)
            ->where('is_completed', true)
            ->count();
    }


}
