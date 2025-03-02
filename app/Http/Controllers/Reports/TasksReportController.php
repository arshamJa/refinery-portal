<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

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

    public function completedTasksWithDelay(Request $request)
    {
        $query = Task::with('meeting')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '>=', 'time_out');

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
}
