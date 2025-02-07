<?php

namespace App\Listeners;

use App\Events\SetNewMeeting;
use App\Notifications\MeetingInvitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMeetingInvitation implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(SetNewMeeting $event): void
    {
        $meeting = $event->meeting;
        $invitedUsers = $event->invitedUsers;
        foreach ($invitedUsers as $invitedUser){
            $invitedUser->notify(new MeetingInvitation($meeting,$meeting->user->user_info->full_name));
        }
    }
}
