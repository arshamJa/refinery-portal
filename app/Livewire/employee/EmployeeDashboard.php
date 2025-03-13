<?php

namespace App\Livewire\employee;


use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\User;
use App\Trait\MeetingsTasks;
use App\Trait\MessageReceived;
use App\Trait\Organizations;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Nette\Schema\ValidationException;

class EmployeeDashboard extends Component
{

    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks, MessageReceived;
    public $meetingTitle;
    public $meeting_id;


    #[Computed]
    public function getMeetingsToday()
    {
        $jalaliNow = gregorian_to_jalali(now()->year, now()->month, now()->day, '/');
        return Meeting::where('date','like',$jalaliNow)->get();
    }


    public function render()
    {
        return view('livewire.employee.employee-dashboard');
    }


    #[Computed]
    public function meetings(){
        return Meeting::where('scriptorium', '=' ,auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=','-1')
            ->count();
    }


    /**
     * this is for scriptoriums only
     */
//    #[Computed]
//    public function meetingNotifications()
//    {
//        return Meeting::where('scriptorium',auth()->user()->user_info->full_name)
//            ->where('is_cancelled','-1')
//            ->get(['title','location','date','time']);
//    }
    #[Computed]
    public function meetingsSchedule()
    {
        return Meeting::with('meetingUsers')
            ->where('is_cancelled','=','-1')
            ->whereRelation('meetingUsers','user_id','=',auth()->user()->id)
            ->get(['title','location','date','time']);
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
