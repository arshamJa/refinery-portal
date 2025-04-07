<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Support\Facades\Auth;
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
//    public function meetingUsers()
//    {
//        return MeetingUser::with([
//            'meeting:id,title,unit_organization,location,scriptorium,date,time,is_cancelled',
//            'user:id',
//            'user.user_info:user_id,full_name'
//        ])
//            ->where('user_id',auth()->user()->id)
////            ->where('is_present','!=','0')
//            ->get(['id','meeting_id','user_id','is_present','reason_for_absent']);
//    }

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with([
            'meeting:id,title,date,time,is_cancelled',
            'user:id',
            'user.user_info:user_id,full_name'
        ])
            ->where('user_id', auth()->id())
            ->where('is_present','==' , 0)
            ->where('read_by_user', false)
            ->latest('created_at')
            ->select('id', 'meeting_id', 'user_id', 'is_present', 'reason_for_absent', 'read_by_user')
            ->paginate(5);
    }


    public function markNotification($id)
    {
        MeetingUser::where('meeting_id', $id)
            ->where('user_id', Auth::id())
            ->update([
                'read_by_user' => true
            ]);
        $this->redirectRoute('meeting.notification');
    }


}
