<?php

namespace App\Traits;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use Livewire\Attributes\Computed;

trait MessageReceived
{
    #[Computed]
    public function messages()
    {
        $user = auth()->user();
        $userId = $user->id;
        $fullName = $user->user_info->full_name;

        $invitationCount = MeetingUser::where('user_id', $userId)
            ->where('is_present', '0')
            ->count();

        $readByUserCount = MeetingUser::where('user_id', $userId)
            ->where('read_by_user', false)
            ->count();

        $sentTaskCount = Task::whereHas('meeting', function ($query) use ($fullName) {
            $query->where('scriptorium', $fullName);
        })
            ->where('is_completed', true)
            ->count();

        $unreadMeetingUsersCount = MeetingUser::where('is_present', '!=', '0')
            ->where('read_by_scriptorium', false)
            ->whereHas('meeting', function ($query) use ($fullName) {
                $query->where('scriptorium', $fullName);
            })
            ->count();

        return $invitationCount + $readByUserCount + $sentTaskCount + $unreadMeetingUsersCount;
    }




}
