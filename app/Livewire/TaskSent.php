<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class TaskSent extends Component
{
    public function render()
    {
        return view('livewire.task-sent');
    }

    #[Computed]
    public function meetings()
    {
//        $meetings = Meeting::with('meetingUsers','tasks')
//            ->where('scriptorium','=',auth()->user()->user_info->full_name)
//            ->where('is_cancelled','=',-1)
//            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
//            ->get();

        $meetings = Meeting::with(['meetingUsers', 'tasks' => function ($query) {
            $query->where('is_completed', 1); // Filter tasks where is_completed is 1
        }])
            ->where('scriptorium', '=', auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=', -1)
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled'])
            ->get();
        // Filter meetings to include only those with tasks
        return $meetings->filter(function ($meeting){
           return $meeting->tasks->isNotEmpty();
        });
    }
    #[Computed]
    public function tasks()
    {
        return Task::with('user')->where('is_completed','=','1')->get();
    }
}
