<?php

namespace App\Livewire\employee;


use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Trait\MeetingsTasks;
use App\Trait\Organizations;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeDashboard extends Component
{

    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;
    public $meetingTitle;
    public $meeting_id;
    public function render()
    {
        return view('livewire.employee.employee-dashboard');
    }
    #[Computed]
    public function messages()
    {
        $invitations = \App\Models\MeetingUser::where('is_present','!=' , '0')->where('read_at',null)->count();
        $meetingsCount = \App\Models\Meeting::where('is_cancelled','!=','0')->where('scriptorium','==',auth()->user()->user_info->full_name)->count();
        return $invitations + $meetingsCount;
    }
    #[Computed]
    public function invitation()
    {
        return MeetingUser::where('user_id',auth()->user()->id)->where('is_present',0)->count();
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
