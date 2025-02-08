<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Trait\MeetingsTasks;
use App\Trait\Organizations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MeetingsList extends Component
{
    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;
    public $meeting;
    public $meeting_id;
    public $body;

    public function render()
    {
        return view('livewire.meetings-list');
    }

    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium',auth()->user()->user_info->full_name)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
            ->paginate(3);
    }


    public ?string $search = '';
    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,scriptorium,date,time,is_cancelled')
            ->where('user_id',auth()->user()->id)
            ->select('id','meeting_id','user_id','is_present')->paginate(3);
    }





    public function denyMeeting($meetingId)
    {
        Meeting::where('id', $meetingId)->update([
            'is_cancelled' => '-1'
        ]);
        $this->close();
    }
    public function openModalDelete($meetingId)
    {
        $this->meeting = Meeting::where('id',$meetingId)->value('title');
        $this->meeting_id = $meetingId;
        $this->dispatch('crud-modal', name: 'delete');
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return redirect()->back();
    }
    public function accept($meetingId)
    {
        $meeting = MeetingUser::find($meetingId);
        $meeting->is_present = '1';
        $meeting->save();
    }


    public function openModalDeny($meetingId)
    {
        $this->meeting = Meeting::where('id',$meetingId)->value('title');
        $this->meeting_id = $meetingId;
        $this->dispatch('crud-modal', name: 'delete');
    }

    public function deny($meetingId)
    {
        $meeting = MeetingUser::find($meetingId);
        $meeting->is_present = '-1';
        $meeting->reason_for_absent = $this->body;
        $meeting->save();
        $this->close();
    }
}
