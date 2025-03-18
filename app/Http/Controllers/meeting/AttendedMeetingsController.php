<?php

namespace App\Http\Controllers\meeting;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class AttendedMeetingsController extends Controller
{
    public function index(Request $request)
    {

        $query = Task::with('meeting:id,title,scriptorium,date,time')
            ->where('user_id', auth()->user()->id)
            ->select('id', 'meeting_id', 'time_out', 'sent_date', 'is_completed'); // Select specific columns from Task

        $originalTasksCount = $query->count(); // Count before any filtering

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('meeting', function ($meeting) use ($search) {
                $meeting->where('title', 'like', '%' . $search . '%')
                    ->orWhere('scriptorium', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%')
                    ->orWhere('time', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('task_status')) {
            $taskStatus = $request->input('task_status');
            if ($taskStatus === '0' || $taskStatus === '1') {
                $query->where('is_completed', $taskStatus);
            }
        }
        $tasks = $query->paginate(5);
        $filteredTasksCount = $tasks->total(); // Count after filtering

        return view('meeting.attended-meetings', [
            'tasks' => $tasks,
            'originalTasksCount' => $originalTasksCount,
            'filteredTasksCount' => $filteredTasksCount,
        ]);
    }
}
