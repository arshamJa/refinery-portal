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
        $invitation = MeetingUser::where('user_id',auth()->user()->id)->where('is_present',0)->count();


        $invitations = \App\Models\MeetingUser::where('is_present','!=' , '0')->where('read_by_scriptorium',null)->count();


        $meetingsCount = \App\Models\Meeting::where('is_cancelled','!=','0')->where('scriptorium',auth()->user()->user_info->full_name)->count();
        $meeting_approval = Meeting::where('is_cancelled','!=','0')->where('scriptorium','!=',auth()->user()->user_info->full_name)->count();

        return $invitations + $meetingsCount + $invitation + $meeting_approval;
    }
}
