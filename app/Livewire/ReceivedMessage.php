<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Traits\MeetingsTasks;
use App\Traits\MessageReceived;
use App\Traits\Organizations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ReceivedMessage extends Component
{
    use MessageReceived,WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;

    public ?string $search='';
    public $meeting;
    public $meetingId;
    public $body;
    public bool $checkBox = false;
    public $full_name;
    public $p_code;

    public $activeTab = 'sent';  // 'sent' or 'received'
    public $filter = '';
    public $message_status = '';
    public $notificationType;

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
        return view('livewire.received-message');
    }

    public function mount()
    {
        $this->activeTab = request()->routeIs('received.message') ? 'received' : 'sent';
    }

    #[Computed]
    public function userNotifications(string $type = null)
    {
        $query = Notification::where('recipient_id', auth()->id())
            ->with(['sender.user_info', 'notifiable']);

        if ($type) {
            $query->where('type', $type);
        }
        // Filter by message status
        if ($this->message_status) {
            switch ($this->message_status) {
                case 'unread':
                    // Assuming unread means sender_read_at IS NULL
                    $query->whereNull('sender_read_at');
                    break;

                case 'read':
                    // Read means sender_read_at IS NOT NULL
                    $query->whereNotNull('sender_read_at');
                    break;
            }
        }
        return $query->latest()->paginate(5);
    }
    /**
     * Mark a notification as read.
     */
    public function markAsRead($notificationId)
    {
        $notification = Notification::where('recipient_id',auth()->id())->findOrFail($notificationId);
        $notification->recipient_read_at = now();
        $notification->save();
        $this->dispatch('notificationRead');
        $this->resetPage();
    }


    #[Computed]
    public function getNotificationMessage($notification)
    {
        $message = json_decode($notification->data)->message ?? 'N/A';
        if ($notification->type === 'AcceptInvitation') {
            $meeting = $notification->notifiable;

            if ($meeting) {
                // Check replacement for the recipient in meeting_users
                $replacementId = DB::table('meeting_users')
                    ->where('meeting_id', $meeting->id)
                    ->where('user_id', $notification->recipient_id)
                    ->value('replacement');  // get replacement user id if any

                // Format date and time — adjust formatting if needed
                $date = $meeting->date; // assuming a string or Carbon instance
                $time = $meeting->time;

                $message = "این شخص دعوت به جلسه \"{$meeting->title}\" برای تاریخ {$date} و ساعت {$time} قبول کرده است";

                if ($replacementId) {
                    $replacementUser = User::find($replacementId);
                    $replacementName = $replacementUser?->user_info?->full_name ?? 'نامشخص';

                    $message .= " و این جانشین او است: {$replacementName}";
                }
                return $message;
            }
            // fallback message if no meeting found
            return 'این شخص دعوت به جلسه را قبول کرده است';

        } elseif ($notification->type === 'DenyInvitation') {
            $meeting = $notification->notifiable;

            if ($meeting) {
                $date = $meeting->date;
                $time = $meeting->time;

                return "این شخص برای جلسه \"{$meeting->title}\" برای تاریخ {$date} و ساعت {$time} دعوت را رد کرده است.";
            }
            // fallback message if no meeting found
            return 'این شخص دعوت به جلسه را رد کرده است.';
        }
        if ($notification->type === 'ReplacementForMeeting') {
            $meeting = $notification->notifiable;

            if ($meeting) {
                $replacementUserId = $notification->recipient_id; // The user who is the replacement (recipient of the notification)
                $replacementUser = User::with('user_info')->find($replacementUserId);
                $replacementName = $replacementUser?->user_info?->full_name ?? 'نامشخص';

                // Find the meeting_user row where replacement = $replacementUserId
                $meetingUser = $meeting->meetingUsers()->where('replacement', $replacementUserId)->with('user.user_info')
                    ->first();

                $assignerName = $meetingUser?->user?->user_info?->full_name ?? 'فرد ناشناس';

                // Get date and time from meeting (adjust if you store these differently)
                $date = $meeting->date;
                $time = $meeting->time;

                // Get meeting title
                $title = $meeting->title ?? 'بدون عنوان جلسه';
                return "{$assignerName} شما را به عنوان جانشین خود برای جلسه «{$title}» در تاریخ {$date} ساعت {$time} انتخاب کرده است.";
            }
        }
        if ($notification->type === 'MeetingInvitation') {
            $meeting = $notification->notifiable;
            if ($meeting) {
                $isGuest = DB::table('meeting_users')
                    ->where('meeting_id', $meeting->id)
                    ->where('user_id', $notification->recipient_id)
                    ->where('is_guest', true)
                    ->exists();

                $date = $meeting->date;
                $time = $meeting->time;

                if ($isGuest) {
                    return "شما به عنوان مهمان به جلسه‌ای با عنوان \"{$meeting->title}\" در تاریخ {$date} و ساعت {$time} دعوت شده‌اید.";
                } else {
                    return "شما به جلسه‌ای با عنوان \"{$meeting->title}\" در تاریخ {$date} و ساعت {$time} دعوت شده‌اید.";
                }
            }
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

    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium','=',auth()->user()->user_info->full_name)
            ->where('status','=',-1)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','status'])
            ->paginate(3);
    }

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with([
            'meeting:id,title,date,time,status,scriptorium',
            'user:id',
            'user.user_info:user_id,full_name'
        ])
            ->where('user_id', auth()->id())
            ->where('read_by_user', false)
            ->latest('created_at')
            ->select('id', 'meeting_id', 'user_id', 'is_present', 'reason_for_absent', 'read_by_user','replacement')
            ->paginate(5);
    }

//    #[Computed]
//    public function taskUsers()
//    {
//        return TaskUser::with([
//            'task' => function ($query) {
//                $query->select('id', 'meeting_id', 'body');
//            },
//            'task.meeting' => function ($query) {
//                $query->select('id', 'title', 'date', 'time', 'scriptorium');
//            },
//            'user.user_info' => function ($query) {
//                $query->select('user_id', 'full_name');
//            }
//        ])
//            ->where('request_task','!=',null)
//            ->whereHas('task.meeting', function ($query) {
//                $query->where('scriptorium', auth()->user()->user_info->full_name);
//            })
//            ->select('id', 'task_id', 'user_id', 'request_task', 'created_at')
//            ->latest('created_at')
//            ->get();
//    }

    public function openModalAccept($meetingId, $notificationId)
    {

        $this->meeting = Meeting::find($meetingId)?->title;
        $this->meetingId = $meetingId;
        $this->notificationType = Notification::findOrFail($notificationId);
        $this->dispatch('crud-modal', name: 'accept-invitation');
    }

    public function openModalDeny($meetingId)
    {
        $this->meeting = Meeting::find($meetingId)?->title;
        $this->meetingId = $meetingId;
        $this->dispatch('crud-modal', name: 'deny-invitation');
    }

    #[Computed]
    public function IsAlreadyRepresentative()
    {
        return DB::table('meeting_users')
            ->where('meeting_id', $this->meetingId)
            ->where('replacement', auth()->id())
            ->exists();
    }

    /**
     * @throws ValidationException
     */
    public function accept($meetingId)
    {
        if ($this->checkBox) {
            $validated = Validator::make(
                ['full_name' => $this->full_name, 'p_code' => $this->p_code, 'checkBox' => $this->checkBox],
                ['full_name' => 'required|string', 'p_code' => 'required|numeric|digits:6'],
                [
                    'full_name.required_if' => 'فیلد نام کامل اجباری است.',
                    'p_code.required_if' => 'فیلد کد پرسنلی اجباری است.',
                ]
            )->validate();

            // Sanitize full_name
            $full_name = Str::deduplicate(trim($this->full_name));

            // Find the user based on full_name
            $userInfo = UserInfo::where('full_name', $full_name)->first();

            // Check if user exists
            if (!$userInfo) {
                $this->addError('full_name', 'نام و نام خانوادگی یافت نشد');
                return;
            }

            // Validate the p_code
            $userPCode = User::where('id', $userInfo->user_id)->pluck('p_code')->first();
            if ($userPCode != $this->p_code) {
                $this->addError('p_code', 'کد پرسنلی با نام مطابقت ندارد');
                return;
            }

            // Check if replacement is already taken for the meeting
            $existingReplacement = MeetingUser::where('meeting_id', $meetingId)
                ->where('replacement', $userInfo->user_id)
                ->exists();

            if ($existingReplacement) {
                $this->addError('full_name', 'شخص جانشین قبلا برای این جلسه توسط کاربر دیگری انتخاب شده است');
                return;
            }

            $meeting = Meeting::select('id', 'title', 'date', 'time', 'scriptorium')->findOrFail($meetingId);
            $scriptoriumUserId = UserInfo::where('full_name', $meeting->scriptorium)->value('user_id');

            MeetingUser::where('meeting_id', $meetingId)->where('user_id', auth()->user()->id)
                ->update(['is_present' => '1','replacement'=>$userInfo->user_id]);

            // Create the replacement user meeting record
            MeetingUser::create([
                'meeting_id' => $meetingId,
                'user_id' => $userInfo->user_id,
                'is_present' => '0',
            ]);
            $notificationMessage = 'شما در این جلسه در تاریخ ' . $meeting->date . ' و ساعت ' . $meeting->time . ' به عنوان جانشین از طرف ' . auth()->user()->user_info->full_name . ' دعوت شده‌اید.';
            Notification::create([
                'type' => 'ReplacementForMeeting',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingId,
                'sender_id' => auth()->id(),
                'recipient_id' => $userInfo->user_id,
            ]);
            // ALSO send AcceptInvitation to scriptorium
            $scriptoriumAcceptMessage = 'شما این دعوت را قبول کردید و آقای/خانم ' . $userInfo->full_name . ' به جای شما در جلسه شرکت خواهد کرد.';
            Notification::create([
                'type' => 'AcceptInvitation',
                'data' => json_encode(['message' => $scriptoriumAcceptMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingId,
                'sender_id' => auth()->id(),
                'recipient_id' => $scriptoriumUserId,
            ]);
        }
        else {
            $meeting = Meeting::select('id', 'title', 'date', 'time', 'scriptorium')->findOrFail($meetingId);
            $scriptoriumUserId = UserInfo::where('full_name', $meeting->scriptorium)->value('user_id');
            $notificationMessage = 'شما دعوت به جلسه را قبول کردید.';
            Notification::create([
                'type' => 'AcceptInvitation',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingId,
                'sender_id' => auth()->id(),
                'recipient_id' => $scriptoriumUserId,
            ]);
            // If checkbox is not checked, just update the user's presence without replacement
            MeetingUser::where('meeting_id', $meetingId)
                ->where('user_id', auth()->id())
                ->update(['is_present' => '1']);
        }
        $this->close();
    }

    protected function normalizeText($text)
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $words = explode(' ', $text);
        $uniqueWords = array_unique($words);
        return implode(' ', $uniqueWords);
    }


    /**
     * @throws ValidationException
     */
    public function deny($meetingId)
    {
        $validated = Validator::make(
            ['body' => $this->body,],
            ['body' => 'required|string|max:255',],
            ['body.required' => 'فیلد دلیل رد درخواست اجباری است.',]
        )->validate();

        MeetingUser::where('meeting_id', $meetingId)
            ->where('user_id', auth()->user()->id)
            ->update([
                'reason_for_absent' => $this->body,
                'is_present' => '-1',
            ]);

        $meeting = Meeting::select('id', 'title', 'date', 'time', 'scriptorium')->findOrFail($meetingId);
        $scriptoriumUserId = UserInfo::where('full_name', $meeting->scriptorium)->value('user_id');

        $notificationMessage = 'شما دعوت به جلسه را رد کردید.';
        Notification::create([
            'type' => 'DenyInvitation',
            'data' => json_encode(['message' => $notificationMessage]),
            'notifiable_type' => Meeting::class,
            'notifiable_id' => $meetingId,
            'sender_id' => auth()->id(),
            'recipient_id' => $scriptoriumUserId,
        ]);
        $this->close();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return to_route('received.message');
    }
}
