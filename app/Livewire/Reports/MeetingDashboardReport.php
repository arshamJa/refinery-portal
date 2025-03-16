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
    public $currentYear = 1403; // Default year
    public $currentMonth = 0; // Default month (0 for first 6, 1 for last 6)

    public function mount()
    {
        $this->fetchData();
    }

    public function updatedCurrentYear()
    {
        $this->fetchData();
    }

    public function updatedCurrentMonth()
    {
        $this->fetchData(); // Refetch data when month changes
    }
    private function fetchData()
    {
        $this->yearData = [];

        // Loop through years 1403 to 1450
        for ($year = 1403; $year <= 1450; $year++) {
            $processedData = [
                'done' => array_fill(1, 12, 0),
                'notDone' => array_fill(1, 12, 0),
                'delayed' => array_fill(1, 12, 0),
            ];

            // Fetch tasks for the current year
            $tasks = DB::table('tasks')
                ->select('sent_date', 'time_out', 'is_completed')
                ->get();

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
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                // Count notDone tasks based on time_out
//                if ($task->is_completed === 0) {
//                    if ($task->time_out !== null) {
//                        $timeOutParts = explode('/', $task->time_out);
//                        if (count($timeOutParts) === 3) {
//                            $taskYear = (int) $timeOutParts[0];
//                            $taskMonth = (int) $timeOutParts[1];
//
//                            if ($taskYear === $year) {
//                                if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
//                                    $processedData['notDone'][$taskMonth]++;
//                                } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
//                                    $processedData['notDone'][$taskMonth - 6]++;
//                                }
//                            }
//                        }
//                    }
//                    continue; // Skip further processing for is_completed = 0
//                }
//
//                // Count done and delayed tasks based on sent_date
//                if ($task->sent_date !== null) {
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $taskYear = (int) $sentDateParts[0];
//                        $month = (int) $sentDateParts[1];
//
//                        if ($taskYear === $year) {
//                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month]++;
//                                    // Check for delayed tasks
//                                    if ($task->time_out !== null) {
//                                        $timeOutParts = explode('/', $task->time_out);
//                                        if (count($timeOutParts) === 3) {
//                                            $timeOutYear = (int) $timeOutParts[0];
//                                            $timeOutMonth = (int) $timeOutParts[1];
//                                            $timeOutDay = (int) $timeOutParts[2];
//                                            $sentDateParts = explode('/', $task->sent_date);
//                                            $sentYear = (int) $sentDateParts[0];
//                                            $sentMonth = (int) $sentDateParts[1];
//                                            $sentDay = (int) $sentDateParts[2];
//
//                                            $timeOutTimestamp = mktime(0, 0, 0, $timeOutMonth, $timeOutDay, $timeOutYear);
//                                            $sentTimestamp = mktime(0, 0, 0, $sentMonth, $sentDay, $sentYear);
//
//                                            if ($sentTimestamp > $timeOutTimestamp) {
//                                                $processedData['delayed'][$month]++;
//                                            }
//                                        }
//                                    }
//                                }
//                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month - 6]++;
//                                    // Check for delayed tasks
//                                    if ($task->time_out !== null) {
//                                        $timeOutParts = explode('/', $task->time_out);
//                                        if (count($timeOutParts) === 3) {
//                                            $timeOutYear = (int) $timeOutParts[0];
//                                            $timeOutMonth = (int) $timeOutParts[1];
//                                            $timeOutDay = (int) $timeOutParts[2];
//                                            $sentDateParts = explode('/', $task->sent_date);
//                                            $sentYear = (int) $sentDateParts[0];
//                                            $sentMonth = (int) $sentDateParts[1];
//                                            $sentDay = (int) $sentDateParts[2];
//
//                                            $timeOutTimestamp = mktime(0, 0, 0, $timeOutMonth, $timeOutDay, $timeOutYear);
//                                            $sentTimestamp = mktime(0, 0, 0, $sentMonth, $sentDay, $sentYear);
//
//                                            if ($sentTimestamp > $timeOutTimestamp) {
//                                                $processedData['delayed'][$month - 6]++;
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//            foreach ($tasks as $task) {
//                // Count notDone tasks based on time_out
//                if ($task->is_completed === 0) {
//                    if ($task->time_out !== null) {
//                        $timeOutParts = explode('/', $task->time_out);
//                        if (count($timeOutParts) === 3) {
//                            $taskYear = (int) $timeOutParts[0];
//                            $taskMonth = (int) $timeOutParts[1];
//
//                            if ($taskYear === $year) {
//                                if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
//                                    $processedData['notDone'][$taskMonth]++;
//                                } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
//                                    $processedData['notDone'][$taskMonth - 6]++;
//                                }
//                            }
//                        }
//                    }
//                    continue; // Skip further processing for is_completed = 0
//                }
//
//                // Count done and delayed tasks based on sent_date
//                if ($task->sent_date !== null) {
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $taskYear = (int) $sentDateParts[0];
//                        $month = (int) $sentDateParts[1];
//
//                        if ($taskYear === $year) {
//                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month]++;
//                                    }
//                                }
//                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month - 6]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month - 6]++;
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                // Count notDone tasks based on time_out
//                if ($task->is_completed === 0) {
//                    if ($task->time_out !== null) {
//                        $timeOutParts = explode('/', $task->time_out);
//                        if (count($timeOutParts) === 3) {
//                            $taskYear = (int) $timeOutParts[0];
//                            $taskMonth = (int) $timeOutParts[1];
//
//                            if ($taskYear === $year) {
//                                if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
//                                    $processedData['notDone'][$taskMonth]++;
//                                } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
//                                    $processedData['notDone'][$taskMonth - 6]++;
//                                }
//                            }
//                        }
//                    }
//                    continue; // Skip further processing for is_completed = 0
//                }
//
//                // Count done and delayed tasks based on sent_date
//                if ($task->sent_date !== null) {
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $taskYear = (int) $sentDateParts[0];
//                        $month = (int) $sentDateParts[1];
//
//                        if ($taskYear === $year) {
//                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month]++;
//                                    }
//                                }
//                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month - 6]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month - 6]++;
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                if ($task->is_completed === 0) {
//                    // If is_completed is 0, count it as notDone
//                    $taskMonth = 0; // Default month
//                    $taskYear = 0; // Default year
//
//                    if ($task->time_out !== null) {
//                        $timeOutParts = explode('/', $task->time_out);
//                        if (count($timeOutParts) === 3) {
//                            $taskYear = (int) $timeOutParts[0];
//                            $taskMonth = (int) $timeOutParts[1];
//                        }
//                    }
//
//                    if ($taskYear === $year) {
//                        if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
//                            $processedData['notDone'][$taskMonth]++;
//                        } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
//                            $processedData['notDone'][$taskMonth - 6]++;
//                        }
//                    }
//
//                    continue; // Skip further processing for is_completed = 0
//                }
//
//                if ($task->sent_date !== null) { // Only process sent_date if it's not null
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $taskYear = (int) $sentDateParts[0];
//                        $month = (int) $sentDateParts[1];
//
//                        if ($taskYear === $year) {
//                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month]++;
//                                    }
//                                }
//                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month - 6]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month - 6]++;
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                if ($task->is_completed === 0) {
//                    // If is_completed is 0, count it as notDone
//                    $taskMonth = 0; // Default month
//                    $taskYear = 0; // Default year
//
//                    if ($task->time_out !== null) {
//                        $timeOutParts = explode('/', $task->time_out);
//                        if (count($timeOutParts) === 3) {
//                            $taskYear = (int) $timeOutParts[0];
//                            $taskMonth = (int) $timeOutParts[1];
//                        }
//                    }
//
//                    if ($taskYear === $year) {
//                        if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
//                            $processedData['notDone'][$taskMonth]++;
//                        } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
//                            $processedData['notDone'][$taskMonth - 6]++;
//                        }
//                    }
//
//                    continue; // Skip further processing for is_completed = 0
//                }
//
//                if ($task->sent_date === null) {
//                    continue; // Skip tasks with null sent_date after checking is_completed.
//                }
//
//                $sentDateParts = explode('/', $task->sent_date);
//                if (count($sentDateParts) === 3) {
//                    $taskYear = (int) $sentDateParts[0];
//                    $month = (int) $sentDateParts[1];
//
//                    if ($taskYear === $year) {
//                        if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month]++;
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month]++;
//                                }
//                            }
//                        } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month - 6]++;
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month - 6]++;
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                if ($task->is_completed === 0) { // Check is_completed first
//                    // If is_completed is 0, count it as notDone
//                    if ($this->currentMonth === 0 && $year === 1403) {
//                        $processedData['notDone'][12]++; //Assuming the month is 12 for year 1403, change if needed.
//                    } else if ($this->currentMonth === 1 && $year === 1404){
//                        $processedData['notDone'][2]++; //Assuming the month is 2 for year 1404, change if needed.
//                    }
//                    continue; // Skip further processing for is_completed = 0
//                }
//                if ($task->sent_date === null) {
//                    continue; // Skip tasks with null sent_date after checking is_completed.
//                }
//                $sentDateParts = explode('/', $task->sent_date);
//                if (count($sentDateParts) === 3) {
//                    $taskYear = (int) $sentDateParts[0];
//                    $month = (int) $sentDateParts[1];
//
//                    if ($taskYear === $year) {
//                        if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month]++;
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month]++;
//                                }
//                            }else{
//                                $processedData['notDone'][$month]++;
//                            }
//                        } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month - 6]++;
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month - 6]++;
//                                }
//                            } else {
//                                $processedData['notDone'][$month - 6]++;
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }

//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                $sentDateParts = explode('/', $task->sent_date);
//                if (count($sentDateParts) === 3) {
//                    $taskYear = (int) $sentDateParts[0];
//                    $month = (int) $sentDateParts[1];
//
//                    if ($taskYear === $year) {
//                        if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                            if ($task->is_completed) {
//                                $processedData['done'][$month]++;
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month]++;
//                                }
//                            }else{
//                                $processedData['notDone'][$month]++;
//                            }
//                        }
//                        elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                            if ($task->is_completed) {
//                                $processedData['done'][$month - 6]++;
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month - 6]++;
//                                }
//                            }else{
//                                $processedData['notDone'][$month - 6]++;
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }
//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
////                'notDone' => array_fill(1, 12, 0),
//                'notDone' => 0,
//                'delayed' => array_fill(1, 12, 0),
//                'nullSentDate' => 0,
//                'notCompleted' => array_fill(1, 12, 0), // Added back 'notCompleted'
//                //'beforeTimeout' => array_fill(1, 12, 0),
//                //'afterTimeout' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year (Corrected year filter)
//            $tasks = DB::table('tasks')
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                if ($task->is_completed === 0) {
//                    $processedData['nullSentDate']++;
//                } else {
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $taskYear = (int) $sentDateParts[0]; // Extract the year
//                        $month = (int) $sentDateParts[1];
//
//                        if ($taskYear === $year) { // Check if the task's year matches the loop's year
//                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month]++;
//                                } else {
//                                    $processedData['notCompleted'][$month]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month]++;
//                                        // $processedData['afterTimeout'][$month]++;
//                                    } else {
//                                        $processedData['notDone'][$month]++;
//                                        // $processedData['beforeTimeout'][$month]++;
//                                    }
//                                }
//                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                                if ($task->is_completed === 1) {
//                                    $processedData['done'][$month - 6]++;
//                                } else {
//                                    $processedData['notCompleted'][$month - 6]++;
//                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                        $processedData['delayed'][$month - 6]++;
//                                        // $processedData['afterTimeout'][$month - 6]++;
//                                    } else {
//                                        $processedData['notDone'][$month - 6]++;
//                                        // $processedData['beforeTimeout'][$month - 6]++;
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }

//    private function fetchData()
//    {
//        $this->yearData = [];
//
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//                'nullSentDate' => 0,
////                'notCompleted' => array_fill(1, 12, 0),
////                'beforeTimeout' => array_fill(1, 12, 0),
////                'afterTimeout' => array_fill(1, 12, 0),
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->where('sent_date', $year)
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//                if ($task->sent_date === null) {
//                    $processedData['nullSentDate']++;
//                } else {
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $month = (int) $sentDateParts[1];
//
//                        if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month]++;
//                            } else {
//                                $processedData['notCompleted'][$month]++; // Increment notCompleted here
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month]++;
////                                    $processedData['afterTimeout'][$month]++;
//                                } else {
//                                    $processedData['notDone'][$month]++;
////                                    $processedData['beforeTimeout'][$month]++;
//                                }
//                            }
//                        } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month - 6]++;
//                            } else {
//                                $processedData['notCompleted'][$month - 6]++; // Increment notCompleted here
//                                if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month - 6]++;
////                                    $processedData['afterTimeout'][$month - 6]++;
//                                } else {
//                                    $processedData['notDone'][$month - 6]++;
////                                    $processedData['beforeTimeout'][$month - 6]++;
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }


//    private function fetchData()
//    {
//        $this->yearData = [];
//        // Loop through years 1403 to 1450
//        for ($year = 1403; $year <= 1450; $year++) {
//            $processedData = [
//                'done' => array_fill(1, 12, 0),
//                'notDone' => array_fill(1, 12, 0),
//                'delayed' => array_fill(1, 12, 0),
//                'nullSentDate' => 0, // Add a counter for null sent_date
//                'notCompleted' => array_fill(1, 12, 0), // Add counter for not completed
//                'beforeTimeout' => array_fill(1, 12, 0), // Tasks with sent_date before time_out
//                'afterTimeout' => array_fill(1, 12, 0), // Tasks with sent_date after time_out
//            ];
//
//            // Fetch tasks for the current year
//            $tasks = DB::table('tasks')
//                ->where('sent_date', $year) // Filter by year
//                ->select('sent_date', 'time_out', 'is_completed')
//                ->get();
//
//            foreach ($tasks as $task) {
//
//                if ($task->is_completed == null) {
//                    // Task with null sent_date
//                    $processedData['nullSentDate']++;
//
//                } else {
//                    $sentDateParts = explode('/', $task->sent_date);
//                    if (count($sentDateParts) === 3) {
//                        $month = (int) $sentDateParts[1];
//                        // Filter by month range
//                        if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
//                            // فروردین - شهریور
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month]++;
//                            } else {
//                                if ($task->time_out  && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month]++;
//                                } else {
//                                    $processedData['notDone'][$month]++;
//                                }
//                            }
//                        } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
//                            // مهر - اسفند
//                            if ($task->is_completed === 1) {
//                                $processedData['done'][$month - 6]++; // Adjust month index
//                            } else {
//                                if ($task->time_out && strtotime($task->sent_date) > strtotime($task->time_out)) {
//                                    $processedData['delayed'][$month - 6]++; // Adjust month index
//                                } else {
//                                    $processedData['notDone'][$month - 6]++; // Adjust month index
//                                }
//                            }
//                        }
////                    if ($task->is_completed === 1) { // Task is completed
////                        $processedData['done'][$month]++;
////                    } else { // Task is not completed
////                        if ($task->time_out && strtotime($task->sent_date) > strtotime($task->time_out)) {
////                            $processedData['delayed'][$month]++;
////                        } else {
////                            $processedData['notDone'][$month]++;
////                        }
////                    }
//                    }
//                }
//            }
//            $this->yearData[$year] = $processedData;
//        }
//    }

    public function render()
    {
        return view('livewire.reports.meeting-dashboard-report');
    }
//
//    public $yearData = [];
//    public $currentYear = 1400; // Default year
//    public $currentMonth = 0; // Default month (0 for first 6, 1 for last 6)
//
//    public function mount()
//    {
//        $this->fetchData();
//    }
//
//    public function updatedCurrentYear()
//    {
//        $this->fetchData();
//    }
//
//    public function updatedCurrentMonth()
//    {
//        // No need to refetch data for month change, only categories change in javascript
//    }

//    private function fetchData()
//    {
//        $this->yearData = [];
//
////        for ($year = 1400; $year <= 1405; $year++) { // Adjust based on your years
//            $done = DB::table('tasks') // Replace 'actions' with your table name
//                ->whereColumn('sent_date', '<=', 'time_out')
//                ->where('is_completed', true)
//                ->pluck('sent_date')
//                ->toArray();
//            foreach ($done as $value){
//                $done_year = explode('/',$value);
//                dump($done_year[0]); //this is the year
//                dump($done_year[1]); // this is the month
//            }
////            $notDone = DB::table('tasks')
////                ->where('year', $year)
////                ->where('status', 'notDone')
////                ->orderBy('month')
////                ->pluck('value')
////                ->toArray();
////
////            $delayed = DB::table('tasks')
////                ->where('year', $year)
////                ->where('status', 'delayed')
////                ->orderBy('month')
////                ->pluck('value')
////                ->toArray();
//
////            $this->yearData[$year] = [
////                'done' => $done,
////                'notDone' => $notDone,
////                'delayed' => $delayed,
////            ];
////        }
//    }


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
