<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Enums\TaskStatus;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\TaskUserFile;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;


class CreateTask extends Component
{
    use WithFileUploads;

    public $meeting;
    public $taskUserId = '';


    public $selectedTask = '';

    public $participantName;
    public $selectedTaskId;
    public $updateTaskBody;
    public $newFiles = [];


    public $participantTaskBody;
    public $files = [];


    protected $loadedMeeting;
    protected $loadedEmployees;
    protected $loadedTasks;

    public $selectedTaskFiles = [];


    public $userName;
    public $day;
    public $month;
    public $year;
    public $body;
    public $request_task;





    /**
     * for inline editing
     */
    public $editingTaskId,$editingBodyTask,$editingTimeOut;
    public function edit($taskId): void
    {
        $task = Task::find($taskId);
        $this->editingTaskId = $taskId;
        $this->editingBodyTask = $task->body;
        $this->editingTimeOut = $task->time_out;
    }
    public function delete($taskId)
    {
        Task::findOrFail($taskId)->delete();
        session()->flash('delete', 'Todo were deleted successfully.');
    }
    public function cancel(): void
    {
        $this->reset('editingTaskId', 'editingBodyTask', 'editingTimeOut');
        $this->resetValidation();
    }
    /**
     * @throws ValidationException
     */
    public function update(): void
    {
        $validated = Validator::make(
            [
                'editingBodyTask' => $this->editingBodyTask,
                'editingTimeOut' => $this->editingTimeOut,
            ],
            [
                'editingBodyTask' => 'required|string|max:1000|min:5',
                'editingTimeOut' => 'required',
            ],
            [
                'editingBodyTask.required' => 'فیلد خلاصه مذاکره اجباری است.',
                'editingBodyTask.max' => 'حداکثر تعداد کاراکترها برای خلاصه مذاکره 1000 کاراکتر است.',
                'editingBodyTask.min' => 'حداقل تعداد کاراکترها برای خلاصه مذاکره 5 کاراکتر است.',
                'editingTimeOut.required' => 'تاریخ اجباری است.',
            ]
        )->validate();

        $timeOutTrimmed = trim($this->editingTimeOut);
        if (!preg_match('/^14\d{2}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])$/', $timeOutTrimmed)) {
            $this->addError('editingTimeOut', 'فرمت تاریخ باید به صورت 0000/00/00 باشد.');
            return;
        }

        $normalized = $this->normalizeText($this->editingBodyTask);

        Task::findOrFail($this->editingTaskId)->update([
            'body' => $normalized,
            'time_out' => $timeOutTrimmed,
        ]);
        $this->cancel();
        session()->flash('success', 'مذاکره با موفقیت بروزرسانی شد.');
    }




    public function render()
    {
        return view('livewire.create-task');
    }

    #[Computed]
    public function meetings()
    {
        // Load once and cache inside the object
        if (!$this->loadedMeeting) {
            $this->loadedMeeting = Meeting::with([
                'meetingUsers.user.user_info:id,user_id,full_name',
                'tasks.user.user_info:id,user_id,full_name',
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
            $this->loadedTasks = $this->meetings()->tasks()->get();
        }
        return $this->loadedTasks;
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



    /**
     * To accept the Task by participant
     * @throws AuthorizationException
     */
    public function acceptTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        abort_unless($task->user_id === auth()->id(), 403);
        $task->update([
            'task_status' => TaskStatus::ACCEPTED->value
        ]);
    }
    /**
     * To deny the Task by participant
     * @throws AuthorizationException
     */
    public function openDenyModal($taskId)
    {
        $this->authorize('acceptOrDeny',$taskId);
        $task = Task::with(['user.user_info:id,user_id,full_name'])->findOrFail($taskId);
        $this->selectedTask = $task;
        $this->dispatch('crud-modal', name: 'deny-task');
    }
    /**
     * To show Task form model to the participant
     * @throws AuthorizationException
     */
    public function showTaskForm($taskId)
    {
        $task = Task::with([
            'user.user_info:id,user_id,full_name',
        ])->findOrFail($taskId);
        $this->authorize('participantCanWriteTask',$task);
        $this->selectedTask = $task;
        $this->selectedTaskId = $taskId;
        $this->participantName = $task->user->user_info->full_name ?? '';
        $this->dispatch('crud-modal', name: 'view-task-details-modal');
    }

    /**
     * To Show update Task form to the participant
     * @throws ValidationException
     */
    public function submitTaskForm()
    {
        $validated = Validator::make(
            ['participantTaskBody' => $this->participantTaskBody, 'files' => $this->files],
            ['participantTaskBody' => 'required|string|min:5', 'files.*' => 'nullable|file|max:2048|mimes:jpeg,png,pdf,docx,xlsx'],
            [
                'participantTaskBody.required' => 'فیلد شرح اقدام شما اجباری است.',
                'participantTaskBody.min' => 'شرح اقدام باید حداقل ۵ کاراکتر باشد.',
                'files.*.file' => 'هر فایل باید یک فایل معتبر باشد.',
                'files.*.max' => 'حداکثر حجم فایل 2 مگابایت است.',
                'files.*.mimes' => 'فرمت فایل باید یکی از jpeg, png, pdf, docx, xlsx باشد.'
            ]
        )->validate();

        $participantTaskBody = $this->normalizeText($this->participantTaskBody);

        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        $task = Task::where('id', $this->selectedTaskId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        abort_unless($task->user_id === auth()->id(), 403);

        $task->update([
            'sent_date' => $newTime,
            'task_status' => TaskStatus::IS_COMPLETED->value,
            'body_task' => $participantTaskBody,
        ]);

        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $path = $file->store('task_files', 'public');
                $task->taskUserFiles()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }
        session()->flash('status', 'اقدام با موفقیت ثبت شد.');
        $this->dispatch('close-modal');
    }


    /**
     * To Show update Task form to the participant
     */
    public function openUpdateModal($taskId)
    {
        $this->selectedTask = Task::with('taskUserFiles')->findOrFail($taskId);
        $this->authorize('participantCanUpdateTask',$this->selectedTask);
        // Preload the task body for editing

        $this->updateTaskBody = $this->selectedTask->body_task;

        $this->selectedTaskId = $taskId;

        // Preload existing files related to the task
        $this->selectedTaskFiles = $this->selectedTask->taskUserFiles;

        // Dispatch event to open the modal
        $this->dispatch('crud-modal', name: 'edit-task-details-modal');
    }

    public function deleteUploadedFile($fileId)
    {
        $file = TaskUserFile::findOrFail($fileId);
        Storage::disk('public')->delete($file->file_path);
        $file->delete();
        $this->selectedTaskFiles = $this->selectedTask->taskUserFiles()->get();
    }



    public function updatedNewFiles($files)
    {
        foreach ($files as $file) {
            if (!$file->isValid()) {
                $this->reset('newFiles');
                $this->addError('newFiles', 'فایل نامعتبر است یا خیلی بزرگ است.');
                return;
            }

            if ($file->getSize() > 2 * 1024 * 1024) {
                $this->reset('newFiles');
                $this->addError('newFiles', 'فایل "' . $file->getClientOriginalName() . '" بیشتر از ۲ مگابایت است.');
                return;
            }
        }
    }
    /**
     * update Task by participants
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function updateTaskForm()
    {
        $validated = Validator::make(
            ['updateTaskBody' => $this->updateTaskBody, 'newFiles' => $this->newFiles],
            ['updateTaskBody' => 'required|string|min:5', 'newFiles.*' => 'nullable|file|max:2048|mimes:jpeg,png,pdf,docx,xlsx'],
            [
                'updateTaskBody.required' => 'فیلد شرح اقدام شما اجباری است.',
                'updateTaskBody.min' => 'شرح اقدام باید حداقل ۵ کاراکتر باشد.',
                'newFiles.*.file' => 'هر فایل باید یک فایل معتبر باشد.',
                'newFiles.*.max' => 'حداکثر حجم فایل 2 مگابایت است.',
                'newFiles.*.mimes' => 'فرمت فایل باید یکی از jpeg, png, pdf, docx, xlsx باشد.',
            ]
        )->validate();

        $updateTaskBody = $this->normalizeText($this->updateTaskBody);

        $task = Task::where('id', $this->selectedTaskId)->where('user_id', auth()->id())->firstOrFail();

        abort_unless($task->user_id === auth()->id(), 403);

        $task->update([
            'body_task' => $updateTaskBody,
        ]);

        if ($this->newFiles && count($this->newFiles) > 0) {
            foreach ($this->newFiles as $file) {
                $path = $file->store('task_files', 'public');
                $task->taskUserFiles()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }
        session()->flash('status', 'اقدام با موفقیت ویرایش شد.');
        $this->close($task->meeting_id);
    }

    public function close($meetingId)
    {
        $this->dispatch('close-modal');
        $signedUrl = URL::signedRoute('tasks.create', ['meeting' => $meetingId]);
        return redirect()->to($signedUrl);
    }






    public function showFinalCheck()
    {
        $this->dispatch('crud-modal', name: 'final-check');
    }

    public function finishMeeting($meeting_id)
    {
        Meeting::where('id', $meeting_id)->update([
            'status' => MeetingStatus::IS_FINISHED->value,
            'end_time' => now()->format('H:i')
        ]);
        $this->dispatch('close-modal');
        session()->flash('status', 'جلسه خاتمه یافت');
    }








    /**
     * @throws ValidationException
     */
    public function denyTask($selectedTaskId)
    {
        $validated = Validator::make(
            ['request_task' => $this->request_task],
            ['request_task' => 'required|min:3'],
            ['required' => 'فیلد دلیل رد خلاصه مذاکره اجباری است.', 'min' => 'دلیل رد باید حداقل ۳ کاراکتر باشد.']
        )->validate();

        $request_task = Str::of(strip_tags($this->request_task))->squish();

        // Find the TaskUser model
        $taskUser = TaskUser::findOrFail($selectedTaskId);

        // Update the task's status and reason
        $taskUser->task_status = TaskStatus::DENIED;
        $taskUser->request_task = $validated['request_task'];  // Save the reason
        $taskUser->save();

        // Get the related meeting
        $meeting = $taskUser->task->meeting;

        // Prepare the notification message
        $notificationMessage = $request_task;
        // Get the scriptorium user id (recipient)
        $recipientId = User::whereHas('user_info', function($q) use ($meeting) {
            $q->where('full_name', $meeting->scriptorium);
        })->value('id');

        if ($recipientId) {
            // Create the notification to the scriptorium
            Notification::create([
                'type' => 'DeniedTaskNotification',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $recipientId,
            ]);
        }

        session()->flash('status', 'درخواست شما به دبیرجلسه ارسال شد');
        $this->dispatch('close-modal');
    }

    public function sendToScriptorium($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
        $taskUser->task_status = TaskStatus::SENT_TO_SCRIPTORIUM;
        $taskUser->save();
        session()->flash('status', 'شرح اقدام به دبیرجلسه ارسال شد');
    }

    protected function normalizeText($text)
    {
        $text = strip_tags($text);                         // Remove HTML tags
        $text = preg_replace('/\s+/', ' ', trim($text));   // Normalize whitespace
        $words = explode(' ', $text);                      // Split into words
        $uniqueWords = array_unique($words);               // Remove duplicate words
        return implode(' ', $uniqueWords);                 // Join back to string
    }



}
