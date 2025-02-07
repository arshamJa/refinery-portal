<?php

namespace App\Notifications;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    private $meeting;
    private $inviter;
    /**
     * Create a new notification instance.
     */
    public function __construct(Meeting $meeting , User $user)
    {
        $this->meeting = $meeting;
        $this->inviter = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->inviter->user_info->full_name,
            'meeting_id' => $this->meeting->id,
        ];
    }
}
