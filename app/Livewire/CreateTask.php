<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Models\Meeting;
use App\Models\TaskUser;
use App\Models\TaskUserFile;
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
    public $fileUpload = [];
    public $selectedTaskFiles = [];




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
            $this->loadedEmployees = $this->meetings()->meetingUsers;
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

    public function submitTaskForm($taskUserId)
    {
        $this->validate([
            'taskBody' => ['required', 'string', 'min:5'],
            'fileUpload' => ['nullable']
        ]);

        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        $body = Str::deduplicate($this->taskBody);

        $taskUser = TaskUser::where('id', $taskUserId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $taskUser->update([
            'sent_date' => $newTime,
            'is_completed' => true,
            'body_task' => $body,
        ]);
        if (!empty($this->fileUpload)) {
            foreach ($this->fileUpload as $file) {
                $path = $file->store('task_files', 'public');
                TaskUserFile::create([
                    'task_user_id' => $taskUser->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('status', 'اقدام با موفقیت ثبت شد');
    }

    public function showFinalCheck()
    {
        $this->dispatch('crud-modal', name: 'final-check');
    }

    public function finishMeeting($meeting_id)
    {
        Meeting::where('id', $meeting_id)->update(['status' => MeetingStatus::IS_FINISHED->value]);
    }




//    #[Computed]
//    public function meetings()
//    {
//        return Meeting::where('deleted_at',null)->findOrFail($this->meeting);
//    }
//
//    #[Computed]
//    public function employees()
//    {
//        return MeetingUser::with('user.user_info:id,user_id,full_name') // Only fetch necessary columns
//        ->where('meeting_id', $this->meeting)
//            ->select('id','meeting_id','user_id','is_present')
//            ->get();
//
//    }


//    #[Computed]
//    public function allUsersHaveTasks()
//    {
//        // Get the meeting ID directly
//        $meetingId = $this->meetings()->id;
//
//        // Get the count of users associated with the meeting
//        $userIdsCount = $this->meetings()->meetingUsers->count();
//
//        if ($userIdsCount === 0) {
//            return false; // If no users are associated with the meeting, return false immediately
//        }
//
//        // Get the count of users who have tasks assigned for this meeting
//        $usersWithTasksCount = TaskUser::whereHas('task', function ($query) use ($meetingId) {
//            $query->where('meeting_id', $meetingId); // Ensure the task is related to this meeting
//        })
//            ->distinct() // Ensure distinct user IDs
//            ->count('user_id'); // Count distinct user IDs
//
//        // Check if all users have tasks by comparing counts
//        return $usersWithTasksCount === $userIdsCount;
//    }

//    #[Computed]
//    public function tasks()
//    {
//        // Eager load user and userInfo
//        return Task::with([
//            'taskUsers' => function ($query) {
//                $query->select('id','task_id', 'user_id', 'sent_date', 'is_completed', 'request_task','body_task');
//            },
//            'taskUsers.user.user_info:id,user_id,full_name'
//        ])
//            ->where('meeting_id', $this->meeting)
//            ->select('id', 'meeting_id', 'body', 'time_out')
//            ->get();
//    }

//    public function render()
//    {
//        return view('livewire.create-task');
//    }



//    public function showTaskDetails($taskUserId)
//    {
//        $taskUser = TaskUser::with([
//            'task:id,body,meeting_id',
//        ])
//            ->select('id', 'task_id', 'user_id', 'sent_date', 'is_completed', 'body_task', 'request_task')
//            ->findOrFail($taskUserId);
//
//        $this->selectedTask = $taskUser;
//        $this->taskName = $taskUser->user->user_info->full_name;
//        $this->dispatch('crud-modal', name: 'view-task-details-modal');
//    }


//    public function submitTaskForm($taskUserId)
//    {
//        $this->validate([
//            'taskBody' => ['required', 'string', 'min:5'],
//        ]);
//
//        // Convert current Gregorian date to Jalali
//        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
//
//        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
//
//        $body = Str::deduplicate($this->taskBody);
//
//        TaskUser::where('id', $taskUserId)
//            ->where('user_id', auth()->id()) // auth()->user()->id is fine too
//            ->update([
//                'sent_date' => $newTime,
//                'is_completed' => true,
//                'body_task' => $body,
//            ]);
//
//        return back()->with('status', 'Task submitted successfully.');
//    }


//    public function showFinalCheck($meeting_id)
//    {
//        $meeting = Meeting::select('id', 'title')
//        ->findOrFail($meeting_id);
//
//        $this->dispatch('crud-modal', name: 'final-check');
//    }

//    public function finishMeeting($meeting_id)
//    {
//        $meeting = Meeting::where('id',$meeting_id)->update(['status' => '2']);
//
//    }



}
