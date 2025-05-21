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
    public bool $unreadOnly = true;
    public $filter = '';


    public function filterMessage()
    {
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
    public function toggleUnreadOnly()
    {
        $this->unreadOnly = !$this->unreadOnly;
        $this->resetPage();
    }
    #[Computed]
    public function userNotifications(string $type = null)
    {
        $query = Notification::where('recipient_id', auth()->id())
            ->with([
                'sender.user_info',
                'notifiable.meetingUsers' => function ($query) {
                    $query->where('user_id', auth()->id());
                },
            ]);

        if ($type) {
            $query->where('type', $type);
        }

        if ($this->unreadOnly) {
            $query->whereNull('recipient_read_at');
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

    public function openModalAccept($meetingId)
    {
        $this->meeting = Meeting::find($meetingId)?->title;
        $this->meetingId = $meetingId;
        $this->dispatch('crud-modal', name: 'accept-invitation');
    }

    public function openModalDeny($meetingId)
    {
        $this->meeting = Meeting::find($meetingId)?->title;
        $this->meetingId = $meetingId;
        $this->dispatch('crud-modal', name: 'deny-invitation');
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
