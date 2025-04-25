<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Models\UserInfo;
use Exception;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateTask extends Component
{
    public $meeting;

    #[Computed]
    public function meetings()
    {
        return Meeting::findOrFail($this->meeting);
    }
    #[Computed]
    public function employees()
    {
        return MeetingUser::with('user.user_info') // Eager load user and userInfo
        ->where('meeting_id', $this->meeting)
            ->get();

    }


    #[Computed]
    public function allUsersHaveTasks()
    {
        // Get all users associated with the meeting via TaskUser
        $taskUsers = TaskUser::whereHas('task', function ($query) {
            $query->where('meeting_id', $this->meeting);
        })->get();

        // Extract user IDs from the task_users
        $userIdsWithTasks = $taskUsers->pluck('user_id')->toArray();


        // Get all users that are supposed to be associated with this meeting
        // Assuming you have a list of all users assigned to the meeting, perhaps from another source or through TaskUser
        $expectedUsers = User::all(); // Replace this with the correct query for users associated with this meeting

        // Check if all expected users have tasks
        foreach ($expectedUsers as $user) {
            if (!in_array($user->id, $userIdsWithTasks)) {
                return false; // If any user does not have a task, return false
            }
        }
        return true; // All users have tasks
    }





















    #[Computed]
    public function tasks()
    {
        // Eager load user and userInfo
                return Task::with([
            'taskUsers' => function ($query) {
                $query->select('id','task_id', 'user_id', 'sent_date', 'is_completed', 'request_task','body_task');
            },
            'taskUsers.user.user_info'
        ])
            ->where('meeting_id', $this->meeting)
            ->select('id', 'meeting_id', 'body', 'time_out')
            ->get();
    }

    public function render()
    {
        return view('livewire.create-task');
    }


    public $taskBody;
    public $selectedTask = '';
    public $taskName;
    public function showTaskDetails($taskUserId)
    {
        $taskUser = TaskUser::with([
            'task' => function ($query) {
                $query->select('id', 'body', 'meeting_id');
            },
            'user.user_info'
        ])
            ->select('id', 'task_id', 'user_id', 'sent_date', 'is_completed', 'body_task', 'request_task')
            ->findOrFail($taskUserId);

        $this->selectedTask = $taskUser;
        $this->taskName = UserInfo::where('user_id',$taskUser->user_id)->value('full_name');
        $this->dispatch('crud-modal', name: 'view-task-details-modal');
    }


    public function submitTaskForm($taskUserId)
    {
        $this->validate([
            'taskBody' => ['required', 'string', 'min:5'],
        ]);

        // Convert current Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        $body = Str::deduplicate($this->taskBody);

        TaskUser::where('id', $taskUserId)
            ->where('user_id', auth()->id()) // auth()->user()->id is fine too
            ->update([
                'sent_date' => $newTime,
                'is_completed' => true,
                'body_task' => $body,
            ]);

        return back()->with('status', 'Task submitted successfully.');
    }



}
