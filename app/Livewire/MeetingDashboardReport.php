<?php

namespace App\Livewire;


use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;


class MeetingDashboardReport extends Component
{
    public function render()
    {
        return view('livewire.meeting-dashboard-report');
    }

    #[Computed]
    public function tasksOnTime()
    {
        return Task::with('meeting')
            ->whereColumn('sent_date', '<=', 'time_out')
            ->count();
    }

    #[Computed]
    public function tasksNotDone()
    {
        return Task::with('meeting')
            ->where('sent_date',null)
            ->count();
    }

    #[Computed]
    public function tasksDoneWithDelay()
    {
        return Task::with('meeting')
            ->whereColumn('sent_date', '>', 'time_out')
            ->count();
    }

}
