<?php

namespace App\Livewire;

use App\Enums\MeetingUserStatus;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ReceivedMessage extends Component
{
    use WithPagination, WithoutUrlPagination;

    public ?string $search='';
    public $meeting;
    public $title;
    public $meetingId;
    public $meetingUserId;
    public $reason;
    public bool $checkBox = false;
    public $full_name;
    public $p_code;
    public $filter = '';
    public $message_status = '';

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

    #[Computed]
    public function userNotifications(string $type = null)
    {
        $query = Notification::with(['sender.user_info', 'notifiable.meetingUsers'])
            ->where('recipient_id', auth()->id());

        // Filter by message_status including archived
        if ($this->message_status) {
            if ($this->message_status === 'archived') {
                $query->onlyTrashed();
            } elseif ($this->message_status === 'unread') {
                $query->whereNull('recipient_read_at')
                    ->whereNull('deleted_at');  // Only non-archived unread
            } elseif ($this->message_status === 'read') {
                $query->whereNotNull('recipient_read_at')
                    ->whereNull('deleted_at');  // Only non-archived read
            }
        } else {
            // When no filter, exclude archived by default or show all depending on your logic
            $query->whereNull('deleted_at');
        }
        // Optional type filter
        if ($type) {
            $query->where('type', $type);
        }

        return $query->latest()->paginate(10);
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
    public function unreadReceivedCount()
    {
        return Notification::where('recipient_id', auth()->id())
            ->whereNull('recipient_read_at')
            ->count();
    }
    public function openModalDeny($meetingUserId)
    {
        $meetingUser = MeetingUser::find($meetingUserId);
        $this->meetingUserId = $meetingUserId;
        $this->title = $meetingUser->meeting->title;
        $this->dispatch('crud-modal', name: 'deny-invitation');
    }
    /**
     * @throws ValidationException
     */
    public function acceptMeeting($meetingId)
    {
        $userId = auth()->id();

        // Load meeting with scriptorium user info
        $meeting = Meeting::with('scriptorium.user_info')
            ->select('id', 'title', 'date', 'time', 'scriptorium_id')
            ->findOrFail($meetingId);

        $scriptoriumUserId = $meeting->scriptorium_id;

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

        DB::transaction(function () use ($meetingId, $userId, $scriptoriumUserId) {
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
        });

        return to_route('received.message')->with('status', 'شما دعوت به جلسه را پذیرفتید و دبیرجلسه مطلع شد');
    }
    public function IsAlreadyRepresentative(): bool
    {
        return MeetingUser::where('meeting_id', $this->meetingId ?? null)
            ->where('replacement', auth()->id())
            ->exists();
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

        if ($this->IsAlreadyRepresentative()) {
            $this->addError('checkBox', 'شما قبلاً به عنوان جانشین انتخاب شده‌اید و نمی‌توانید جانشین دیگری معرفی کنید.');
            return;
        }

        // ✅ If checkbox is checked, both inputs must be validated BEFORE entering transaction
        if ($this->checkBox) {
            $validated = Validator::make(
                ['full_name' => $this->full_name, 'p_code' => $this->p_code],
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

            $this->full_name = Str::deduplicate(trim($validated['full_name']));
        }

        if ($this->checkBox) {
                $userInfo = UserInfo::where('full_name', $this->full_name)->first();

                if (!$userInfo) {
                    $this->addError('full_name', 'نام و نام خانوادگی یافت نشد');
                    return;
                }

                $userPCode = User::where('id', $userInfo->user_id)->value('p_code');
                if ($userPCode != $this->p_code) {
                    $this->addError('p_code', 'کد پرسنلی با نام مطابقت ندارد');
                    return;
                }

                $meetingId = $this->meetingId ?? optional(MeetingUser::find($this->meetingUserId))->meeting_id;

                $existingReplacement = MeetingUser::where('meeting_id', $meetingId)
                    ->where('replacement', $userInfo->user_id)
                    ->exists();

                if ($existingReplacement) {
                    $this->addError('checkBox', 'شخص جانشین قبلاً برای این جلسه توسط کاربر دیگری انتخاب شده است.');
                    return;
                }

                $meetingUser = MeetingUser::with('meeting')->findOrFail($this->meetingUserId);
                $scriptoriumUserId = $meetingUser->meeting->scriptorium_id;

                if (!$scriptoriumUserId) {
                    $this->addError('checkBox', 'دبیر جلسه تعیین نشده است و نمی‌توان اطلاع‌رسانی کرد.');
                    return;
                }

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

                Notification::create([
                    'type' => 'ReplacementForMeeting',
                    'data' => json_encode(['message' => 'شما در این جلسه در تاریخ ' .
                        $meetingUser->meeting->date . ' و ساعت ' . $meetingUser->meeting->time .
                        ' به عنوان جانشین از طرف ' . auth()->user()->user_info->full_name . ' دعوت شده‌اید.']),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meetingUser->meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $userInfo->user_id,
                ]);

                Notification::create([
                    'type' => 'DenyInvitation',
                    'data' => json_encode(['message' => 'شما این دعوت را رد کردید و آقای/خانم ' .
                        $userInfo->full_name . ' به جای شما در جلسه شرکت خواهد کرد.']),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meetingUser->meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $scriptoriumUserId,
                ]);

            } else {
            $meetingUser = MeetingUser::with('meeting')->findOrFail($this->meetingUserId);
            $scriptoriumUserId = $meetingUser->meeting->scriptorium_id;

            if (!$scriptoriumUserId) {
                    $this->addError('checkBox', 'دبیر جلسه تعیین نشده است و نمی‌توان اطلاع‌رسانی کرد.');
                    return;
                }

            DB::table('meeting_users')
                ->where('id', $this->meetingUserId)
                ->where('user_id', auth()->id())
                ->update([
                        'is_present' => MeetingUserStatus::IS_NOT_PRESENT->value,
                        'reason_for_absent' => $validatedReason['reason'],
                        'updated_at' => now(),
                    ]);

            Notification::create([
                    'type' => 'DenyInvitation',
                    'data' => json_encode(['message' => 'شما دعوت به جلسه را رد کردید.']),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meetingUser->meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $scriptoriumUserId,
                ]);
        }
        return to_route('received.message')->with('status', 'شما دعوت به جلسه را نپذیرفتید و دبیرجلسه مطلع شد');
    }
    protected function normalizeText($text)
    {
        $text = strip_tags($text); // Remove HTML tags
        $text = preg_replace('/\s+/', ' ', trim($text));
        $words = explode(' ', $text);
        return implode(' ', $words);
    }
}
