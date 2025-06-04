<?php

namespace App\Livewire;

use App\Enums\MeetingUserStatus;
use App\Jobs\SendNotificationJob;
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
    public $title;
    public $meetingId;
    public $meetingUserId;
//    public $body;
    public $reason;
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
    public function meetingUsers()
    {
        return MeetingUser::with([
            'meeting:id,title,date,time,status,scriptorium',
            'user:id',
            'user.user_info:user_id,full_name'
        ])
            ->where('user_id', auth()->id())
            ->select('id', 'meeting_id', 'user_id', 'is_present', 'reason_for_absent', 'read_by_user','replacement')
            ->paginate(5);
    }

    public function openModalDeny($meetingUserId)
    {

        $meetingUser = MeetingUser::find($meetingUserId);
        $this->meetingUserId = $meetingUserId;
        $this->title = $meetingUser->meeting->title;
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
    public function acceptMeeting($meetingId)
    {

        $userId = auth()->id();

        $meeting = Meeting::select('id', 'title', 'date', 'time', 'scriptorium')->findOrFail($meetingId);
        $scriptoriumUserId = UserInfo::where('full_name', $meeting->scriptorium)->value('user_id');

        // Check if user is invited
        $isInvited = DB::table('meeting_users')
            ->where('meeting_id', $meetingId)
            ->where('user_id', $userId)
            ->exists();

        if (!$isInvited) {
            abort(403, 'شما مجاز به شرکت در این جلسه نیستید.');
        }

        // Check if user has already responded
        $alreadyResponded = DB::table('meeting_users')
            ->where('meeting_id', $meetingId)
            ->where('user_id', $userId)
            ->where('is_present', '!=', MeetingUserStatus::PENDING->value)
            ->exists();

        if ($alreadyResponded) {
            abort(400, 'شما قبلاً به این دعوت پاسخ داده‌اید.');
        }

        DB::transaction(function () use ($meetingId, $userId, $meeting, $scriptoriumUserId) {
            DB::table('meeting_users')
                ->where('meeting_id', $meetingId)
                ->where('user_id', $userId)
                ->update(['is_present' => MeetingUserStatus::IS_PRESENT->value]);

            $notificationMessage = 'شما دعوت به جلسه را قبول کردید.';
            Notification::create([
                'type' => 'AcceptInvitation',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingId,
                'sender_id' => $userId,
                'recipient_id' => $scriptoriumUserId,
            ]);
//            SendNotificationJob::dispatch([
//                'type' => 'AcceptInvitation',
//                'data' => json_encode(['message' => 'شما دعوت به جلسه را قبول کردید.']),
//                'notifiable_type' => Meeting::class,
//                'notifiable_id' => $meetingId,
//                'sender_id' => $userId,
//                'recipient_id' => $scriptoriumUserId,
//            ]);
        });

        return to_route('received.message')->with('status', 'شما دعوت به جلسه را پذیرفتید و دبیرجلسه مطلع شد');
    }



    /**
     * @throws ValidationException
     */
    public function deny()
    {
        $allowedReasons = ['دلیل اول', 'دلیل دوم', 'دلیل سوم', 'دلیل چهارم'];
        $validatedReason = Validator::make(
            ['reason' => $this->reason],
            ['reason' => ['required', 'string', 'in:' . implode(',', $allowedReasons)]],
            [
                'reason.required' => 'فیلد دلیل رد درخواست اجباری است.',
                'reason.in' => 'دلیل انتخاب شده معتبر نیست.'
            ]
        )->validate();

        if ($this->checkBox) {
            $validated = Validator::make(
                ['full_name' => $this->full_name, 'p_code' => $this->p_code, 'checkBox' => $this->checkBox],
                [
                    'full_name' => 'required|string|max:255',
                    'p_code' => 'required|numeric|digits:6',
                ],
                [
                    'full_name.required' => 'فیلد نام کامل اجباری است.',
                    'p_code.required' => 'فیلد کد پرسنلی اجباری است.',
                    'p_code.numeric' => 'کد پرسنلی باید عدد باشد.',
                    'p_code.digits' => 'کد پرسنلی باید 6 رقمی باشد.',
                ]
            )->validate();

            $full_name = Str::deduplicate(trim($validated['full_name']));

            $userInfo = UserInfo::where('full_name', $full_name)->first();

            if (!$userInfo) {
                $this->addError('full_name', 'نام و نام خانوادگی یافت نشد');
                return;
            }

            $userPCode = User::where('id', $userInfo->user_id)->pluck('p_code')->first();
            if ($userPCode != $this->p_code) {
                $this->addError('p_code', 'کد پرسنلی با نام مطابقت ندارد');
                return;
            }

            $existingReplacement = MeetingUser::where('id', $this->meetingUserId)
                ->where('replacement', $userInfo->user_id)
                ->exists();

            if ($existingReplacement) {
                $this->addError('full_name', 'شخص جانشین قبلا برای این جلسه توسط کاربر دیگری انتخاب شده است');
                return;
            }
            $meetingUser = MeetingUser::with('meeting:id,title,date,time,scriptorium')->findOrFail($this->meetingUserId);
            $scriptoriumUserId = UserInfo::where('full_name', $meetingUser->meeting->scriptorium)->value('user_id');

            DB::table('meeting_users')
                ->where('id', $this->meetingUserId)
                ->where('user_id', auth()->id())
                ->update([
                    'is_present' => MeetingUserStatus::IS_NOT_PRESENT->value,
                    'replacement' => $userInfo->user_id,
                    'reason_for_absent' => $validatedReason['reason'],
                    'updated_at' => now(),
                ]);

            DB::table('meeting_users')->insert([
                'meeting_id' => $meetingUser->meeting->id,
                'user_id' => $userInfo->user_id,
                'is_present' => MeetingUserStatus::PENDING->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $notificationMessage = 'شما در این جلسه در تاریخ ' . $meetingUser->meeting->date .
                ' و ساعت ' . $meetingUser->meeting->time . ' به عنوان جانشین از طرف ' .
                auth()->user()->user_info->full_name . ' دعوت شده‌اید.';

            Notification::create([
                'type' => 'ReplacementForMeeting',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingUser->meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $userInfo->user_id,
            ]);

            $scriptoriumAcceptMessage = 'شما این دعوت را رد کردید و آقای/خانم ' .
                $userInfo->full_name . ' به جای شما در جلسه شرکت خواهد کرد.';

            Notification::create([
                'type' => 'DenyInvitation',
                'data' => json_encode(['message' => $scriptoriumAcceptMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingUser->meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $scriptoriumUserId,
            ]);

        } else {
            // Deny without selecting replacement
            DB::table('meeting_users')
                ->where('id', $this->meetingUserId)
                ->where('user_id', auth()->id())
                ->update([
                    'is_present' => MeetingUserStatus::IS_NOT_PRESENT->value,
                    'reason_for_absent' => $validatedReason['reason'],
                    'updated_at' => now(),
                ]);

            $meetingUser = MeetingUser::with('meeting:id,title,date,time,scriptorium')->findOrFail($this->meetingUserId);
            $scriptoriumUserId = UserInfo::where('full_name', $meetingUser->meeting->scriptorium)->value('user_id');

            $notificationMessage = 'شما دعوت به جلسه را رد کردید.';

            DB::table('notifications')->insert([
                'type' => 'DenyInvitation',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meetingUser->meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $scriptoriumUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return to_route('received.message')->with('status', 'شما دعوت به جلسه را نپذیرفتید و دبیرجلسه مطلع شد');
    }

    protected function normalizeText($text)
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $words = explode(' ', $text);
        return implode(' ', $words);
    }
}
