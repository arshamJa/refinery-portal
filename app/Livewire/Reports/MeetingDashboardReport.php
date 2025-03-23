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
    public $yearData = [];
    public $currentYear = 1404; // Default year
    public $currentMonth = 0; // Default month (0 for first 6, 1 for last 6)

    public function mount()
    {
        $this->fetchData();
    }

    private function fetchData()
    {
        $this->yearData = [];

        // Fetch all tasks once
        $allTasks = DB::table('tasks')
            ->select('sent_date', 'time_out', 'is_completed')
            ->get();

        // Loop through years 1404 to 1430
        for ($year = 1404; $year <= 1430; $year++) {
            $processedData = [
                'done' => array_fill(1, 12, 0),
                'notDone' => array_fill(1, 12, 0),
                'delayed' => array_fill(1, 12, 0),
            ];

            // Filter tasks for the current year
            $tasks = $allTasks->filter(function ($task) use ($year) {
                if ($task->time_out !== null) {
                    $timeOutParts = explode('/', $task->time_out);
                    if (count($timeOutParts) === 3 && (int) $timeOutParts[0] === $year) {
                        return true;
                    }
                }
                if ($task->sent_date !== null) {
                    $sentDateParts = explode('/', $task->sent_date);
                    if (count($sentDateParts) === 3 && (int) $sentDateParts[0] === $year) {
                        return true;
                    }
                }
                return false;
            });

            foreach ($tasks as $task) {
                // Count notDone tasks based on time_out
                if ($task->is_completed === 0) {
                    if ($task->time_out !== null) {
                        $timeOutParts = explode('/', $task->time_out);
                        if (count($timeOutParts) === 3) {
                            $taskYear = (int) $timeOutParts[0];
                            $taskMonth = (int) $timeOutParts[1];

                            if ($taskYear === $year) {
                                if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
                                    $processedData['notDone'][$taskMonth]++;
                                } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
                                    $processedData['notDone'][$taskMonth - 6]++;
                                }
                            }
                        }
                    }
                    continue; // Skip further processing for is_completed = 0
                }
                // Count done and delayed tasks based on sent_date
                if ($task->sent_date !== null) {
                    $sentDateParts = explode('/', $task->sent_date);
                    if (count($sentDateParts) === 3) {
                        $taskYear = (int) $sentDateParts[0];
                        $month = (int) $sentDateParts[1];
                        if ($taskYear === $year) {
                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
                                if ($task->is_completed === 1) {
                                    $processedData['done'][$month]++;
                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
                                        $processedData['delayed'][$month]++;
                                    }
                                }
                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
                                if ($task->is_completed === 1) {
                                    $processedData['done'][$month - 6]++;
                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
                                        $processedData['delayed'][$month - 6]++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->yearData[$year] = $processedData;
        }
    }

    public function updatedCurrentYear()
    {
        $this->fetchData();
    }

    public function updatedCurrentMonth()
    {
        $this->fetchData(); // Refetch data when month changes
    }

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
