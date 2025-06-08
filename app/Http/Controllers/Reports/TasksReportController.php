<?php

namespace App\Http\Controllers\Reports;

use App\Enums\TaskStatus;
use App\Exports\CompletedTasksExport;
use App\Exports\CompletedTasksWithDelayExport;
use App\Exports\IncompleteTasksExport;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TasksReportController extends Controller
{
    public function completedTasks(Request $request)
    {
        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium',
            'user:id', // Must include 'id' for the relation to work
            'user.user_info:id,user_id,full_name', // Must include user_id to relate properly
        ])
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '<=', 'time_out');

        // Get the start and end date inputs from the request.
        $startDate = trim($request->input('start_date') ?? '');
        $endDate = trim($request->input('end_date') ?? '');
        $search = trim($request->input('search') ?? '');

        // Apply date range filter if both start and end dates are provided.
        if ($startDate && $endDate) {
            $query->where('time_out', '>=', $startDate)
                ->where('time_out', '<=', $endDate);
        }

        // Apply the search filter if a search term is provided.
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', '%' . $search . '%')
                    ->orWhere('sent_date', 'like', '%' . $search . '%')
                    ->orWhereHas('task.meeting', function ($meetingQuery) use ($search) {
                        $meetingQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('scriptorium', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user.user_info', function ($userInfoQuery) use ($search) {
                        $userInfoQuery->where('full_name', 'like', '%' . $search . '%');
                    });
            });
        }
        $taskUsers = $query->paginate(10);
        return view('reports.report-completed-tasks', ['taskUsers' => $taskUsers]);
    }
    public function downloadCompletedTasksExcel(Request $request)
    {
        // Get the filters from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Trigger the export using the CompletedTasksExport class with the filters
        return Excel::download(new CompletedTasksExport($startDate, $endDate, $search), 'completed_tasks.xlsx');
    }

    public function completedTasksWithDelay(Request $request)
    {
        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium',
            'user:id', // Must include 'id' for the relation to work
            'user.user_info:id,user_id,full_name', // Must include user_id to relate properly
        ])
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '>', 'time_out');

        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $search = trim($request->input('search'));

        if ($startDate && $endDate) {
            $query->where('time_out', '>=', $startDate)
                ->where('time_out', '<=', $endDate);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', '%'.$search.'%')
                    ->orWhere('sent_date', 'like', '%'.$search.'%')
                    ->orWhereHas('meeting', function ($meetingQuery) use ($search) {
                        $meetingQuery->where('title', 'like', '%'.$search.'%')
                            ->orWhere('scriptorium', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->whereHas('user_info', function ($userInfoQuery) use ($search) {
                            $userInfoQuery->where('full_name', 'like', '%'.$search.'%');
                        });
                    });
            });
        }
        $tasks = $query->paginate(10);
        return view('reports.report-delay-tasks', ['taskUsers' => $tasks]);
    }
    public function downloadCompletedTasksWithDelayExcel(Request $request)
    {
        // Get the filters from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Trigger the export using the CompletedTasksWithDelayExport class with the filters
        return Excel::download(new CompletedTasksWithDelayExport($startDate, $endDate, $search), 'completed_tasks_with_delay.xlsx');
    }



    public function incompleteTasks(Request $request)
    {

        $getDate = list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year,$ja_month,$ja_day);

        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium',
            'user:id', // Must include 'id' for the relation to work
            'user.user_info:id,user_id,full_name', // Must include user_id to relate properly
        ])
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date', null)
            ->where('time_out', '>=' , $now);

        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $search = trim($request->input('search'));

        if ($startDate && $endDate) {
            $query->where('time_out', '>=', $startDate)
                ->where('time_out', '<=', $endDate);
        }
        // Apply the search filter if a search term is provided.
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', '%' . $search . '%')
                    ->orWhere('sent_date', 'like', '%' . $search . '%')
                    ->orWhereHas('task.meeting', function ($meetingQuery) use ($search) {
                        $meetingQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('scriptorium', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user.user_info', function ($userInfoQuery) use ($search) {
                        $userInfoQuery->where('full_name', 'like', '%' . $search . '%');
                    });
            });
        }
        $taskUsers = $query->paginate(10);
        // Calculate the difference for each task user
//        foreach ($taskUsers as $taskUser) {
//            $taskUser->formatted_diff = $this->calculateDateDifference($taskUser->time_out);
//        }
        foreach ($taskUsers as $taskUser) {
            $taskUser->remaining_diff = $this->calculateRemainingDifference($taskUser->time_out);
        }
        return view('reports.report-inComplete-tasks', ['taskUsers' => $taskUsers]);
    }
    public function downloadIncompleteTasksExcel(Request $request)
    {
        // Get the filters from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Trigger the export using the IncompleteTasksExport class with the filters
        return Excel::download(new IncompleteTasksExport($startDate, $endDate, $search), 'incomplete_tasks.xlsx');
    }


    public function incompleteTasksWithDelay(Request $request)
    {
        $getDate = list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year,$ja_month,$ja_day);
        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium',
            'user:id', // Must include 'id' for the relation to work
            'user.user_info:id,user_id,full_name', // Must include user_id to relate properly
        ])
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date', null)
            ->where('time_out', '<=' , $now);

        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $search = trim($request->input('search'));

        if ($startDate && $endDate) {
            $query->where('time_out', '>=', $startDate)
                ->where('time_out', '<=', $endDate);
        }
        // Apply the search filter if a search term is provided.
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', '%' . $search . '%')
                    ->orWhere('sent_date', 'like', '%' . $search . '%')
                    ->orWhereHas('task.meeting', function ($meetingQuery) use ($search) {
                        $meetingQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('scriptorium', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user.user_info', function ($userInfoQuery) use ($search) {
                        $userInfoQuery->where('full_name', 'like', '%' . $search . '%');
                    });
            });
        }
        $taskUsers = $query->paginate(10);

        // Calculate the difference for each task user
//        foreach ($taskUsers as $taskUser) {
//            $taskUser->formatted_diff = $this->calculateDateDifference($taskUser->time_out);
//        }
        foreach ($taskUsers as $taskUser) {
            $taskUser->past_diff = $this->calculatePastDifference($taskUser->time_out);
        }
        return view('reports.report-inComplete-tasks-withDelay', ['taskUsers' => $taskUsers]);
    }





    private function calculatePastDifference($jalaliDate)
    {
        $dateParts = explode('/', $jalaliDate);
        [$year, $month, $day] = [$dateParts[0] ?? null, $dateParts[1] ?? null, $dateParts[2] ?? null];

        if (!$year || !$month || !$day) {
            return 'تاریخ نامعتبر';
        }

        $gregorianDate = Carbon::parse(jalali_to_gregorian($year, $month, $day, '/'));
        $now = Carbon::now();

        if ($now->lessThanOrEqualTo($gregorianDate)) {
            return null; // Not in the past
        }

        $diff = $now->diff($gregorianDate);
        $text = ($diff->m > 0 ? $diff->m . ' ماه ' : '') . ($diff->d > 0 ? $diff->d . ' روز ' : '');

        return 'گذشته: ' . ($text ?: 'کمتر از یک روز');
    }

    private function calculateRemainingDifference($jalaliDate)
    {
        $dateParts = explode('/', $jalaliDate);
        [$year, $month, $day] = [$dateParts[0] ?? null, $dateParts[1] ?? null, $dateParts[2] ?? null];

        if (!$year || !$month || !$day) {
            return 'تاریخ نامعتبر';
        }

        $gregorianDate = Carbon::parse(jalali_to_gregorian($year, $month, $day, '/'));
        $now = Carbon::now();

        if ($now->greaterThanOrEqualTo($gregorianDate)) {
            return null; // Not remaining
        }

        $diff = $gregorianDate->diff($now);
        $text = ($diff->m > 0 ? $diff->m . ' ماه ' : '') . ($diff->d > 0 ? $diff->d . ' روز ' : '');

        return 'باقی مانده: ' . ($text ?: 'امروز');
    }
}
