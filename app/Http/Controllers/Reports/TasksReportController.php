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
            'task' => function($query) {
                $query->select('id', 'meeting_id'); // Select specific columns from the task table
            },
            'task.meeting' => function($query) {
                $query->select('id', 'title', 'scriptorium'); // Select specific columns from the meeting table
            },
            'user' => function($query) {
                // Only select the user id to avoid duplicate queries for user_info
                $query->select('id');
            },
            'user.user_info' => function($query) {
                $query->select('id', 'full_name', 'user_id'); // Select specific columns from the user_info table
            }
        ])
            ->where('task_status', TaskStatus::IS_COMPLETED->value)
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
        $taskUsers = $query->paginate(5);
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
        $query = Task::with('meeting')
            ->where('is_completed', true)
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
        $tasks = $query->paginate(5);
        return view('reports.report-delay-tasks', ['tasks' => $tasks]);
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
        $query = TaskUser::with([
            'task' => function($query) {
                $query->select('id', 'meeting_id'); // Select specific columns from the task table
            },
            'task.meeting' => function($query) {
                $query->select('id', 'title', 'scriptorium'); // Select specific columns from the meeting table
            },
            'user' => function($query) {
                // Only select the user id to avoid duplicate queries for user_info
                $query->select('id');
            },
            'user.user_info' => function($query) {
                $query->select('id', 'full_name', 'user_id'); // Select specific columns from the user_info table
            }
        ])
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date', null);


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


        $taskUsers = $query->paginate(5);
        // Calculate the difference for each task user
        foreach ($taskUsers as $taskUser) {
            $taskUser->formatted_diff = $this->calculateDateDifference($taskUser->time_out);
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
    private function calculateDateDifference($jalaliDate)
    {
        $dateParts = explode('/', $jalaliDate);
        $year = $dateParts[0] ?? null;
        $month = $dateParts[1] ?? null;
        $day = $dateParts[2] ?? null;

        if (!$year || !$month || !$day) {
            return 'Invalid Date';
        }

        // Convert Jalali to Gregorian date
        $gregorianDateString = jalali_to_gregorian($year, $month, $day, '/');
        $gregorianDate = Carbon::parse($gregorianDateString);

        // Current Gregorian Date (now)
        $currentDate = Carbon::now();

        // Calculate the difference and determine past or remaining
        if ($currentDate->greaterThan($gregorianDate)) {
            $diff = $currentDate->diff($gregorianDate);
            $formattedDiff = 'گذشته: ';
        } else {
            $diff = $gregorianDate->diff($currentDate);
            $formattedDiff = 'باقی مانده: ';
        }

        // Build the difference string
//        $formattedDiff .= ($diff->y > 0) ? $diff->y . ' سال ' : '';
        $formattedDiff .= ($diff->m > 0) ? $diff->m . ' ماه ' : '';
        $formattedDiff .= ($diff->d > 0) ? $diff->d . ' روز ' : '';
//        $formattedDiff .= ($diff->h > 0) ? $diff->h . ' ساعت ' : '';
//        $formattedDiff .= ($diff->i > 0) ? $diff->i . ' دقیقه ' : '';
//        $formattedDiff .= ($diff->s > 0) ? $diff->s . ' ثانیه ' : '';

        // If no difference found, show less than a second
        return trim($formattedDiff) ?: 'کمتر از یک ثانیه';
    }
}
