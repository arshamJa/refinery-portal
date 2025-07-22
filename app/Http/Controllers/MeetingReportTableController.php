<?php

namespace App\Http\Controllers;

use App\Enums\MeetingStatus;
use App\Enums\TaskStatus;
use App\Exports\MeetingsExport;
use App\Exports\TaskUserExport;
use App\Models\Meeting;
use App\Models\TaskUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MeetingReportTableController extends Controller
{
    public function meetingTable(Request $request)
    {
        $search = trim($request->input('search'));
        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $statusFilter = $request->has('statusFilter') ? $request->input('statusFilter') : null;
        $cacheKey = 'meeting_info_' . md5(json_encode([
                'search' => $search,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $statusFilter,
                'page' => $request->input('page', 1),
            ]));
        $meetings = Cache::remember($cacheKey, 3600, function () use ($search, $startDate, $endDate, $statusFilter, $request) {
            return Meeting::with(['scriptorium.user_info', 'boss.user_info'])
                ->select(['id', 'title', 'scriptorium_id', 'boss_id', 'date', 'time', 'end_time', 'status'])
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('date', 'like', "%{$search}%")
                            ->orWhere('time', 'like', "%{$search}%")
                            ->orWhereHas('scriptorium.user_info', fn($q2) => $q2->where('full_name', 'like', "%{$search}%"))
                            ->orWhereHas('boss.user_info', fn($q3) => $q3->where('full_name', 'like', "%{$search}%"));
                    });
                })
                ->when($request->has('statusFilter') && $statusFilter !== '', function ($q) use ($statusFilter) {
                    return $q->where('status', $statusFilter);
                })
                ->when($startDate, fn($q) => $q->where('date', '>=', $startDate))
                ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
                ->paginate(5);
        });
        $percentages = Cache::remember('meeting_percentages', 3600, function () {
            $counts = DB::table('meetings')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $totalMeetings = array_sum($counts);

            $percentages = [];
            foreach (MeetingStatus::cases() as $status) {
                $count = $counts[$status->value] ?? 0;
                $percentages[$status->value] = $totalMeetings > 0 ? round(($count / $totalMeetings) * 100, 2) : 0;
            }

            return $percentages;
        });
        [$ja_year, $ja_month, $ja_day] = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);
        $taskPercentages = Cache::remember('task_percentages', 3600, function () use ($now) {
            $tasksAggregated = DB::table('task_users')
                ->selectRaw("
                SUM(CASE WHEN task_status = ? AND sent_date <= time_out THEN 1 ELSE 0 END) as completed_on_time,
                SUM(CASE WHEN task_status = ? AND sent_date > time_out THEN 1 ELSE 0 END) as completed_with_delay,
                SUM(CASE WHEN task_status = ? AND sent_date IS NULL AND time_out >= ? THEN 1 ELSE 0 END) as incomplete_on_time,
                SUM(CASE WHEN task_status = ? AND sent_date IS NULL AND time_out <= ? THEN 1 ELSE 0 END) as incomplete_with_delay
            ", [
                    TaskStatus::SENT_TO_SCRIPTORIUM->value,
                    TaskStatus::SENT_TO_SCRIPTORIUM->value,
                    TaskStatus::PENDING->value,
                    $now,
                    TaskStatus::PENDING->value,
                    $now,
                ])
                ->first();

            $completedTasks = $tasksAggregated->completed_on_time;
            $completedTasksWithDelay = $tasksAggregated->completed_with_delay;
            $incompleteTasks = $tasksAggregated->incomplete_on_time;
            $incompleteTasksWithDelay = $tasksAggregated->incomplete_with_delay;

            $totalTasks = $completedTasks + $completedTasksWithDelay + $incompleteTasks + $incompleteTasksWithDelay;

            return [
                0 => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
                1 => $totalTasks > 0 ? round(($completedTasksWithDelay / $totalTasks) * 100, 2) : 0,
                2 => $totalTasks > 0 ? round(($incompleteTasks / $totalTasks) * 100, 2) : 0,
                3 => $totalTasks > 0 ? round(($incompleteTasksWithDelay / $totalTasks) * 100, 2) : 0,
            ];
        });
        return view('reportsTable.meeting-report-table', [
            'percentages' => $percentages,
            'taskPercentages' => $taskPercentages,
            'meetings' => $meetings
        ]);
    }
    public function downloadMeetingReport(Request $request)
    {
        $search = trim($request->input('search'));
        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $statusFilter = $request->input('statusFilter');
        $meetings = Meeting::with(['scriptorium', 'boss', 'meetingUsers.user.user_info'])
        ->when(!empty($search), function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhere('time', 'like', "%{$search}%")
                    ->orWhereHas('scriptorium.user_info', function ($q2) use ($search) {
                        $q2->where('full_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('boss.user_info', function ($q3) use ($search) {
                        $q3->where('full_name', 'like', "%{$search}%");
                    });
            });
        })
            ->when($statusFilter !== null && $statusFilter !== '', function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when(!empty($startDate), function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            })
            ->when(!empty($endDate), function ($query) use ($endDate) {
                $query->where('date', '<=', $endDate);
            })
            ->get();
        return Excel::download(new MeetingsExport($meetings), 'meeting_report.xlsx');
    }






    public function show(Meeting $meeting)
    {
        $meeting->load([
            'boss.user_info',
            'scriptorium.user_info',
            'meetingUsers.user.user_info.department'
        ]);
        $guests = is_string($meeting->guest) ? json_decode($meeting->guest, true) : (array) $meeting->guest;
        $innerGuests = $meeting->meetingUsers->where('is_guest', true);
        $participants = $meeting->meetingUsers->where('is_guest', false);
        return view('reportsTable.meeting-details', [
            'meeting' => $meeting,
            'guests' => $guests,
            'innerGuests' => $innerGuests,
            'participants' => $participants
        ]);
    }
    public function taskTable(Request $request)
    {
        // Convert current date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);

        // Get filters from request
        $startDate = trim($request->input('start_date') ?? '');
        $endDate = trim($request->input('end_date') ?? '');
        $search = trim($request->input('search') ?? '');
        $statusFilter = $request->input('statusFilter');

        // Generate a unique cache key
        $cacheKey = 'task_table_' . md5(json_encode([
                'start' => $startDate,
                'end' => $endDate,
                'search' => $search,
                'status' => $statusFilter,
                'page' => $request->input('page', 1),
            ]));

        // Try to retrieve from cache
        $taskUsers = Cache::remember($cacheKey, 3600, function () use ($search, $startDate, $endDate, $statusFilter, $now) {
            $query = TaskUser::with([
                'task:id,meeting_id',
                'task.meeting:id,title,scriptorium_id,boss_id',
                'task.meeting.boss.user_info:id,user_id,full_name',
                'user:id',
                'user.user_info:id,user_id,full_name',
            ]);

            switch ($statusFilter) {
                case '1':
                    $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                        ->whereColumn('sent_date', '<=', 'time_out');
                    break;
                case '2':
                    $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                        ->whereColumn('sent_date', '>', 'time_out');
                    break;
                case '3':
                    $query->where('task_status', TaskStatus::PENDING->value)
                        ->whereNull('sent_date')
                        ->where('time_out', '>=', $now);
                    break;
                case '4':
                    $query->where('task_status', TaskStatus::PENDING->value)
                        ->whereNull('sent_date')
                        ->where('time_out', '<=', $now);
                    break;
            }

            if ($startDate && $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('time_out', [$startDate, $endDate])
                        ->orWhere('time_out', 'some_other_value'); // adjust as needed
                });
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('time_out', 'like', '%' . $search . '%')
                        ->orWhere('sent_date', 'like', '%' . $search . '%')
                        ->orWhereHas('task.meeting', function ($q2) use ($search) {
                            $q2->where('title', 'like', '%' . $search . '%')
                                ->orWhereHas('scriptorium.user_info', function ($q3) use ($search) {
                                    $q3->where('full_name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('boss.user_info', function ($q4) use ($search) {
                                    $q4->where('full_name', 'like', '%' . $search . '%');
                                });
                        })
                        ->orWhereHas('user.user_info', function ($q5) use ($search) {
                            $q5->where('full_name', 'like', '%' . $search . '%');
                        });
                });
            }

            $query->orderBy('created_at');
            return $query->paginate(10);
        });

        // Manually calculate timing differences (not cached)
        foreach ($taskUsers as $taskUser) {
            if (empty($taskUser->sent_date)) {
                $taskUser->remaining_diff = $this->calculateRemainingDifference($taskUser->time_out);
                $taskUser->past_diff = $this->calculatePastDifference($taskUser->time_out);
            }
        }
        return view('reportsTable.task-report-table', [
            'taskUsers' => $taskUsers
        ]);
    }
    public function exportTasks(Request $request)
    {
        $startDate = trim($request->input('start_date') ?? '');
        $endDate = trim($request->input('end_date') ?? '');
        $search = trim($request->input('search') ?? '');
        $statusFilter = $request->input('statusFilter');
        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium_id,boss_id',
            'task.meeting.scriptorium:id',
            'task.meeting.scriptorium.user_info:id,user_id,full_name',
            'task.meeting.boss:id',
            'task.meeting.boss.user_info:id,user_id,full_name',
            'user:id',
            'user.user_info:id,user_id,full_name',
        ]);
        // Apply the same filters as in taskTable()
        switch ($statusFilter) {
            case '1':
                $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                    ->whereColumn('sent_date', '<=', 'time_out');
                break;
            case '2':
                $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                    ->whereColumn('sent_date', '>', 'time_out');
                break;
            case '3':
                $now = now()->format('Y/m/d'); // Or convert to Jalali accordingly
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '>=', $now);
                break;
            case '4':
                $now = now()->format('Y/m/d');
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '<=', $now);
                break;
        }
        if ($startDate && $endDate) {
            $query->where('time_out', '>=', $startDate)
                ->where('time_out', '<=', $endDate);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', "%{$search}%")
                    ->orWhere('sent_date', 'like', "%{$search}%")
                    ->orWhereHas('task.meeting', function ($mq) use ($search) {
                        $mq->where('title', 'like', "%{$search}%")
                            ->orWhereHas('scriptorium.user_info', function ($sq) use ($search) {
                                $sq->where('full_name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('boss.user_info', function ($bq) use ($search) {
                                $bq->where('full_name', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('user.user_info', function ($uq) use ($search) {
                        $uq->where('full_name', 'like', "%{$search}%");
                    });
            });
        }
        $taskUsers = $query->orderBy('created_at')->get();
        return Excel::download(new TaskUserExport($startDate, $endDate, $search, $statusFilter), 'tasks_report.xlsx');
    }
    private function calculatePastDifference($jalaliDate): ?string
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
    private function calculateRemainingDifference($jalaliDate): ?string
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
