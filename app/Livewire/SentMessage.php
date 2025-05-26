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
    public bool $unreadOnly = true;
    public $filter = '';


    public function filterMessage()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.sent-message');
    }
    public function toggleUnreadOnly()
    {
        $this->unreadOnly = !$this->unreadOnly;
        $this->resetPage();
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

        if ($this->unreadOnly) {
            $query->whereNull('sender_read_at');
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


    #[Computed]
    public function getNotificationMessage($notification)
    {
        $message = json_decode($notification->data)->message ?? 'N/A';
        // Directly check the notification type for 'AcceptInvitation'
        if ($notification->type === 'AcceptInvitation') {
            return 'شما دعوت به جلسه را قبول کردید.';
        }
        if ($notification->type === 'DenyInvitation') {
            return 'شما دعوت به جلسه را رد کردید.';
        }
        if ($notification->type === 'ReplacementForMeeting') {
            $meeting = $notification->notifiable;
            if ($meeting) {
                $recipient = User::find($notification->recipient_id);
                $fullName = $recipient?->user_info?->full_name ?? 'نامشخص';

                return 'شما این جلسه را پذیرفته‌اید و این فرد جانشین شماست: ' . $fullName;
            }
        }
        if ($notification->type === 'MeetingGuestInvitation') {
            $meeting = $notification->notifiable;
            if ($meeting) {
                $date = $meeting->date;
                $time = $meeting->time;

                return "این شخص به عنوان مهمان در جلسه \"{$meeting->title}\" و در تاریخ {$date} و ساعت {$time} دعوت شده‌است.";
            }
        }
        if ($notification->type === 'MeetingInvitation') {
            $meeting = $notification->notifiable;
            if ($meeting) {
                $date = $meeting->date;
                $time = $meeting->time;

                return "این شخص در جلسه \"{$meeting->title}\" و در تاریخ {$date} و ساعت {$time} دعوت شده‌است.";
            }
        }
        if ($notification->type === 'AssignedNewTask') {
            $task = $notification->notifiable;  // Task model instance
            $meeting = $task?->meeting;          // Related meeting

            $recipientFullName = $notification->recipient?->user_info?->full_name ?? 'نامشخص';
            // Fetch task_user record for this task and recipient
            $taskUser = \App\Models\TaskUser::where('task_id', $task->id)->where('user_id', $notification->recipient_id)
                ->first();
            $timeOut = $taskUser?->time_out ?? 'زمان مشخص نشده';
            $meetingTitle = $meeting?->title ?? 'بدون عنوان جلسه';
            return "شما وظیفه‌ای را به آقای/خانم {$recipientFullName} ارسال کرده‌اید برای جلسه «{$meetingTitle}». تاریخ مهلت اقدام: {$timeOut}.";
        }

        if ($notification->type === 'UpdatedTaskTimeOut') {
            $taskUser = $notification->notifiable;
            $timeOut = $taskUser?->time_out ?? 'زمان مشخص نشده';
            $meetingTitle = $taskUser?->task?->meeting?->title ?? 'بدون عنوان جلسه';
            $recipientFullName = $notification->recipient?->user_info?->full_name ?? 'نامشخص';
            return "مهلت انجام اقدام آقای/خانم {$recipientFullName} برای جلسه «{$meetingTitle}» به تاریخ {$timeOut} به‌روزرسانی شد.";
        }
        if ($notification->type === 'UpdatedTaskBody') {
            $taskUser = $notification->notifiable; // TaskUser model instance
            $meetingTitle = $taskUser?->task?->meeting?->title ?? 'بدون عنوان جلسه';
            $recipientFullName = $notification->recipient?->user_info?->full_name ?? 'نامشخص';

            return "متن مذاکره آقای/خانم {$recipientFullName} برای جلسه «{$meetingTitle}» به‌روزرسانی شد.";
        }


        if (auth()->id() === $notification->sender_id) {
            $meeting = $notification->notifiable; // Assuming this is your meeting model
            if ($meeting) {
                // Format date/time (assuming $meeting->date is a Carbon instance or date string)
                $dateMeeting = $meeting->date;
                $timeMeeting = $meeting->time;

                return "شما آقای/خانم " . ($notification->recipient->user_info->full_name ?? 'N/A')
                    . " را به جلسه \"{$meeting->title}\" در تاریخ {$dateMeeting} و ساعت {$timeMeeting} دعوت کرده‌اید.";
            }
            // Fallback message if meeting is not found
            return "شما آقای/خانم " . ($notification->recipient->user_info->full_name ?? 'N/A') . " را به یک جلسه دعوت کرده‌اید.";
        }

        return $message;
    }

    #[Computed]
    public function getSentNotificationDateTime($notification)
    {
        // Extract the date and time from created_at
        $datetime = $notification->created_at;
        list($date, $time) = explode(' ', $datetime);
        // Separate the date into year, month, and day
        list($year, $month, $day) = explode('-', $date);
        // Convert the Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali($year, $month, $day, '/'));
        // Format the Jalali date
        $newDate = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
        // Extract hour and minute from time
        list($hour, $minute) = explode(':', $time);
        // Combine Jalali date with hour and minute only
        return $newDate . ' - ' . $hour . ':' . $minute;
    }
}
