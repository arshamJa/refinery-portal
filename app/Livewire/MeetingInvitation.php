<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\User;
use App\Models\UserInfo;
use App\Notifications\Invitation;
use App\Rules\farsi_chs;
use App\Trait\MeetingsTasks;
use App\Trait\Organizations;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MeetingInvitation extends Component
{
    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;
    public $meeting;
    public $meetingId;
    public $body;
    public $checkBox;
    public $full_name;
    public $p_code;
    public ?string $search = '';

    public function render()
    {
        return view('livewire.meeting-invitation');
    }
    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,scriptorium,date,time,is_cancelled')
            ->where('user_id',auth()->user()->id)
            ->select('id','meeting_id','user_id','is_present','replacement')->paginate(3);
    }
    public function accept($meetingId)
    {
        MeetingUser::where('meeting_id',$meetingId)
        ->where('user_id',auth()->user()->id)
        ->update([
            'is_present' => '1'
        ]);
    }
    public function openModalDeny($meeting_id)
    {
        $this->meeting = Meeting::where('id',$meeting_id)->value('title');
        $this->meetingId = $meeting_id;
        $this->dispatch('crud-modal', name: 'deny');
    }
    /**
     * this one will open modal and U can type your reason for absence and select replacement,
     * and it will send invitation to the selected person.
     * @throws ValidationException
     */
    public function deny($meetingId)
    {
        $this->validate([
            'body' => ['required','string','max:255',new farsi_chs()]
        ]);
        if ($this->checkBox || $this->full_name || $this->p_code){
            $this->validate([
                'checkBox' => ['required'],
                'full_name' => ['required','string',new farsi_chs()],
                'p_code' => ['required','numeric','digits:6']
            ]);
            $full_name = Str::deduplicate($this->full_name);
            $userId = UserInfo::where('full_name',$full_name)->value('user_id');
                if ($this->checkBox){
                    if (UserInfo::where('user_id',$userId)->where('full_name',$full_name)->exists()){
                        if (User::where('id',$userId)->where('p_code',$this->p_code)->exists()){
                            if (MeetingUser::where('meeting_id',$meetingId)->value('user_id') == $userId ){
                                throw ValidationException::withMessages([
                                    'full_name' => 'شخص جانشین قبلا دعوت به جلسه شده است'
                                ]);
                            }else{
                                MeetingUser::where('meeting_id',$meetingId)
                                    ->where('user_id',auth()->user()->id)
                                    ->update([
                                        'is_present' => '-1',
                                        'reason_for_absent' => $this->body,
                                        'replacement' => $userId
                                    ]);
                                $meetingUser = MeetingUser::create([
                                   'meeting_id' => $meetingId,
                                    'user_id' => $userId
                                ]);
                                $this->close();
                            }
                        }else{
                            throw ValidationException::withMessages([
                                'p_code' => 'کد پرسنلی وجود ندارد'
                            ]);
                        }
                    }else{
                        throw ValidationException::withMessages([
                            'full_name' => ' نام و نام خانوادگی با کد پرسنلی مطابقت ندارد'
                        ]);
                    }
                }else{
                    throw ValidationException::withMessages([
                        'checkBox' => 'تیک را بزنید'
                    ]);
                }
        }else{
            MeetingUser::where('meeting_id',$meetingId)
                ->where('user_id',auth()->user()->id)
                ->update([
                'is_present' => '-1',
                'reason_for_absent' => $this->body
            ]);
            $this->close();
        }
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return to_route('meeting.invitation');
    }
}
