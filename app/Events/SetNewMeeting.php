<?php

namespace App\Events;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SetNewMeeting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meeting;
    public $invitedUsers;
    /**
     * Create a new event instance.
     */
    public function __construct(Meeting $meeting , MeetingUser $meetingUsers)
    {
        $this->meeting = $meeting;
        $this->invitedUsers = $meetingUsers;
    }
}
