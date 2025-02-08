<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MeetingTable extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $meetingTitle;
    public $meeting_id;

    public function render()
    {
        return view('livewire.meeting-table');
    }
    public ?string $search = '';

    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium','=',auth()->user()->user_info->full_name)
//            ->where('is_cancelled','=',-1)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
            ->paginate(3);
    }
    public function acceptMeeting($meetingId)
    {
        $meeting = Meeting::find($meetingId);
        $meeting->is_cancelled = '-1';
        $meeting->save();
        return redirect()->back();
    }

    public function openModalDelete($meetingId)
    {
        $this->meetingTitle = Meeting::where('id',$meetingId)->value('title');
        $this->meeting_id = $meetingId;
        $this->dispatch('crud-modal', name: 'delete');
    }
    public function denyMeeting($meetingId)
    {
        $meeting = Meeting::find($meetingId);
        $meeting->is_cancelled = '1';
        $meeting->save();
        $this->close();
    }
    public function accept($meetingId)
    {
        MeetingUser::where('user_id', auth()->user()->id)->where('meeting_id', $meetingId)->update([
            'is_present' => '1'
        ]);
        return redirect()->back();
    }

    public function deny($meetingId)
    {
        MeetingUser::where('user_id', auth()->user()->id)->where('meeting_id', $meetingId)->update([
            'is_present' => '-1'
        ]);
        return redirect()->back();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return redirect()->back();
    }


}
