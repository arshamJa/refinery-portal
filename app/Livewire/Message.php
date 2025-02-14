<?php

namespace App\Livewire;

use App\Models\MeetingUser;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Message extends Component
{
    public function render()
    {
        return view('livewire.message');
    }

    #[Computed]
    public function invitation()
    {
        return MeetingUser::where('user_id',auth()->user()->id)->where('is_present',0)->count();
    }
}
