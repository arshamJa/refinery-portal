<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $fillable = [
        'type', 'data', 'notifiable_type', 'notifiable_id', 'sender_id', 'recipient_id', 'status',
        'sender_read_at','recipient_read_at'
    ];

    // Mark as read for sender
    public function markAsReadBySender()
    {
        $this->update(['sender_read_at' => now()]);
    }

    // Mark as read for recipient
    public function markAsReadByRecipient()
    {
        $this->update(['recipient_read_at' => now()]);
    }

    // Check if the notification is read for sender
    public function isReadBySender()
    {
        return !is_null($this->sender_read_at);
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

                return "{$assignerName} شما را به عنوان جانشین خود برای جلسه «{$meeting->title}» در تاریخ {$meeting->date} ساعت {$meeting->time} انتخاب کرده است.";
            }
        }

        if ($this->type === 'MeetingInvitation') {
            $meeting = $this->notifiable;
            if ($meeting) {
                $isGuest = DB::table('meeting_users')
                    ->where('meeting_id', $meeting->id)
                    ->where('user_id', $this->recipient_id)
                    ->where('is_guest', true)
                    ->exists();

                if ($isGuest) {
                    return "شما به عنوان مهمان به جلسه‌ای با عنوان \"{$meeting->title}\" در تاریخ {$meeting->date} و ساعت {$meeting->time} دعوت شده‌اید.";
                } else {
                    return "شما به جلسه‌ای با عنوان \"{$meeting->title}\" در تاریخ {$meeting->date} و ساعت {$meeting->time} دعوت شده‌اید.";
                }
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

    public function getTypeLabelAttributes(): array
    {
        return match ($this->type) {
            'MeetingInvitation' => ['label' => __('دعوتنامه'), 'text' => 'text-blue-600'],
            'MeetingGuestInvitation' => ['label' => __('دعوتنامه مهمان'), 'text' => 'text-teal-600'],
            'ReplacementForMeeting' => ['label' => __('دعوتنامه جانشین'), 'text' => 'text-indigo-600'],
            'AcceptInvitation' => ['label' => __('تایید دعوتنامه'), 'text' => 'text-green-600'],
            'DenyInvitation' => ['label' => __('رد دعوتنامه'), 'text' => 'text-red-600'],
            'MeetingConfirmed' => ['label' => __('برگزاری جلسه'), 'text' => 'text-emerald-600'],
            'MeetingCancelled' => ['label' => __('لغو جلسه'), 'text' => 'text-gray-600'],
            'AssignedNewTask' => ['label' => __('دریافت اقدام'), 'text' => 'text-yellow-600'],
            'UpdatedTaskTimeOut' => ['label' => __('ویرایش مهلت اقدام'), 'text' => 'text-yellow-600'],
            'UpdatedTaskBody' => ['label' => __('ویرایش بند مذاکره'), 'text' => 'text-purple-600'],
            'DeniedTaskNotification' => ['label' => __('رد اقدام'), 'text' => 'text-rose-600'],
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
}
