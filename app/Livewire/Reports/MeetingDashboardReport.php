<?php

namespace App\Livewire\Reports;


use App\Enums\TaskStatus;
use App\Models\Meeting;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;


class MeetingDashboardReport extends Component
{

    public $yearData = [];
    public $currentYear = 1404;
    public $currentMonth = 0;

    public function mount()
    {
        $this->fetchData();
    }

    private function fetchData()
    {
        $this->yearData = [];

        $allTasks = DB::table('task_users')->select('sent_date', 'time_out', 'task_status')->get();

        // Loop through years 1404 to 1430
        for ($year = 1404; $year <= 1430; $year++) {
            $this->yearData[$year] = [
                'done' => array_fill(1, 6, 0),
                'notDone' => array_fill(1, 6, 0),
                'delayed' => array_fill(1, 6, 0)
            ];
            // Filter tasks for the current year
            foreach ($allTasks as $task) {
                if ($task->task_status === TaskStatus::PENDING->value) {
                    $this->incrementTaskCount($task, $year, 'notDone');
                } else if ($task->task_status === TaskStatus::IS_COMPLETED->value) {
                    $this->incrementTaskCount($task, $year, 'done');
                    if ($task->time_out && strtotime($task->sent_date) > strtotime($task->time_out)) {
                        $this->incrementTaskCount($task, $year, 'delayed');
                    }
                }
            }
        }
    }
    private function incrementTaskCount($task, $year, $type)
    {
        $dateField = $type === 'notDone' ? 'time_out' : 'sent_date';

        if ($task->$dateField) {
            [$taskYear, $taskMonth] = explode('/', $task->$dateField);
            $taskYear = (int)$taskYear;
            $taskMonth = (int)$taskMonth;

            if ($taskYear === $year) {
                $monthIndex = $this->currentMonth === 0 ? $taskMonth : $taskMonth - 6;
                if ($monthIndex >= 1 && $monthIndex <= 6) {
                    $this->yearData[$year][$type][$monthIndex]++;
                }
            }
        }
    }

    // Optimized Computed Properties
    #[Computed]
    public function tasksOnTime()
    {
        return DB::table('task_users')
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '<=', 'time_out')
            ->count();
    }
    #[Computed]
    public function tasksDoneWithDelay()
    {
        return DB::table('task_users')
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '>', 'time_out')
            ->count();
    }
    #[Computed]
    public function tasksNotDoneOnTime()
    {
        $getDate = list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year,$ja_month,$ja_day);

        return DB::table('task_users')
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date',null)
            ->where('time_out', '>=' , $now)
            ->count();
    }
    #[Computed]
    public function tasksNotDoneWithDelay()
    {
        $getDate = list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year,$ja_month,$ja_day);

        return DB::table('task_users')
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date',null)
            ->where('time_out', '<=' , $now)
            ->count();
    }



    #[Computed]
    public function tasksOnTimePercentage()
    {
        $totalTasks = $this->allTasks();
        return $totalTasks ? (int)(($this->tasksOnTime() / $totalTasks) * 100) : 0;
    }



    #[Computed]
    public function tasksNotDonePercentage()
    {
        $totalTasks = $this->allTasks();
        return $totalTasks ? (int)(($this->tasksNotDone() / $totalTasks) * 100) : 0;
    }




    #[Computed]
    public function tasksDoneWithDelayPercentage()
    {
        $totalTasks = $this->allTasks();
        return $totalTasks ? (int)(($this->tasksDoneWithDelay() / $totalTasks) * 100) : 0;
    }

    #[Computed]
    public function allMeetings()
    {
        return Meeting::count();
    }

    #[Computed]
    public function allTasks()
    {
        return DB::table('task_users')->count();
    }

    public function render()
    {
        return view('livewire.reports.meeting-dashboard-report');
    }

}
