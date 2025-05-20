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
    protected $listeners = ['notificationRead' => '$refresh'];
    public function render()
    {
        return view('livewire.side-bar');
    }



    #[Computed]
    public function unreadReceivedCount()
    {
        return Notification::where('recipient_id', auth()->id())
            ->whereNull('recipient_read_at')
            ->count();
    }
    #[Computed]
    public function unreadSentCount()
    {
        return Notification::where('sender_id', auth()->id())
            ->whereNull('sender_read_at')
            ->count();
    }
}
