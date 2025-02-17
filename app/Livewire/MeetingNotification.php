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
    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium','!=',auth()->user()->user_info->full_name)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
            ->paginate(3);
    }
    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,scriptorium,date,time,is_cancelled','user')
            ->where('is_present','!=','0')
//            ->where('read_at',null)
            ->get(['id','meeting_id','user_id','is_present','reason_for_absent']);
    }

    public function markNotification($id)
    {
        DB::table('meeting_users')
            ->where('meeting_id',$id)
            ->where('user_id', auth()->user()->id)
            ->update([
                'read_by_user' => true
            ]);
        return redirect()->back();
    }


}
