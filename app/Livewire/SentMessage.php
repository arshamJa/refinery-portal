<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\User;
use App\Traits\MeetingsTasks;
use App\Traits\MessageReceived;
use App\Traits\Organizations;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SentMessage extends Component
{

    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;
    public ?string $search='';
    public $meeting;
    public $meetingId;
    public $body;
    public bool $checkBox = false;
    public $full_name;
    public $p_code;

    public $activeTab = 'sent';  // 'sent' or 'received'
    public $filter = '';



    public function filterMessage()
    {
        $this->resetPage();
    }
    public function resetFilters()
    {
        $this->filter = '';
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.sent-message');
    }

    #[Computed]
    public function userNotifications(string $type = null, bool $isSender = false)
    {
        $query = Notification::with([
            'sender.user_info',
            'notifiable'
        ]);

        if ($this->activeTab === 'sent') {
            $query->where('sender_id', auth()->id());
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($this->filter) {
            $query->where('type', $this->filter);
        }
        return $query->latest()->paginate(5);
    }
    public function markAsRead($notificationId)
    {
        $notification = Notification::where('sender_id',auth()->id())->findOrFail($notificationId);
        $notification->sender_read_at = now();
        $notification->save();
        $this->dispatch('notificationRead');
        $this->resetPage();
    }
}
