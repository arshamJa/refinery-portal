<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Traits\MeetingsTasks;
use App\Traits\Organizations;
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
    public function userNotifications(string $type = null)
    {
        $query = Notification::with(['sender.user_info', 'notifiable.meetingUsers'
        ])->where('sender_id', auth()->id()); // Always filter by sender
        if ($type) {
            $query->where('type', $type);
        }
        if ($this->filter) {
            if ($this->filter === 'invitation') {
                $query->whereIn('type', ['MeetingInvitation', 'MeetingGuestInvitation', 'MeetingBossInvitation']);
            } elseif ($this->filter === 'invitation_response') {
                $query->whereIn('type', ['AcceptInvitation', 'DenyInvitation']);
            } elseif ($this->filter === 'meeting_status') {
                $query->whereIn('type', ['MeetingConfirmed', 'MeetingCancelled']);
            } elseif ($this->filter === 'UpdatedTask') {
                $query->where('type', 'UpdatedTask');
            }elseif($this->filter === 'task_action'){
                $query->whereIn('type', ['AcceptedTask', 'DeniedTask']);
            }
            else {
                $query->where('type', $this->filter);
            }
        }
        return $query->latest()->paginate(10);
    }
    public function markAsRead($notificationId)
    {
        $notification = Notification::where('sender_id',auth()->id())->findOrFail($notificationId);
        $notification->sender_read_at = now();
        $notification->save();
        $this->dispatch('notificationRead');
        $this->resetPage();
    }


    #[Computed]
    public function unreadReceivedCount()
    {
        return Notification::where('recipient_id', auth()->id())
            ->whereNull('recipient_read_at')
            ->count();
    }
}
