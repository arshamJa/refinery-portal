<?php

namespace App\Traits;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Livewire\Attributes\Computed;

trait MessageReceived
{
    #[Computed]
    public function messages()
    {
        $userId = auth()->id();
        $fullName = auth()->user()->user_info->full_name;

        // Count: Invitations the user hasn't responded to
        $send_invitation = MeetingUser::where('user_id', $userId)
            ->where('is_present', 0)
            ->count();

        // Count: Unread invitations result for meetings organized by user
        $invitations_result = MeetingUser::where('is_present', '!=', '0')
            ->where('read_by_scriptorium', false)
            ->whereHas('meeting', function ($query) use ($fullName) {
                $query->where('scriptorium', $fullName);
            })
            ->count();

        // Count: Final meeting result not read by the user (meetings they didn't organize)
        $meeting_final_result = MeetingUser::where('user_id', $userId)
            ->where('read_by_user', false)
            ->whereHas('meeting', function ($query) use ($fullName) {
                $query->where('is_cancelled', '!=', '0')
                    ->where('scriptorium', '!=', $fullName);
            })
            ->count();

        return $send_invitation + $invitations_result + $meeting_final_result;
    }




}
