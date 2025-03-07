<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MeetingNotification extends Component
{
    use WithPagination, WithoutUrlPagination;
    public ?string $search = '';

    public function render()
    {
        return view('livewire.meeting-notification');
    }
//    #[Computed]
//    public function meetings()
//    {
//        return Meeting::with('meetingUsers')
//            ->where('title', 'like', '%'.$this->search.'%')
//            ->where('is_cancelled','!=','0')
//            ->where('scriptorium','!=',auth()->user()->user_info->full_name)
//            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
//            ->get();
//    }



    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,unit_organization,location,scriptorium,date,time,is_cancelled','user')
            ->where('user_id',auth()->user()->id)
//            ->where('is_present','!=','0')
            ->get(['id','meeting_id','user_id','is_present','reason_for_absent']);
    }

    public function markNotification($id)
    {
        MeetingUser::where('meeting_id',$id)
            ->where('user_id', auth()->user()->id)
            ->update([
                'read_by_user' => true
            ]);
        return to_route('meeting.notification');
    }


}
