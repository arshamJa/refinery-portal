<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Traits\HasNotificationCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SideBar extends Component
{
    use HasNotificationCount;

    public function render()
    {
        return view('livewire.side-bar');
    }

    #[Computed]
    public function unreadReceivedCount()
    {
        $userId = auth()->id();

        return Cache::remember("unread_received_count_user_{$userId}", 60, function () use ($userId) {
            return Notification::where('recipient_id', $userId)
                ->whereNull('recipient_read_at')
                ->count();
        });
    }

    #[Computed]
    public function unreadSentCount()
    {
        $userId = auth()->id();
        return Cache::remember("unread_sent_count_user_{$userId}", 60, function () use ($userId) {
            return Notification::where('sender_id', $userId)
                ->whereNull('sender_read_at')
                ->count();
        });
    }
}
