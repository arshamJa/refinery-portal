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
    public $meeting_id;
    public $body;
    public function render()
    {
        return view('livewire.meeting-invitation');
    }
    public ?string $search = '';

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,scriptorium,date,time,is_cancelled')
            ->where('user_id',auth()->user()->id)
            ->select('id','meeting_id','user_id','is_present')->paginate(3);
    }

    public function denyMeeting($meetingId)
    {
        Meeting::where('id', $meetingId)->update([
            'is_cancelled' => '-1'
        ]);
        $this->close();
    }
    public function openModalDelete($meetingId)
    {
        $this->meeting = Meeting::where('id',$meetingId)->value('title');
        $this->meeting_id = $meetingId;
        $this->dispatch('crud-modal', name: 'delete');
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return redirect()->back();
    }
    public function accept($meetingId)
    {
        $meeting = MeetingUser::find($meetingId);
        $meeting->is_present = '1';
        $meeting->save();
    }


    public function openModalDeny($meetingId)
    {
        $this->meeting = Meeting::where('id',$meetingId)->value('title');
        $this->meeting_id = $meetingId;
        $this->dispatch('crud-modal', name: 'delete');
    }


    public $checkBox;
    public $full_name;
    public $p_code;
    /**
     * @throws ValidationException
     */
    public function deny($meetingId)
    {
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
                        if (User::where('p_code',$this->p_code)->exists()){
                            if (MeetingUser::where('meeting_id',$meetingId)->where('user_id',$userId)->exists()){
                                throw ValidationException::withMessages([
                                    'full_name' => 'شخص جانشین قبلا دعوت به جلسه شده است'
                                ]);
                            }else{
                                dd('you can send invitation to this person');
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
            $meeting = MeetingUser::find($meetingId);
            $meeting->is_present = '-1';
            $meeting->reason_for_absent = $this->body;
            $meeting->save();
            $this->close();
        }

    }
}
