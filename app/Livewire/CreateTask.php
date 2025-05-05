<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Enums\TaskStatus;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\TaskUserFile;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;


class CreateTask extends Component
{
    use WithFileUploads;

    public $meeting;
    public $taskBody;
    public $selectedTask = '';
    public $taskName;
    protected $loadedMeeting;
    protected $loadedEmployees;
    protected $loadedTasks;
    public $fileUpload;
    public $selectedTaskFiles = [];


    public $taskUserId = '';
//    public $taskUser;



    #[Computed]
    public function meetings()
    {
        // Load once and cache inside the object
        if (!$this->loadedMeeting) {
            $this->loadedMeeting = Meeting::with([
                'meetingUsers.user.user_info:id,user_id,full_name',
                'tasks.taskUsers.user.user_info:id,user_id,full_name',
            ])
                ->findOrFail($this->meeting);
        }
        return $this->loadedMeeting;
    }

    #[Computed]
    public function employees()
    {
        if (!$this->loadedEmployees) {
            $this->loadedEmployees = $this->meetings()->meetingUsers->where('is_guest',false);
        }
        return $this->loadedEmployees;
    }

    #[Computed]
    public function tasks()
    {
        if (!$this->loadedTasks) {
            $this->loadedTasks = $this->meetings()->tasks()->with([
                'taskUsers.user.user_info',
                'taskUsers.taskUserFiles',
            ])->get();
        }
        return $this->loadedTasks;
    }

    #[Computed]
    public function allUsersHaveTasks()
    {
        $userIdsCount = $this->employees()->count();

        if ($userIdsCount === 0) {
            return false;
        }

        $usersWithTasksCount = TaskUser::whereHas('task', function ($query) {
            $query->where('meeting_id', $this->meeting);
        })->distinct()->count('user_id');

        return $usersWithTasksCount === $userIdsCount;
    }

    public function render()
    {
        return view('livewire.create-task');
    }

    #[Computed]
    public function presentUsers()
    {
        return $this->employees->filter(function ($employee) {
            return $employee->is_present; // Adjust this to match your actual logic
        })->map(function ($employee) {
            return $employee->user;
        });
    }

    public function showTaskDetails($taskUserId)
    {
        $taskUser = TaskUser::with([
            'task:id,body,meeting_id',
            'user.user_info:id,user_id,full_name'
        ])->findOrFail($taskUserId);

        $this->selectedTask = $taskUser;
        $this->taskName = $taskUser->user->user_info->full_name ?? '';
        $this->dispatch('crud-modal', name: 'view-task-details-modal');
    }

    public function openUpdateModal($taskUserId)
    {
        $this->selectedTask = TaskUser::with('taskUserFiles')->findOrFail($taskUserId);
        // Ensure the user is the owner
        abort_unless( $this->selectedTask->user_id === auth()->id(), 403);
        $this->taskBody = $this->selectedTask->body_task; // preload body
        $this->selectedTaskFiles = $this->selectedTask->taskUserFiles; // preload existing files

        $this->dispatch('crud-modal', name:'edit-task-details-modal');

    }
    public function showFinalCheck()
    {
        $this->dispatch('crud-modal', name: 'final-check');
    }

    public function finishMeeting($meeting_id)
    {
        Meeting::where('id', $meeting_id)->update(['status' => MeetingStatus::IS_FINISHED->value]);
        $this->dispatch('close-modal');
        session()->flash('status', 'اقدام با موفقیت ثبت شد');
    }


    /**
     * @throws AuthorizationException
     */
    public function acceptTask($taskId)
    {
//        $this->authorize('acceptOrDeny',$taskId);
        TaskUser::where('task_id',$taskId)->where('user_id',auth()->user()->id)->update([
            'task_status' => TaskStatus::ACCEPTED->value
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function openDenyModal($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
//        $this->authorize('acceptOrDeny', $taskUser);
        $this->taskUserId = $taskUser->id;
        $this->dispatch('crud-modal', name: 'deny-task');
    }


    public $request_task;
    public function denyTask()
    {
        $this->validate([
            'request_task' => 'required|string|max:1000',
        ]);

        // Find the TaskUser model
        $taskUser = TaskUser::findOrFail($this->taskUserId);

        // Update the task's status and reason
        $taskUser->task_status = TaskStatus::DENIED;
        $taskUser->request_task = $this->request_task;  // Save the reason
        $taskUser->save();

        $this->dispatch('close-modal');
    }
    public $userName ,$day ,$month,$year,$body;

    public function openModalScriptorium($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
        $this->taskUserId = $taskUser->id;
        $this->userName = $taskUser->user->user_info->full_name;
        $this->dispatch('crud-modal', name: 'edit-by-scriptorium');
    }

    public function updateTask()
    {
        $taskUser = TaskUser::findOrFail($this->taskUserId);
        $oldTask  = Task::findOrFail($taskUser->task_id);

        $normalizedOldBody = $this->normalizeText($oldTask->body);
        $normalizedNewBody = $this->normalizeText($this->body);

        $newTimeOut = sprintf("%04d/%02d/%02d", $this->year, $this->month, $this->day);

        $bodyChanged = $normalizedOldBody !== $normalizedNewBody;
        $timeOutChanged = $taskUser->time_out !== $newTimeOut;

        if (!$bodyChanged && !$timeOutChanged) {
            session()->flash('message', 'هیچ تغییری اعمال نشد.');
            $this->dispatch('close-modal', name: 'edit-by-scriptorium');
            return;
        }

        if ($bodyChanged) {
            $newTask = Task::create([
                'meeting_id' => $oldTask->meeting_id,
                'body' => $normalizedNewBody,
            ]);
            $taskUser->task_id = $newTask->id;
        }

        if ($timeOutChanged || $bodyChanged) {
            $taskUser->time_out = $newTimeOut;
            $taskUser->task_status = TaskStatus::PENDING;
            $taskUser->request_task = null;
            $taskUser->save();
        }

        session()->flash('message', 'شرح وظیفه یا مهلت اقدام بروزرسانی شد.');
        $this->dispatch('close-modal', name: 'edit-by-scriptorium');
    }

    protected function normalizeText($text)
    {
        $text = preg_replace('/\s+/', ' ', trim($text));
        $words = explode(' ', $text);
        $uniqueWords = array_unique($words);
        return implode(' ', $uniqueWords);
    }



}
