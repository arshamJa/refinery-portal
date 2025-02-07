<?php

namespace App\Livewire;

use App\Models\Meeting;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Meetings extends Component
{
    public function render()
    {
        return view('livewire.meetings');
    }

    public ?string $search = '';

    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium','=',auth()->user()->user_info->full_name)
            ->where('is_cancelled','=',-1)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
            ->paginate(3);
    }
}
