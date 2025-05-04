<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Traits\MessageReceived;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Message extends Component
{
    use MessageReceived;
    public ?string $search='';

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
        return MeetingUser::where('user_id', auth()->id())
            ->where('read_by_user', false)
            ->count();
    }
    #[Computed]
    public function sentTaskCount()
    {
        $fullName = auth()->user()->user_info->full_name;

        return Task::whereHas('meeting', function ($query) use ($fullName) {
            $query->where('scriptorium', $fullName);
        })
            ->where('status', true)
            ->count();
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
    #[Computed]
    public function meetingCount()
    {
        return Meeting::where('scriptorium', auth()->user()->user_info->full_name)->count();
    }


    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium','=',auth()->user()->user_info->full_name)
            ->where('status','=',-1)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','status'])
            ->paginate(3);
    }

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with([
            'meeting:id,title,date,time,status',
            'user:id',
            'user.user_info:user_id,full_name'
        ])
            ->where('user_id', auth()->id())
            ->where('read_by_user', false)
            ->latest('created_at')
            ->select('id', 'meeting_id', 'user_id', 'is_present', 'reason_for_absent', 'read_by_user')
            ->paginate(5);
    }



}
