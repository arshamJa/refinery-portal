<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ScriptoriumReport extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $start_date = '';
    public $end_date = '';
    public ?string $search = '';

    public function render()
    {
        return view('livewire.reports');
    }

    public function users()
    {
        return UserInfo::where('user_id',$this->meetings()->meetingUsers()->value('user_id'))->value('full_name');
    }

//    #[Computed]
//    public function meetings()
//    {
//        $startDate = trim($this->start_date);
//        $endDate = trim($this->end_date);
//        if ($startDate && $endDate){
//            $query = Meeting::query();
//                $query->where('date', '>', $startDate)
//                    ->where('date', '<', $endDate);
//            return $query->paginate(3);
//        }else{
//            return Meeting::with('meetingUsers')
//                ->where('scriptorium', '=' ,auth()->user()->user_info->full_name)
//                ->where('is_cancelled', '=','-1')
////                ->where('title', 'like', '%' . $this->search . '%')
////                ->orWhere('scriptorium', 'like', '%' . $this->search . '%')
////                ->orWhere('location', 'like', '%' . $this->search . '%')
////                ->orWhere('date', 'like', '%' . $this->search . '%')
//                ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled'])
//                ->paginate(3);
//        }
//    }
    #[Computed]
    public function meetings()
    {
        $startDate = trim($this->start_date);
        $endDate = trim($this->end_date);
        $search = trim($this->search); // Assuming $this->search holds the search term
        $query = Meeting::query();
        if ($startDate && $endDate) {
            $query->where('date', '>', $startDate)
                ->where('date', '<', $endDate);
        } else {
            $query->where('scriptorium', '=', auth()->user()->user_info->full_name)
                ->where('is_cancelled', '=', '-1');
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('scriptorium', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%');
            });
        }
        return $query->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled'])
            ->paginate(3);
    }

}

