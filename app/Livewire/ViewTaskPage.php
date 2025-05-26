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
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;


class ViewTaskPage extends Component
{
    use WithFileUploads;

    public $meeting;

    public $selectedTaskUser;

    public $selectedTaskUserId;

    public $taskBody;

    public $participantName;
    public $updateTaskBody;
    public $newFiles = [];


    public $participantTaskBody;
    public $files = [];


    protected $loadedMeeting;
    protected $loadedEmployees;
    protected $loadedTasks;

    public $selectedTaskFiles = [];


    public $request_task;


    public function openAddParticipantModal()
    {
        $this->dispatch('crud-modal', name: 'add-participant');
    }
    public $employee_id,$task_id,$year,$month,$day;
    public function submitParticipantForm()
    {
        abort_if(!Gate::allows('scriptorium-role'), 403);
        $validated = Validator::make(
            ['employee_id' => $this->employee_id, 'task_id' => $this->task_id,
                'year' => $this->year, 'month' => $this->month, 'day' => $this->day,],
            ['employee_id' => 'required|exists:users,id', 'task_id' => 'required|exists:tasks,id',
                'year' => 'required|integer|between:1404,1430', 'month' => 'required|integer|between:1,12',
                'day' => 'required|integer|between:1,31'],
            ['employee_id.required' => 'اقدام‌کننده اجباری است.', 'employee_id.exists' => 'اقدام‌کننده نامعتبر است.',
                'task_id.required' => 'بند مذاکره اجباری است.', 'task_id.exists' => 'بند مذاکره نامعتبر است.',
                'year.required' => 'سال اجباری است.', 'year.integer' => 'سال باید عدد باشد.',
                'year.min' => 'سال نامعتبر است.', 'year.max' => 'سال نامعتبر است.',
                'month.required' => 'ماه اجباری است.', 'month.integer' => 'ماه باید عدد باشد.',
                'month.min' => 'ماه نامعتبر است.', 'month.max' => 'ماه نامعتبر است.',
                'day.required' => 'روز اجباری است.', 'day.integer' => 'روز باید عدد باشد.',
                'day.min' => 'روز نامعتبر است.', 'day.max' => 'روز نامعتبر است.',
            ]
        )->validate();

        $exists = TaskUser::where('task_id', $validated['task_id'])
            ->where('user_id', $validated['employee_id'])
            ->exists();

        if ($exists) {
            $this->addError('employee_id', 'این اقدام‌کننده قبلاً برای این بند ثبت شده است.');
            return;
        }

        // Validate that the Jalali date is not in the past
        list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        if (
            $validated['year'] < $ja_year ||
            ($validated['year'] == $ja_year && $validated['month'] < $ja_month) ||
            ($validated['year'] == $ja_year && $validated['month'] == $ja_month && $validated['day'] < $ja_day)
        ) {
            $this->addError('year', 'تاریخ نباید گذشته باشد');
            return;
        }

        $time_out = sprintf('%04d/%02d/%02d', $validated['year'], $validated['month'], $validated['day']);

        TaskUser::create([
            'task_id' => $validated['task_id'],
            'user_id' => $validated['employee_id'],
            'time_out' => $time_out,
        ]);

        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'اقدام کننده جدید ثبت شد');
        return redirect()->to($signedUrl);

    }
    public function opedEditModal($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
        $this->selectedTaskUser = $taskUser;
        $this->selectedTaskUserId = $taskUser->user_id;
        $this->taskBody = $taskUser->task->body;
        $this->dispatch('crud-modal', name: 'edit-by-scriptorium');

    }
    public function editForm()
    {
        $validated = Validator::make(
            [
                'taskBody' => $this->taskBody,
                'year' => $this->year,
                'month' => $this->month,
                'day' => $this->day,
            ],
            [
                'taskBody' => 'nullable|string|min:5',
                'year' => 'nullable|integer|between:1404,1430',
                'month' => 'nullable|integer|between:1,12',
                'day' => 'nullable|integer|between:1,31',
            ],
            [
                'taskBody.min' => 'خلاصه مذاکره باید حداقل ۵ کاراکتر باشد.',
                'year.integer' => 'مقدار سال نامعتبر است.',
                'year.between' => 'سال باید بین ۱۴۰۴ تا ۱۴۳۰ باشد.',
                'month.integer' => 'مقدار ماه نامعتبر است.',
                'month.between' => 'ماه باید بین ۱ تا ۱۲ باشد.',
                'day.integer' => 'مقدار روز نامعتبر است.',
                'day.between' => 'روز باید بین ۱ تا ۳۱ باشد.',
            ]
        )->validate();

        // Validate date is not in the past
        if ($validated['year'] && $validated['month'] && $validated['day']) {
            // Convert current Gregorian date to Jalali
            list($ja_year, $ja_month, $ja_day) = explode('/',
                gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

            // Prevent selecting past dates
            if (
                $validated['year'] < $ja_year ||
                ($validated['year'] == $ja_year && $validated['month'] < $ja_month) ||
                ($validated['year'] == $ja_year && $validated['month'] == $ja_month && $validated['day'] < $ja_day)
            ) {
                $this->addError('year', 'تاریخ نباید گذشته باشد');
                return;
            }
        }

        $taskUser = $this->selectedTaskUser;
        $oldDate = $taskUser->time_out;
        $oldBody = $taskUser->task->body;
        $newBody = $this->normalizeText($validated['taskBody']);
        $newDate = null;
        if ($validated['year'] && $validated['month'] && $validated['day']) {
            $newDate = sprintf('%04d/%02d/%02d', $validated['year'], $validated['month'], $validated['day']);
        }
        // Notification data to send later
        $notificationsData = [];
        // Case 1: Date changed → update TaskUser
        if ($newDate && $newDate !== $oldDate) {
            $targetTaskUser = \App\Models\TaskUser::where('user_id', $taskUser->user_id)
                ->where('task_id', $taskUser->task_id)
                ->first();
            if ($targetTaskUser) {
                $targetTaskUser->time_out = $newDate;
                $targetTaskUser->save();
                // Prepare notification message for time_out update
                $notificationMessage = "مهلت انجام اقدام شما به تاریخ {$newDate} تغییر کرد.";
                $notificationsData[] = [
                    'type' => 'UpdatedTaskTimeOut',
                    'data' => json_encode(['message' => $notificationMessage]),
                    'notifiable_type' => \App\Models\TaskUser::class,
                    'notifiable_id' => $targetTaskUser->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $targetTaskUser->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        // Case 2: taskBody changed → create new Task and TaskUser
        if ($newBody && trim($newBody) !== trim($oldBody)) {
            $taskUsersCount = $taskUser->task->taskUsers()->count();

            // Create new Task
            $newTask = $taskUser->task->replicate();
            $newTask->body = $newBody;
            $newTask->save();

            // Create new TaskUser
            $newTaskUser = $taskUser->replicate();
            $newTaskUser->task_id = $newTask->id;
            $newTaskUser->time_out = $newDate ?: $taskUser->time_out;
            $newTaskUser->save();

            // Always delete old task_user for the same user
            $taskUser->delete();

            // Delete old task if no other users left
            if ($taskUsersCount === 1) {
                $taskUser->task->delete();
            }
            // Prepare notification message for body update
            $notificationMessage = "متن وظیفه شما به روز رسانی شد.";
            $notificationsData[] = [
                'type' => 'UpdatedTaskBody',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => \App\Models\TaskUser::class,
                'notifiable_id' => $newTaskUser->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $newTaskUser->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // Insert notifications if any
        if (!empty($notificationsData)) {
            \App\Models\Notification::insert($notificationsData);
        }
        session()->flash('status', 'اطلاعات با موفقیت ویرایش شد');
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        return redirect()->to($signedUrl);
    }
    public function delete($taskUserId)
    {
        TaskUser::findOrFail($taskUserId)->delete();
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'اقدام کننده با موفقیت حذف شد');
        return redirect()->to($signedUrl);
    }

    public function openDeleteTaskModal()
    {
        $this->dispatch('crud-modal', name: 'delete-task');
    }
    public function deleteTask()
    {
        abort_if(!Gate::allows('scriptorium-role'), 403);
        $validated = Validator::make(
            ['task_id' => $this->task_id,],
            ['task_id' => 'required|exists:tasks,id'],
            ['task_id.required' => 'انتخاب بند مذاکره اجباری است.', 'task_id.exists' => 'بند مذاکره نامعتبر است.',]
        )->validate();

        // Fetch the task for ownership check
        $task = DB::table('tasks')->where('id', $this->task_id)->first();

        // Optional: add null check just in case
        abort_if(!$task, 404);


        // Perform deletion in a transaction
        DB::transaction(function () {
            DB::table('task_users')->where('task_id', $this->task_id)->delete();
            DB::table('tasks')->where('id', $this->task_id)->delete();
        });
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'بند مذاکره با موفقیت حذف شد.');
        return redirect()->to($signedUrl);
    }


    public function openParticipantTaskBodyModal($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
        $this->selectedTaskUser = $taskUser;
        $this->dispatch('crud-modal', name: 'participant-body-task');
    }

    public function render()
    {
        return view('livewire.view-task-page');
    }

    #[Computed]
    public function meetings()
    {
        // Load once and cache inside the object
        if (!$this->loadedMeeting) {
            $this->loadedMeeting = Meeting::with([
                'meetingUsers.user.user_info:id,user_id,full_name',
                'tasks.user.user_info:id,user_id,full_name',
                'tasks.taskUsers.taskUserFiles',
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
    #[Computed]
    public function taskUsers()
    {
        $taskIds = $this->tasks()->pluck('id');
        return TaskUser::with([
            'user.user_info:id,user_id,full_name',
            'taskUserFiles'
        ])
            ->whereIn('task_id', $taskIds)
            ->select('id', 'task_id', 'user_id', 'request_task', 'task_status')
            ->get();
    }

    /**
     * To accept the Task by participant
     * @throws AuthorizationException
     */
    public function acceptTask($taskId)
    {
        $task = Task::findOrFail($taskId);

        $isParticipant = $task->taskUsers->contains('user_id', auth()->id());
        abort_unless($isParticipant, 403);

        DB::table('task_users')
            ->where('task_id', $task->id)
            ->where('user_id', auth()->id())
            ->update(['task_status' => TaskStatus::ACCEPTED->value]);

        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'خلاصه مذاکره تایید شد');
        return redirect()->to($signedUrl);
    }
    /**
     * To show Task form model to the participant
     * @throws AuthorizationException
     */



    public function showTaskForm($taskUserId)
    {
        $taskUser = TaskUser::with([
            'task',
            'user.user_info:id,user_id,full_name,position'
        ])
            ->where('user_id',auth()->id())
            ->findOrFail($taskUserId);
        $this->authorize('participantTask',$taskUser);
        $this->selectedTaskUser = $taskUser;
        $this->selectedTaskUserId = $taskUser->id;
        $this->participantName = $taskUser->user->user_info->full_name ?? '';
        $this->dispatch('crud-modal', name: 'view-task-details-modal');
    }



    protected function validateFilesSize($files, $maxSizeInMB = 2)
    {
        foreach ($files as $file) {
            if ($file->getSize() > $maxSizeInMB * 1024 * 1024) {
                $this->addError('files', 'فایل "' . $file->getClientOriginalName() . '" بیشتر از ' . $maxSizeInMB . ' مگابایت است.');
                return false;
            }
        }
        return true;
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
        if (!empty($this->files) && !$this->validateFilesSize($this->files)) {
            return;
        }

        $participantTaskBody = $this->normalizeText($this->participantTaskBody);


        // Update TaskUser via DB
        $updated = DB::table('task_users')
            ->where('id', $this->selectedTaskUserId)
            ->where('user_id', auth()->id())
            ->update([
                'body_task' => $participantTaskBody,
                'task_status' => TaskStatus::IS_COMPLETED->value
            ]);

        if (!$updated) {
            abort(403, 'Unauthorized or TaskUser not found.');
        }

        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $path = $file->store('task_files', 'public');
                DB::table('task_user_files')->insert([
                    'task_user_id' => $this->selectedTaskUserId,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        $this->dispatch('close-modal');
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'اقدام با موفقیت ذخیره شد.');
        return redirect()->to($signedUrl);
    }
    /**
     * To Show update Task form to the participant
     */
    public function openUpdateModal($taskUserId)
    {
        $this->selectedTaskUser = TaskUser::with([
            'task',
            'user.user_info:id,user_id,full_name,position'
        ])
            ->where('user_id',auth()->id())
            ->findOrFail($taskUserId);
        $this->authorize('participantTask',$this->selectedTaskUser);

        // Preload the task body for editing
        $this->updateTaskBody = $this->selectedTaskUser->body_task;
        $this->selectedTaskUserId = $this->selectedTaskUser->id;
        // Preload existing files related to the task
        $this->selectedTaskFiles = $this->selectedTaskUser->taskUserFiles;
        // Dispatch event to open the modal
        $this->dispatch('crud-modal', name: 'edit-task-details-modal');
    }
    public function deleteUploadedFile($fileId)
    {
        $file = TaskUserFile::findOrFail($fileId);
        Storage::disk('public')->delete($file->file_path);
        $file->delete();
        $this->selectedTaskFiles = $this->selectedTaskUser->taskUserFiles()->get();
    }

    public function updatedNewFiles($files)
    {
        foreach ($files as $file) {
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

        $taskUser = DB::table('task_users')
            ->where('id', $this->selectedTaskUserId)
            ->first();

        abort_unless($taskUser && $taskUser->user_id === auth()->id(), 403);

        // Update TaskUser body_task
        DB::table('task_users')
            ->where('id', $this->selectedTaskUserId)
            ->update([
                'body_task' => $updateTaskBody,
                'updated_at' => now(),
            ]);

        // Insert new files linked to TaskUser
        if ($this->newFiles && count($this->newFiles) > 0) {
            foreach ($this->newFiles as $file) {
                $path = $file->store('task_files', 'public');
                DB::table('task_user_files')->insert([
                    'task_user_id' => $this->selectedTaskUserId,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->dispatch('close-modal');
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'اقدام با موفقیت ویرایش شد.');
        return redirect()->to($signedUrl);
    }


    /**
     * To open modal for ending the meeting
     */
    public function showFinalCheck($meetingId)
    {
        $this->meeting = $meetingId;
        $this->dispatch('crud-modal', name: 'final-check');
    }
    /**
     * To Confirm the end meeting
     */
    public function finishMeeting()
    {

        DB::table('meetings')->where('id', $this->meeting)
            ->update([
            'status' => MeetingStatus::IS_FINISHED->value,
            'end_time' => now()->format('H:i'),
        ]);
        $this->dispatch('close-modal');
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'جلسه خاتمه یافت');
        return redirect()->to($signedUrl);
    }






    /**
     * To deny the Task by participant
     * @throws AuthorizationException
     */
    public function openDenyModal($taskUserId)
    {
        $taskUser = TaskUser::findOrFail($taskUserId);
        $this->authorize('participantTask',$taskUser);
        $this->selectedTaskUser = $taskUser;
        $this->selectedTaskUserId = $taskUser->id;
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

        $request_task = $this->normalizeText($this->request_task);

        // Find the Task model

        $taskUser = TaskUser::where('id', $this->selectedTaskUserId)->where('user_id', auth()->id())->firstOrFail();

        abort_unless($taskUser->user_id === auth()->id(), 403);

        // Update the task's status and reason
        $taskUser->update([
            'task_status' => TaskStatus::DENIED,
            'request_task' => $request_task,
        ]);

        // Get the related meeting
        $meeting = $taskUser->task->meeting;

        // Prepare the notification message
        $notificationMessage = $request_task;

        // Find the scriptorium (recipient) user ID
        $recipientId = User::whereHas('user_info', function ($query) use ($meeting) {
            $query->where('full_name', $meeting->scriptorium);
        })->value('id');

        if ($recipientId) {
            Notification::create([
                'type' => 'DeniedTaskNotification',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $recipientId,
            ]);
        }
        $this->dispatch('close-modal');
        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'درخواست رد شما به دبیر جلسه ارسال شد.');
        return redirect()->to($signedUrl);
    }

    public function sendToScriptorium($taskId)
    {
        $taskUser = DB::table('task_users')
            ->where('task_id', $taskId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$taskUser) {
            abort(404);
        }

        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $newTime = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        DB::table('task_users')
            ->where('task_id', $taskId)
            ->where('user_id', auth()->id())
            ->update([
                'sent_date' => $newTime,
                'task_status' => TaskStatus::SENT_TO_SCRIPTORIUM->value,
            ]);

        $signedUrl = URL::signedRoute('view.task.page', ['meeting' => $this->meeting]);
        session()->flash('status', 'شرح اقدام به دبیر جلسه ارسال شد.');
        return redirect()->to($signedUrl);
    }

    protected function normalizeText($text)
    {
        $text = strip_tags($text);                         // Remove HTML tags
        $text = preg_replace('/\s+/', ' ', trim($text));   // Normalize whitespace
        $words = explode(' ', $text);                      // Split into words
        return implode(' ', $words);                 // Join back to string
    }
}
