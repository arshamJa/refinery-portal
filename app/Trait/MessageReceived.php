<?php

namespace App\Trait;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Livewire\Attributes\Computed;

trait MessageReceived
{
    #[Computed]
    public function messages()
    {
        // send invitation to participants
        $send_invitation = MeetingUser::where('user_id',auth()->user()->id)->where('is_present',0)->count();

        // invitations result
        $invitations_result = MeetingUser::where('is_present','!=' , '0')->where('read_by_scriptorium',false)->count();

        // to send final result of meeting to participants
        $meeting_final_result = MeetingUser::with('meeting')
            ->whereRelation('meeting','is_cancelled','!=','0')
            ->whereRelation('meeting','scriptorium','!=',auth()->user()->user_info->full_name)
            ->where('user_id',auth()->user()->id)->where('read_by_user',false)->count();

        return $send_invitation + $invitations_result + $meeting_final_result;
    }
}
