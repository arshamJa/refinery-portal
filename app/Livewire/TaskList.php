<?php

namespace App\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TaskList extends Component
{
    public $meeting;
    public function render()
    {
        return view('livewire.task-list');
    }




    #[Computed]
    public function tasks()
    {
        return Task::where('meeting_id',$this->meeting)->where('user_id',auth()->user()->id)->get();
    }


    public function finishTask($taskId)
    {
        $day = now()->day;
        $month = now()->month;
        $year = now()->year;
        $nowTime = gregorian_to_jalali($year,$month,$day,'/');

        $task = Task::find($taskId);
        $task->is_completed = true;
        $task->sent_date = $nowTime;
        $task->save();
        $this->redirectRoute('attended.meetings');
    }

    public $body;

    public function editTask($taskId)
    {
        $task = Task::find($taskId);
        $body = trim($this->body);
        $task->request_task = $body;
        $task->save();
        $this->redirectRoute('attended.meetings');
    }

}
