<?php

namespace App\Http\Controllers;

use App\Enums\UserPermission;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Permission;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TaskManagementController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request,string $meeting)
    {
        $request->validate([
           'holders' => ['required'],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'body' => ['required', 'string', 'min:5']
        ]);
        // Convert current Gregorian date to Jalali
        $gr_day = now()->day;
        $gr_month = now()->month;
        $gr_year = now()->year;
        $jalaliDate = gregorian_to_jalali($gr_year, $gr_month, $gr_day, '/');
        [$ja_year, $ja_month, $ja_day] = explode('/', $jalaliDate);

        if (
            ($request->year < $ja_year) ||
            ($request->year == $ja_year && $request->month < $ja_month) ||
            ($request->year == $ja_year && $request->month == $ja_month && $request->day < $ja_day)
        ) {
            throw ValidationException::withMessages([
                'year' => 'تاریخ نمی‌تواند در گذشته باشد.'
            ]);
        }

        $new_month = sprintf("%02d", $request->month);
        $new_day = sprintf("%02d", $request->day);
        $newTime = $request->year . '/' . $new_month . '/' . $new_day;

        $body = $this->normalizeText($request->body);
        $initiators = Str::of($request->holders)->split('/[\s,]+/');

        $task = Task::create([
            'meeting_id' => $request->meeting,
            'body'   => $body
        ]);

        foreach ($initiators as $initiator) {
            $taskUser = TaskUser::create([
                'task_id' => $task->id,
                'user_id' => $initiator,
                'time_out' => $newTime,
            ]);

            $notificationMessage = 'یک بند جدید به شما اختصاص داده شده است و مهلت انجام آن: ' . $taskUser->time_out;
            $notificationsData[] = [
                'type' => 'AssignedNewTask',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => \App\Models\Meeting::class,
                'notifiable_id' => $task->meeting_id,
                'sender_id' => auth()->id(),
                'recipient_id' => $initiator,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Notification::insert($notificationsData);
        return to_route('view.task.page',$meeting)->with('status','درج اقدام انجام شد');
    }
    protected function normalizeText($text)
    {
        $text = strip_tags($text);                         // Remove HTML tags
        $text = preg_replace('/\s+/', ' ', trim($text));   // Normalize whitespace
        $words = explode(' ', $text);                      // Split into words
        $uniqueWords = array_unique($words);               // Remove duplicate words
        return implode(' ', $uniqueWords);                 // Join back to string
    }
    public function lockTasks($meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);

        if (Gate::denies('lock-task')) {
            abort(403, 'دسترسی غیرمجاز');
        }

        // Authorization check
        if (
            auth()->user()->user_info->full_name !== $meeting->scriptorium ||
            auth()->user()->user_info->position !== $meeting->scriptorium_position
        ) {
            abort(403, 'دسترسی غیرمجاز');
        }
        // Lock all tasks related to the meeting
        Task::where('meeting_id', $meetingId)->update(['is_locked' => true]);

        session()->flash('status', 'وظایف این جلسه قفل شدند و دیگر قابل تغییر نیستند.');
        return back();
    }

}
