<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PresentUsers extends Component
{

    public $meetingTitle;

    public function render()
    {
        return view('livewire.present-users');
    }

    public $meetingId;

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('user','meeting')
            ->where('meeting_id',$this->meetingId)
            ->where('is_guest',0)
            ->get(['meeting_id','user_id','is_present','reason_for_absent','replacement']);
    }

    #[Computed]
    public function meeting()
    {
        return Meeting::where('id',$this->meetingId)->value('status');
    }

    #[Computed]
    public function present()
    {
        return MeetingUser::where('meeting_id',$this->meetingId)->where('is_present',1)->count();
    }

    #[Computed]
    public function absent()
    {
        return MeetingUser::where('meeting_id',$this->meetingId)->where('is_present',-1)->count();
    }

    #[Computed]
    public function not_sent()
    {
        return MeetingUser::where('meeting_id',$this->meetingId)->where('is_present',0)->where('is_guest',0)->count();
    }

    public function acceptMeeting($meetingId)
    {
        $meeting = Meeting::find($meetingId);
        $meeting->status = '-1';
        $meeting->save();
        return redirect()->back()->with('status','جلسه با موفقیت تایید نهایی شد');
    }

    public function openModalDeny($department_id)
    {
        $this->meetingTitle = Meeting::where('id',$department_id)->value('title');
        $this->meetingId = $department_id;
        $this->dispatch('crud-modal', name: 'delete');
    }
    public function denyMeeting($meetingId)
    {
        $meeting = Meeting::find($meetingId);
        $meeting->status = '1';
        $meeting->save();
        $this->dispatch('close-modal');
        return redirect()->back()->with('status','جلسه با موفقیت لفو نهایی شد');

    }
    public function accept($meetingId)
    {
        MeetingUser::where('user_id', auth()->user()->id)->where('meeting_id', $meetingId)->update([
            'is_present' => '1'
        ]);
        return redirect()->back();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return redirect()->back();
    }




}
