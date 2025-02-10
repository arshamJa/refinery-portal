<?php

namespace App\Livewire;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ScriptoriumReport extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        return view('livewire.scriptorium-report');
    }

    public $start_date;
    public $end_date;

//    public function filter()
//    {
//        return Meeting::where('date', '>', $this->start_date)
//            ->where('date', '<', $this->end_date)->get();
//    }
    public ?string $search = '';

    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers')
            ->where('title', 'like', '%' . $this->search . '%')
            ->where('scriptorium', '=', auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=', -1)
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled'])
            ->paginate(3);
    }


}

