<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TasksFinishedOnTime extends Component
{
    public function render()
    {
        return view('livewire.tasks-finished-on-time');
    }
    #[Computed]
    public function tasksDoneOnTime()
    {
        return Task::with('meeting')
            ->whereColumn('sent_date', '<=', 'time_out')
            ->get();
//        $task_deadline = '';
//        $task_sent_date = '';
//        $tasks = Task::with('meeting')->get();
//        foreach ($tasks as $task) {
//            $task_deadline = $task->time_out;
//            $task_sent_date = $task->sent_date;
//        }
//        // deadline to send tasks
//        $first_year = Str::of($task_deadline)->explode('/')[0];
//        $first_month = Str::of($task_deadline)->explode('/')[1];
//        $first_day = Str::of($task_deadline)->explode('/')[2];
//
//        // sent date of tasks
//        $second_year = Str::of($task_sent_date)->explode('/')[0];
//        $second_month = Str::of($task_sent_date)->explode('/')[1];
//        $second_day = Str::of($task_sent_date)->explode('/')[2];
//
//        if ($first_year === $second_year) {
//            if ($first_month === $second_month) {
//                if ($first_day >= $second_day) {
//                    return Task::with('meeting')
//                        ->whereColumn('sent_date', '<=', 'time_out')
//                        ->get();
//                }
//            }
//        }
    }
}
