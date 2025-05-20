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
        return Notification::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('recipient_id', $userId);
        })
            ->where(function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)
                        ->whereNull('sender_read_at');
                })
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('recipient_id', $userId)
                            ->whereNull('recipient_read_at');
                    });
            })
            ->count();
    }

}
