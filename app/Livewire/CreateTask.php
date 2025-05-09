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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;


class CreateTask extends Component
{
    use WithFileUploads;

    public $meeting;
    public $taskBody,$taskUserId = '';
    public $files = [];

    public $selectedTask = '';
    public $taskName;
    protected $loadedMeeting,$loadedEmployees,$loadedTasks;

    public $selectedTaskFiles = [];


    public $userName;
    public $day,$month,$year,$body;
    public $request_task;

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

    /**
     * @throws ValidationException
     */
    public function submitTaskForm($selectedTaskId)
    {
        $validated = Validator::make(
            ['taskBody' => $this->taskBody, 'files' => $this->files],
            ['taskBody' => 'required|string|min:5', 'files.*' => 'nullable|file|max:2048|mimes:jpeg,png,pdf,docx,xlsx'],
            [
                'taskBody.required' => 'فیلد شرح اقدام شما اجباری است.',
                'taskBody.min' => 'شرح اقدام باید حداقل ۵ کاراکتر باشد.',
                'files.*.file' => 'هر فایل باید یک فایل معتبر باشد.',
                'files.*.max' => 'حداکثر حجم فایل 2 مگابایت است.',
                'files.*.mimes' => 'فرمت فایل باید یکی از jpeg, png, pdf, docx, xlsx باشد.'
            ]
        )->validate();

        $taskBody = Str::of(strip_tags($this->taskBody))->squish();


        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        $taskUser = TaskUser::where('id', $selectedTaskId)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $taskUser->update([
            'sent_date' => $newTime,
            'is_completed' => true,
            'body_task' => $taskBody,
        ]);

        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $path = $file->store('task_files', 'public');
                TaskUserFile::create([
                    'task_user_id' => $taskUser->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        session()->flash('message', 'اقدام با موفقیت ثبت شد.');
        $this->dispatch('close-modal');
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


    /**
     * @throws ValidationException
     */
    public function denyTask()
    {

        $validated = Validator::make(
            ['request_task' => $this->request_task],
            ['request_task' => 'required|min:3'],
            ['required' => 'فیلد دلیل رد خلاصه مذاکره اجباری است.', 'min' => 'دلیل رد باید حداقل ۳ کاراکتر باشد.']
        )->validate();

        $request_task = Str::of(strip_tags($this->request_task))->squish();

        // Find the TaskUser model
        $taskUser = TaskUser::findOrFail($this->taskUserId);

        // Update the task's status and reason
        $taskUser->task_status = TaskStatus::DENIED;
        $taskUser->request_task = $validated['request_task'];  // Save the reason
        $taskUser->save();
        session()->flash('message', 'وظیفه با موفقیت رد شد.');
        $this->dispatch('close-modal');
    }


    public function openModalScriptorium($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
        $this->taskUserId = $taskUser->id;
        $this->userName = $taskUser->user->user_info->full_name;
        $this->dispatch('crud-modal', name: 'edit-by-scriptorium');
    }


    /**
     * @throws ValidationException
     */
    public function updateTask()
    {

        $validated = Validator::make(
            ['year' => $this->year, 'month' => $this->month, 'day' => $this->day, 'body' => $this->body],
            ['year' => 'required', 'month' => 'required', 'day' => 'required', 'body' => 'required|min:3'],
            [
                'year.required' => 'فیلد سال اجباری است.',
                'month.required' => 'فیلد ماه اجباری است.',
                'day.required' => 'فیلد روز اجباری است.',
                'body.required' => 'فیلد خلاصه مذاکرات اجباری است.',
                'body.min' => 'خلاصه مذاکرات باید حداقل ۳ کاراکتر باشد.'
            ]
        )->validate();

        // Sanitize the inputs before validation
        $year = trim($this->year);
        $month = trim($this->month);
        $day = trim($this->day);
        $body  = Str::of(strip_tags($this->body))->squish();



        $taskUser = TaskUser::with('task')->findOrFail($this->taskUserId);
        $oldTask  = Task::findOrFail($taskUser->task_id);

        $normalizedOldBody = $this->normalizeText($oldTask->body);
        $normalizedNewBody = $this->normalizeText($body);

        $newTimeOut = sprintf("%04d/%02d/%02d", $year, $month, $day);

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
                'body' => $body,
            ]);
            $taskUser->task_id = $newTask->id;
        }

        if ($timeOutChanged || $bodyChanged) {
            $taskUser->update([
                'time_out' => $newTimeOut,
                'task_status' => TaskStatus::PENDING,
                'request_task' => null
            ]);
        }

        session()->flash('status', 'ویرایش انجام شد');
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
