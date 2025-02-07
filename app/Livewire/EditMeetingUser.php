<?php

namespace App\Livewire;

use App\Models\BlogImage;
use App\Models\MeetingUser;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class EditMeetingUser extends Component
{
    public $meeting;


    public function render()
    {
        return view('livewire.edit-meeting-user');
    }


    #[Computed]
    public function userIds()
    {
        return MeetingUser::where('meeting_id',$this->meeting)->get();
    }

    public function deleteUser($userId)
    {
        DB::table('meeting_users')->where('user_id',$userId)->delete();
        $this->reset();
    }
}
