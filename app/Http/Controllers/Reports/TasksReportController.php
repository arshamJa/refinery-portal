<?php

namespace App\Http\Controllers\Reports;

use App\Exports\CompletedTasksExport;
use App\Exports\CompletedTasksWithDelayExport;
use App\Exports\IncompleteTasksExport;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TasksReportController extends Controller
{
    public function completedTasks(Request $request)
    {
        $query = Task::with('meeting', 'user')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '<=', 'time_out');

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
        return view('reports.report-completed-tasks', ['tasks' => $tasks]);
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
        $query = Task::with('meeting')
            ->where('is_completed', false)
            ->where('sent_date', null);

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
        return view('reports.report-inComplete-tasks', ['tasks' => $tasks]);
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
}
