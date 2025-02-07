<?php

namespace App\Trait;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

trait MeetingsTasks
{
    public ?string $search = '';


    #[Computed]
    public function meetings()
    {
        return Meeting::with('meetingUsers','tasks')
            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium',auth()->user()->user_info->full_name)
            ->get(['id','title','date','time','scriptorium','is_cancelled']);
    }

    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with('meeting:id,title,scriptorium,date,time,is_cancelled')
            ->where('user_id',auth()->user()->id)
            ->get(['id','meeting_id','user_id','is_present']);
    }
    #[Computed]
    public function tasks()
    {
        $user = User::find(auth()->id());
        return Task::with('meeting','user')->whereBelongsTo($user)->get();
    }
    public function download($taskId)
    {
        $files = DB::table('task_user')
            ->where('task_id','=',$taskId)
            ->value('file');
        return response()->download(public_path('storage/'.$files));
    }

}
