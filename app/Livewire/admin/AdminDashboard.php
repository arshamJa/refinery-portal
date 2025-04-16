<?php

namespace App\Livewire\admin;
use App\Models\Department;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\User;
use App\Traits\MeetingsTasks;
use App\Traits\MessageReceived;
use App\Traits\Organizations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AdminDashboard extends Component
{

    use Organizations,MessageReceived;



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


    // this is for taskChart
    public $yearData = [];
    public $currentYear = 1404; // Default year
    public $currentMonth = 0; // Default month (0 for first 6, 1 for last 6)

    // this is for meetingChart
    public $yearDataMeeting = [];
    public $currentYearMeeting = 1404; // Default year
    public $currentMonthMeeting = 0; // Default month (0 for first 6, 1 for last 6)
    public $allMeetings = 0;
    public $allCancelledMeetings = 0;

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
    public function mount()
    {
        $this->fetchData();
        $this->fetchDataMeeting();
        $this->calculateTotals();
    }
//    public function updatedCurrentYear()
//    {
//        $this->fetchData();
//    }
//    public function updatedCurrentMonth()
//    {
//        $this->fetchData(); // Refetch data when month changes
//    }
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
//    public function updatedCurrentYearMeeting()
//    {
//        $this->fetchDataMeeting();
//        $this->calculateTotals();
//    }
//    public function updatedCurrentMonthMeeting()
//    {
//        $this->fetchDataMeeting();
//        $this->calculateTotals();
//    }

    private function fetchDataMeeting()
    {
        $this->yearDataMeeting = [];

        // Fetch all meetings once
        $allMeetings = Meeting::all();

        for ($year = 1404; $year <= 1450; $year++) {
            $processedData = [
                'cancelled' => array_fill(1, 12, 0),
                'notCancelled' => array_fill(1, 12, 0),
                'pending' => array_fill(1, 12, 0),
            ];

            // Filter meetings for the current year
            $meetings = $allMeetings->filter(function ($meeting) use ($year) {
                return date('Y', strtotime($meeting->date)) == $year;
            });

            foreach ($meetings as $meeting) {
                $month = (int) date('n', strtotime($meeting->date));

                if ($this->currentMonthMeeting === 0 && $month >= 1 && $month <= 6) {
                    if ($meeting->is_cancelled === 1) {
                        $processedData['cancelled'][$month]++;
                    } elseif ($meeting->is_cancelled === -1) {
                        $processedData['notCancelled'][$month]++;
                    } else {
                        $processedData['pending'][$month]++;
                    }
                } elseif ($this->currentMonthMeeting === 1 && $month >= 7 && $month <= 12) {
                    if ($meeting->is_cancelled === 1) {
                        $processedData['cancelled'][$month - 6]++;
                    } elseif ($meeting->is_cancelled === -1) {
                        $processedData['notCancelled'][$month - 6]++;
                    } else {
                        $processedData['pending'][$month - 6]++;
                    }
                }
            }
            $this->yearDataMeeting[$year] = $processedData;
        }
    }
    private function calculateTotals()
    {
        $this->allMeetings = 0;
        $this->allCancelledMeetings = 0;
        $meetings = Meeting::where('date', $this->currentYearMeeting)->get();
        foreach($meetings as $meeting){
            $month = (int) date('n', strtotime($meeting->date));
            if($this->currentMonthMeeting === 0 && $month >=1 && $month<=6){
                $this->allMeetings++;
                if($meeting->is_cancelled ===1){
                    $this->allCancelledMeetings++;
                }

            } else if ($this->currentMonthMeeting === 1 && $month >=7 && $month<=12){
                $this->allMeetings++;
                if($meeting->is_cancelled ===1){
                    $this->allCancelledMeetings++;
                }
            }
        }
    }
    #[Computed]
    public function users()
    {
        return User::all()->count();
    }
    #[Computed]
    public function departments()
    {
        return Department::all()->count();
    }

}
