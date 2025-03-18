<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class InvitationsResult extends Component
{
    use WithPagination, WithoutUrlPagination;
    public ?string $search = '';

    public function render()
    {
        return view('livewire.invitations-result');
    }
    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with([
            'meeting:id,title,scriptorium,date,time,is_cancelled',
            'user:id',
            'user.user_info:user_id,full_name'
        ])
            ->where('is_present','!=','0')
            ->where('read_by_scriptorium',false)
            ->whereRelation('meeting','scriptorium','=',auth()->user()->user_info->full_name)
            ->get(['id','meeting_id','user_id','is_present','reason_for_absent','replacement']);
    }

    public function markNotification(string $id)
    {
        $meetingUser = MeetingUser::with('user.user_info')
        ->find($id);

        if ($meetingUser) {
            $meetingUser->read_by_scriptorium = true;
            $meetingUser->save();
        }
        return to_route('invitations.result');
    }
}
