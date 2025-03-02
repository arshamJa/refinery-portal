<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Trait\MeetingsTasks;
use App\Trait\Organizations;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AttendedMeetings extends Component
{
    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;

    public ?string $search = '';

    public function render()
    {
        return view('livewire.attended-meetings');
    }

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,scriptorium,date,time,is_cancelled')
            ->where('user_id', auth()->user()->id)
            ->select(['id', 'meeting_id', 'user_id', 'is_present'])
            ->paginate(3);
    }

    #[Computed]
    public function tasks()
    {
        return Task::where('user_id',auth()->user()->id)->get();
    }

}
