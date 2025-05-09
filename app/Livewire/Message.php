<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use App\Traits\MeetingsTasks;
use App\Traits\MessageReceived;
use App\Traits\Organizations;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Message extends Component
{
    use MessageReceived,WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;

    public ?string $search='';
    public $meeting;
    public $meetingId;
    public $body;
    public $checkBox = false;
    public $full_name;
    public $p_code;

    public function render()
    {
        return view('livewire.message');
    }
    #[Computed]
    public function invitation()
    {
        return MeetingUser::where('user_id', auth()->id())
            ->where('is_present', '0')
            ->count();
    }
    #[Computed]
    public function read_by_user()
    {
        return MeetingUser::where('user_id', auth()->id())
            ->where('read_by_user', false)
            ->count();
    }
    #[Computed]
    public function sentTaskCount()
    {
        $fullName = auth()->user()->user_info->full_name;

        return Task::whereHas('meeting', function ($query) use ($fullName) {
            $query->where('scriptorium', $fullName);
        })
            ->where('status', true)
            ->count();
    }
    #[Computed]
    public function unreadMeetingUsersCount()
    {
        return MeetingUser::where('is_present', '!=', '0')
            ->where('read_by_scriptorium', false)
            ->whereHas('meeting', function ($query) {
                $query->where('scriptorium', auth()->user()->user_info->full_name);
            })
            ->count();
    }
    #[Computed]
    public function meetingCount()
    {
        return Meeting::where('scriptorium', auth()->user()->user_info->full_name)->count();
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

    #[Computed]
    public function taskUsers()
    {
        return TaskUser::with(['task.meeting'])
            ->where('user_id',auth()->user()->id)
            ->get();
    }



    public function accept($meetingId)
    {
        MeetingUser::where('meeting_id', $meetingId)
            ->where('user_id', auth()->id())
            ->update(['is_present' => '1']);
    }
    public function openModalDeny($meetingId)
    {
        $this->meeting = Meeting::find($meetingId)?->title;
        $this->meetingId = $meetingId;
        $this->dispatch('crud-modal', name: 'deny');
    }
    protected function normalizeText($text)
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $words = explode(' ', $text);
        $uniqueWords = array_unique($words);
        return implode(' ', $uniqueWords);
    }



    protected function rules()
    {
        return [
            // Always validate body
            'body' => 'required|string|max:255',

            // Conditional validation for full_name and p_code when checkbox is checked
            'full_name' => 'required_if:checkBox,true|string',
            'p_code' => 'required_if:checkBox,true|numeric|digits:6',
        ];
    }

    protected function messages()
    {
        return [
            'body.required' => 'دلیل رد درخواست الزامی است و باید حداکثر 255 کاراکتر باشد',
            'body.string' => 'دلیل رد درخواست باید یک رشته باشد',
            'body.max' => 'دلیل رد درخواست نباید بیشتر از 255 کاراکتر باشد',
            'full_name.required_if' => 'نام و نام خانوادگی الزامی است',
            'p_code.required_if' => 'کد پرسنلی الزامی است',
            'p_code.numeric' => 'کد پرسنلی باید عددی باشد',
            'p_code.digits' => 'کد پرسنلی باید شامل 6 رقم باشد',
        ];
    }
    public function deny($meetingId)
    {
        $this->validate();

        // If checkbox is checked, validate full_name and p_code
        if ($this->checkBox) {
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

            // Update the current user's meeting record with the replacement
            MeetingUser::where('meeting_id', $meetingId)
                ->where('user_id', auth()->user()->id)
                ->update([
                    'replacement' => $userInfo->user_id,
                    'reason_for_absent' => $this->body,
                    'is_present' => '-1',
                ]);

            // Create the replacement user meeting record
            MeetingUser::create([
                'meeting_id' => $meetingId,
                'user_id' => $userInfo->user_id,
                'is_present' => '0',
            ]);
        } else {
            // If checkbox is not checked, just update the user's absence without replacement
            MeetingUser::where('meeting_id', $meetingId)
                ->where('user_id', auth()->user()->id)
                ->update([
                    'reason_for_absent' => $this->body,
                    'is_present' => '-1',
                    'replacement' => null,
                ]);
        }
        $this->close();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return to_route('message');
    }

}
