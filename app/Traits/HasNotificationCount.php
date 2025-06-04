<?php

namespace App\Traits;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

trait HasNotificationCount
{
    #[Computed]
    public function messagesNotification()
    {
        $userId = auth()->id();

        if (!$userId) {
            return 0;
        }

        return Notification::where('recipient_id', $userId)
            ->whereNull('recipient_read_at')
            ->count();
    }

}
