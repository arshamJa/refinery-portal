<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\Task;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TaskSent extends Component
{
    public function render()
    {
        return view('livewire.task-sent');
    }

    #[Computed]
    public function meetings()
    {
        // Filter tasks where is_completed is true
        $meetings = Meeting::with(['meetingUsers', 'tasks' => function (Builder $query) {
            $query->where('is_completed', true);
        }])
            ->where('scriptorium', '=', auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=', -1)
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled'])
            ->get();
        // Filter meetings to include only those with tasks
        return $meetings->filter(function (Meeting $meeting){
           return $meeting->tasks->isNotEmpty();
        });
    }
    #[Computed]
    public function tasks()
    {
        return Task::with('user')->where('is_completed',true)->get();
    }
}
