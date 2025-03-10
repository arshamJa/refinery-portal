<?php

namespace App\Livewire;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
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
        $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $jaYear = $jalaliNow[0];
        $jaMonth = $jalaliNow[1];
        $jaDay = $jalaliNow[2];

        // two lines below will check if the month and day is one digit , which will add 0 before it .
        $new_month = sprintf("%02d", $jaMonth);
        $new_day = sprintf("%02d", $jaDay);
        $newDate = $jaYear . '/' . $new_month . '/' . $new_day;

        $task = Task::find($taskId);
        $task->is_completed = true;
        $task->sent_date = $newDate;
        $task->save();
        $this->redirectRoute('attended.meetings');
    }
//    public $body;
//
//    public function editTask($taskId)
//    {
//        $task = Task::find($taskId);
//        $body = trim($this->body);
//        $task->request_task = $body;
//        $task->save();
//        $this->redirectRoute('attended.meetings');
//    }

}
