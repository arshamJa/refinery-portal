<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
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
            ->select('id', 'meeting_id', 'user_id', 'is_present', 'reason_for_absent', 'read_by_user')
            ->paginate(5);
    }




    public $meeting;
    public $meetingId;
    public $body;
    public $checkBox;
    public $full_name;
    public $p_code;


//    #[Computed]
//    public function meetingUsers()
//    {
//        return MeetingUser::with([
//            'meeting:id,title,scriptorium,date,time,status',
//        ])
//            ->where('user_id', auth()->id())
//            ->orderByDesc('created_at')
//            ->select('id', 'meeting_id', 'user_id', 'is_present', 'replacement')
//            ->paginate(5);
//    }


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
    public function deny($meetingId)
    {
        $this->validate([
            'body' => ['required', 'string', 'max:255', new farsi_chs()]
        ]);

        if ($this->checkBox || $this->full_name || $this->p_code) {
            $this->validate([
                'checkBox' => ['accepted'],
                'full_name' => ['required', 'string', new farsi_chs()],
                'p_code' => ['required', 'numeric', 'digits:6']
            ]);

            $full_name = Str::deduplicate($this->full_name);
            $userId = UserInfo::where('full_name', $full_name)->value('user_id');

            if (!$userId ||
                !UserInfo::where('user_id', $userId)->where('full_name', $full_name)->exists()) {
                throw ValidationException::withMessages([
                    'full_name' => 'نام و نام خانوادگی با کد پرسنلی مطابقت ندارد'
                ]);
            }

            if (!User::where('id', $userId)->where('p_code', $this->p_code)->exists()) {
                throw ValidationException::withMessages([
                    'p_code' => 'کد پرسنلی وجود ندارد'
                ]);
            }

            if (MeetingUser::where('meeting_id', $meetingId)->where('user_id', $userId)->exists()) {
                throw ValidationException::withMessages([
                    'full_name' => 'شخص جانشین قبلا دعوت به جلسه شده است'
                ]);
            }

            // Mark current user as absent and assign replacement
            MeetingUser::where('meeting_id', $meetingId)
                ->where('user_id', auth()->id())
                ->update([
                    'is_present' => '-1',
                    'reason_for_absent' => $this->body,
                    'replacement' => $userId
                ]);

            // Invite replacement user
            MeetingUser::create([
                'meeting_id' => $meetingId,
                'user_id' => $userId
            ]);

            return $this->close();
        }

        // No replacement
        MeetingUser::where('meeting_id', $meetingId)
            ->where('user_id', auth()->id())
            ->update([
                'is_present' => '-1',
                'reason_for_absent' => $this->body
            ]);

        return $this->close();
    }

    public function close()
    {
        $this->dispatch('close-modal');
        return to_route('meeting.invitation');
    }
































}
