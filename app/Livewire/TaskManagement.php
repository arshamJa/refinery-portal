<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskManagement extends Component
{
    use WithFileUploads;

    public $meeting_id;
    public $user_id;
    public $title;
    public $body;
    public $date;
    public $day;
    public array $files = [];


    public function render()
    {
        return view('livewire.task-management');
    }
    #[Computed]
    public function meetings()
    {
        return Meeting::where('scriptorium', auth()->user()->user_info->full_name)
            ->where('is_cancelled','-1')
            ->get(['id','title']);
    }
    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('user')->where('meeting_id',$this->meeting_id)->get('user_id');
    }

    #[Computed]
    public function users()
    {
        return UserInfo::where('user_id','!=',auth()->user()->id)
            ->get(['user_id', 'full_name']);
    }

    public function send()
    {
        $task = Task::create([
            'meeting_id' => $this->meeting_id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'body' => $this->body,
            'sent_date' => $this->date,
            'time_out' => $this->day
        ]);
        foreach ($this->files as $file){
            $path = $file->store('tasks','public');
            DB::table('task_user')->insert([
                'task_id' => $task->id,
                'file' => $path
            ]);
        }
    }
}
