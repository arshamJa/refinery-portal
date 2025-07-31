<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'type', 'data', 'notifiable_type', 'notifiable_id',
        'sender_id', 'recipient_id', 'status', 'recipient_read_at'
    ];


    // Mark as read for recipient
    public function markAsReadByRecipient()
    {
        $this->update(['recipient_read_at' => now()]);
    }


    // Check if the notification is read for recipient
    public function isReadByRecipient()
    {
        return !is_null($this->recipient_read_at);
    }

    // Check if the notification is read by the authenticated user
    public function isReadByCurrentUser()
    {
        $userId = auth()->id();
        if ($this->sender_id === $userId) {
            return $this->isReadBySender();
        } elseif ($this->recipient_id === $userId) {
            return $this->isReadByRecipient();
        }
        return false;
    }
    public static function hasUnreadForCurrentUser()
    {
        $userId = auth()->id();
        if (!$userId) {
            return false;
        }

        return self::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->orWhere('recipient_id', $userId);
        })
            ->where(function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    // Unread if current user is sender and sender_read_at is null
                    $q->where('sender_id', $userId)
                        ->whereNull('sender_read_at');
                })
                    ->orWhere(function ($q) use ($userId) {
                        // Or unread if current user is recipient and recipient_read_at is null
                        $q->where('recipient_id', $userId)
                            ->whereNull('recipient_read_at');
                    });
            })
            ->exists();
    }

    public function getNotificationMessage(): string
    {
        $message = json_decode($this->data)->message ?? 'N/A';

        if ($this->type === 'AcceptInvitation') {
            $meeting = $this->notifiable;
            if ($meeting) {
                return "این شخص دعوت به جلسه \"{$meeting->title}\" برای تاریخ {$meeting->date} و ساعت {$meeting->time} قبول کرده است";
            }
            return 'این شخص دعوت به جلسه را قبول کرده است';
        }

        if ($this->type === "MeetingCancelled"){
            $meeting = $this->notifiable;
            if ($meeting) {
                return "جلسه \"{$meeting->title}\" که قرار بود در تاریخ {$meeting->date} و ساعت {$meeting->time} برگزار شود، لغو شده‌است.";
            }
        }

        if ($this->type === 'DenyInvitation') {
            $meeting = $this->notifiable;
            if ($meeting) {
                $msg = "این شخص دعوت به جلسه \"{$meeting->title}\" برای تاریخ {$meeting->date} و ساعت {$meeting->time} رد کرده است.";

                // Fetch the meeting_users row
                $meetingUser = DB::table('meeting_users')
                    ->where('meeting_id', $meeting->id)
                    ->where('user_id', $this->sender_id)
                    ->first();

                if ($meetingUser) {
                    // Check for replacement
                    if ($meetingUser->replacement) {
                        $replacementUser = User::with('user_info')->find($meetingUser->replacement);
                        $replacementName = $replacementUser?->user_info?->full_name ?? 'نامشخص';
                        $msg .= " جانشین او: {$replacementName}";
                    }

                    // Add reason for absence
                    if (!empty($meetingUser->reason_for_absent)) {
                        $msg .= " (دلیل رد: «{$meetingUser->reason_for_absent}»)";
                    }
                }

                return $msg;
            }

            return 'این شخص دعوت به جلسه را رد کرده است.';
        }

        if ($this->type === 'ReplacementForMeeting') {
            $meeting = $this->notifiable;
            if ($meeting) {
                $replacementUserId = $this->recipient_id;
                $replacementUser = User::with('user_info')->find($replacementUserId);
                $replacementName = $replacementUser?->user_info?->full_name ?? 'نامشخص';
                $meetingUser = $meeting->meetingUsers()
                    ->where('replacement', $replacementUserId)
                    ->with('user.user_info')
                    ->first();
                $assignerName = $meetingUser?->user?->user_info?->full_name ?? 'فرد ناشناس';
                return "{$assignerName} شما را به عنوان جانشین خود برای جلسه «{$meeting->title}» برای تاریخ {$meeting->date} ساعت {$meeting->time} انتخاب کرده است.";
            }
        }

        if (auth()->id() === $this->sender_id) {
            $meeting = $this->notifiable;
            if ($meeting) {
                return "شما آقای/خانم " . ($this->recipient->user_info->full_name ?? 'N/A')
                    . " را به جلسه \"{$meeting->title}\" در تاریخ {$meeting->date} و ساعت {$meeting->time} دعوت کرده‌اید.";
            }
            return "شما آقای/خانم " . ($this->recipient->user_info->full_name ?? 'N/A') . " را به یک جلسه دعوت کرده‌اید.";
        }
        return $message;
    }

    public function getSentMessage(): string
    {
        $message = json_decode($this->data)->message ?? 'N/A';
        $meeting = $this->notifiable;

        if ($this->type === 'AcceptInvitation') {
            $userId = $this->recipient_id;
            $date = $meeting->date;
            $time = $meeting->time;
            $title = $meeting->title;

            $meetingUser = \App\Models\MeetingUser::where('meeting_id', $meeting->id)
                ->where('user_id', $userId)->first();

            if ($meetingUser && $meetingUser->replacement) {
                $replacementName = \App\Models\UserInfo::where('user_id', $meetingUser->replacement)->value('full_name') ?? 'نامشخص';
                return "شما دعوت به جلسه «{$title}» برای تاریخ {$date} و ساعت {$time} را قبول کردید و آقای/خانم {$replacementName} به جای شما در جلسه شرکت خواهد کرد.";
            }

            return "شما دعوت به جلسه «{$title}» برای تاریخ {$date} و ساعت {$time} را قبول کردید.";
        }

        if ($this->type === 'DenyInvitation') {
            return "شما دعوت به جلسه «{$meeting->title}» برای تاریخ {$meeting->date} و ساعت {$meeting->time} را رد کردید.";
        }

        if ($this->type === 'ReplacementForMeeting') {
            if ($meeting && auth()->id() === $this->sender_id) {
                $replacementName = \App\Models\User::find($this->recipient_id)?->user_info?->full_name ?? 'نامشخص';
                return "شما دعوت به جلسه «{$meeting->title}» برای تاریخ {$meeting->date} و ساعت {$meeting->time} را رد کردید. جانشین شما: {$replacementName}.";
            }
        }

        if (in_array($this->type, ['MeetingGuestInvitation', 'MeetingInvitation', 'MeetingBossInvitation'])) {
            $roleText = match ($this->type) {
                'MeetingBossInvitation' => 'به عنوان رئیس ',
                'MeetingGuestInvitation' => 'به عنوان مهمان ',
                default => '',
            };
            return "این شخص {$roleText}در جلسه \"{$meeting->title}\" و در تاریخ {$meeting->date} و ساعت {$meeting->time} دعوت شده‌است.";
        }

        if (in_array($this->type, ['MeetingConfirmed', 'MeetingCancelled'])) {
            return $this->type === 'MeetingConfirmed'
                ? "جلسه \"{$meeting->title}\" در تاریخ {$meeting->date} و ساعت {$meeting->time} برگزار خواهد شد."
                : "جلسه \"{$meeting->title}\" که قرار بود در تاریخ {$meeting->date} و ساعت {$meeting->time} برگزار شود، لغو شده‌است.";
        }

        if ($this->type === 'AcceptedTask') {
            $data = json_decode($this->data);
            $messageData = $data->message ?? null;
            if (!$messageData) {
                return "اطلاعات اقدام پذیرفته شده در دسترس نیست.";
            }
            if (is_string($messageData)) {
                // Get the authenticated user's full name
                $userName = auth()->user()->user_info->full_name ?? 'شما';
                // Replace "این شخص" with the user's full name
                $personalizedMessage = str_replace('این شخص', $userName, $messageData);
                return $personalizedMessage;
            }
            // If message is an object, extract properties safely
            $taskBody = $messageData->body ?? 'نامشخص';
            $meetingTitle = $messageData->meeting->title ?? 'نامشخص';
            $userName = auth()->user()->full_name ?? 'شما';
            return "{$userName} اقدام «{$taskBody}» را از جلسه «{$meetingTitle}» پذیرفته‌اید.";
        }
        if ($this->type === 'DeniedTask') {
            $data = json_decode($this->data);
            $messageData = $data->message ?? null;
            if (!$messageData) {
                return "اطلاعات اقدام رد شده در دسترس نیست.";
            }
            if (is_string($messageData)) {
                // Get the authenticated user's full name
                $userName = auth()->user()->user_info->full_name ?? 'شما';
                // Replace "این شخص" with the user's full name
                $personalizedMessage = str_replace('این شخص', $userName, $messageData);
                return $personalizedMessage;
            }
            // If message is an object, extract properties safely
            $taskBody = $messageData->body ?? 'نامشخص';
            $meetingTitle = $messageData->meeting_title ?? 'نامشخص';
            $reason = $messageData->reason ?? 'بدون دلیل مشخص';

            $userName = auth()->user()->user_info->full_name ?? 'شما';
            return "{$userName} اقدام «{$taskBody}» را در جلسه «{$meetingTitle}» رد کرده‌اید. دلیل: «{$reason}».";
        }

        if ($this->type === 'AssignedNewTask') {
            $task = \App\Models\Task::where('meeting_id', $meeting->id)
                ->whereHas('taskUsers', fn($q) => $q->where('user_id', $this->recipient_id))
                ->first();

            $taskUser = \App\Models\TaskUser::where('task_id', $task?->id)
                ->where('user_id', $this->recipient_id)->first();

            $timeout = $taskUser?->time_out ?? 'زمان مشخص نشده';
            return "شما بندی را به این شخص برای جلسه «{$meeting->title}» ارسال کرده‌اید. تاریخ مهلت اقدام: {$timeout}.";
        }

        if ($this->type === 'UpdatedTask') {
            $taskUser = \App\Models\TaskUser::whereHas('task', function ($q) use ($meeting) {
                $q->where('meeting_id', $meeting->id);
            })->where('user_id', $this->recipient_id)->first();

            $updater = $this->recipient?->user_info?->full_name ?? 'نامشخص';
            return "متن/مهلت اقدام مربوط به جلسه «{$meeting->title}» برای {$updater} به‌روزرسانی شد.";
        }

        if ($this->type === 'TaskSentToScriptorium') {
            $userId = auth()->id();

            $taskUser = \App\Models\TaskUser::where('user_id', $userId)
                ->whereHas('task', function ($q) use ($meeting) {
                    $q->where('meeting_id', $meeting->id);
                })
                ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                ->whereNotNull('sent_date')
                ->first();

            $sentDate = $taskUser->sent_date ?? 'نامشخص';
            $userName = 'شما';
            $scriptoriumName = $meeting->scriptorium->user_info->full_name ?? 'نامشخص';

            return "{$userName} این بند را برای جلسه «{$meeting->title}» به {$scriptoriumName} ارسال کرده اید. تاریخ ارسال: {$sentDate}.";
        }

        // Fallback: sender message
        if (auth()->id() === $this->sender_id) {
            $recipientName = $this->recipient?->user_info?->full_name ?? 'N/A';
            return "شما آقای/خانم {$recipientName} را به جلسه \"{$meeting->title}\" در تاریخ {$meeting->date} و ساعت {$meeting->time} دعوت کرده‌اید.";
        }

        return $message;
    }

    public function getNotificationDateTime(): string
    {
        $datetime = $this->created_at;
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
    public function getSenderRoleLabel(): string
    {
        $meeting = $this->notifiable;
        $userId = $this->sender_id;

        if (! $meeting || ! $userId) {
            return 'نقش نامشخص';
        }
        // Check if sender is the boss
        if ($meeting->boss_id === $userId) {
            return 'رئیس';
        }
        // Check if sender is the scriptorium
        if ($meeting->scriptorium_id === $userId) {
            return 'دبیر';  // or whatever label you want for scriptorium
        }
        // Get all meeting users
        $meetingUsers = $meeting->meetingUsers;

        // Exclude users who are acting as replacements
        $isReplacement = $meetingUsers->contains(fn ($mu) => $mu->replacement === $userId);
        if ($isReplacement) {
            return 'جانشین';
        }
        // Get the user's record (non-replacement only)
        $meetingUser = $meetingUsers->firstWhere('user_id', $userId);
        if ($meetingUser) {
            if ((bool) $meetingUser->is_guest) {
                return 'مهمان';
            }
            return 'عضو جلسه';
        }
        return 'نقش نامشخص';
    }
    public function getReceiverRoleLabel(): string
    {
        $meeting = $this->notifiable;
        $userId = $this->recipient_id;

        if (! $meeting || ! $userId) {
            return 'نقش نامشخص';
        }

        // Check if receiver is the boss
        if ($meeting->boss_id === $userId) {
            return 'رئیس';
        }

        // Check if receiver is the scriptorium
        if ($meeting->scriptorium_id === $userId) {
            return 'دبیر';
        }

        // Get all meeting users
        $meetingUsers = $meeting->meetingUsers;

        // Exclude users who are acting as replacements
        $isReplacement = $meetingUsers->contains(fn ($mu) => $mu->replacement === $userId);
        if ($isReplacement) {
            return 'جانشین';
        }

        // Get the user's record (non-replacement only)
        $meetingUser = $meetingUsers->firstWhere('user_id', $userId);
        if ($meetingUser) {
            if ((bool) $meetingUser->is_guest) {
                return 'مهمان';
            }
            return 'عضو جلسه';
        }

        return 'نقش نامشخص';
    }
    public function getTypeLabelAttributes(): array
    {
        $isSender = $this->sender_id === auth()->id();
        return match ($this->type) {
            'MeetingInvitation' => ['label' => __('دعوتنامه'), 'text' => 'text-blue-600'],
            'MeetingGuestInvitation' => ['label' => __('دعوتنامه مهمان'), 'text' => 'text-teal-600'],
            'MeetingBossInvitation' => ['label' => __('دعوتنامه رییس'), 'text' => 'text-cyan-600'],
            'ReplacementForMeeting' => ['label' => __('دعوتنامه جانشین'), 'text' => 'text-indigo-600'],
            'AcceptInvitation' => ['label' => __('تایید دعوتنامه'), 'text' => 'text-green-600'],
            'DenyInvitation' => ['label' => __('رد دعوتنامه'), 'text' => 'text-red-600'],
            'MeetingConfirmed' => ['label' => __('برگزاری جلسه'), 'text' => 'text-emerald-600'],
            'MeetingCancelled' => ['label' => __('لغو جلسه'), 'text' => 'text-gray-600'],
            'AssignedNewTask' => [ 'label' => $isSender ? __('ارسال اقدام') : __('دریافت اقدام'), 'text' => 'text-yellow-600'],
            'AcceptedTask' => ['label' => __('تایید بند مذاکره'), 'text' => 'text-cyan-600'],
            'DeniedTask' => ['label' => __('رد بند مذاکره'), 'text' => 'text-red-600'],
            'UpdatedTask'=> ['label' => __('بروزرسانی بند'), 'text' => 'text-emerald-600'],
            'TaskSentToScriptorium' => ['label' => __('تکمیل اقدام'), 'text' => 'text-purple-600'],
            default => ['label' => __('نامشخص'), 'text' => 'text-gray-600'],
        };
    }
    public static function taskTypes()
    {
        return [
            'AssignedNewTask',
            'DeniedTask',
            'DeniedTaskNotification',
            'UpdatedTask',
            'TaskSentToScriptorium',
        ];
    }
    public function isTaskRelated()
    {
        return in_array($this->type, self::taskTypes());
    }
    /**
     * Polymorphic relationship to related models (Meeting, Task, etc.)
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
    /**
     * Relationship to the user who sent the notification.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    /**
     * Relationship to the user who received the notification.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
    /**
     * Helper functions for received-message table
     */
    public function getMeetingUserForCurrentUser()
    {
        // Fast and queryless if already eager-loaded
        if ($this->relationLoaded('notifiable') && $this->notifiable?->relationLoaded('meetingUsers')) {
            return $this->notifiable->meetingUsers->firstWhere('user_id', auth()->id());
        }
        // Fallback if not eager-loaded
        return \App\Models\MeetingUser::where('user_id', auth()->id())
            ->where('meeting_id', $this->notifiable_id)
            ->first();
    }
    public function getReplacementUserFullName()
    {
        $meetingUser = $this->getMeetingUserForCurrentUser();
        if ($meetingUser && $meetingUser->replacement) {
            $replacementUser = \App\Models\User::with('user_info')->find($meetingUser->replacement);
            return $replacementUser?->user_info?->full_name ?? 'N/A';
        }
        return null;
    }

}
