<?php

namespace App\Livewire\Reports;


use App\Models\Meeting;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;


class MeetingDashboardReport extends Component
{


    public function render()
    {
        return view('livewire.reports.meeting-dashboard-report');
    }




    #[Computed]
    public function tasksOnTime()
    {
        return Task::with('meeting')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '<=', 'time_out')
            ->count();
    }

    #[Computed]
    public function tasksOnTimePercentage()
    {
        $totalTasks = Task::whereNotNull('meeting_id')->count(); // Total tasks related to meetings
        if ($totalTasks === 0) {
            return 0; // Avoid division by zero
        }
        $tasksOnTime = Task::with('meeting')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '<=', 'time_out')
            ->count();
        return (int) (($tasksOnTime / $totalTasks) * 100);
    }

    #[Computed]
    public function tasksNotDone()
    {
        return Task::with('meeting')
            ->where('is_completed', false)
            ->where('sent_date', null)
            ->count();
    }

    #[Computed]
    public function tasksNotDonePercentage()
    {
        $totalTasks = Task::whereNotNull('meeting_id')->count(); // Total tasks related to meetings
        if ($totalTasks === 0) {
            return 0; // Avoid division by zero
        }
        $tasksNotDone = Task::with('meeting')
            ->where('is_completed', false)
            ->where('sent_date', null)
            ->count();
        return (int) (($tasksNotDone / $totalTasks) * 100); // Get the integer part of the percentage
    }

    #[Computed]
    public function tasksDoneWithDelay()
    {
        return Task::with('meeting')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '>', 'time_out')
            ->count();
    }

    #[Computed]
    public function tasksDoneWithDelayPercentage()
    {
        $totalTasks = Task::whereNotNull('meeting_id')->count();
        if ($totalTasks === 0) {
            return 0;
        }
        $tasksDoneWithDelay = Task::with('meeting')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '>', 'time_out')
            ->count();
        return (int) (($tasksDoneWithDelay / $totalTasks) * 100);
    }

    #[Computed]
    public function allMeetings()
    {
        return Meeting::count();
    }

    #[Computed]
    public function allTasks()
    {
        return Task::count();
    }

}
