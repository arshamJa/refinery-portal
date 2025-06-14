<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Exports\TaskUserExport;
use App\Models\Meeting;
use App\Models\TaskUser;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class MeetingReportTableController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $statusFilter = $request->input('statusFilter');
        $meetings = Meeting::select([
                'id', 'title', 'scriptorium', 'boss', 'date', 'time', 'end_time', 'status',
            ])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('scriptorium', 'like', "%{$search}%")
                        ->orWhere('boss', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%");
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
            ->paginate(5);

        return view('reportsTable.meeting-report-table', [
            'meetings' => $meetings
        ]);
    }

    public function show(Meeting $meeting)
    {
        $bossInfo = UserInfo::where('full_name', $meeting->boss)->first();
        $guests = is_string($meeting->guest) ? json_decode($meeting->guest, true) : (array) $meeting->guest;
        $innerGuests = $meeting->meetingUsers->where('is_guest', true);
        $participants = $meeting->meetingUsers->where('is_guest', false);

        return view('reportsTable.meeting-details', [
            'meeting' => $meeting,
            'bossInfo'=> $bossInfo,
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

        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium',
            'user:id',
            'user.user_info:id,user_id,full_name',
        ]);

        // Apply status filtering based on statusFilter input
        switch ($statusFilter) {
            case '1': // Completed on time
                $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                    ->whereColumn('sent_date', '<=', 'time_out');
                break;

            case '2': // Completed with delay
                $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                    ->whereColumn('sent_date', '>', 'time_out');
                break;

            case '3': // Incomplete within deadline
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '>=', $now);
                break;

            case '4': // Incomplete and delayed
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '<=', $now);
                break;

            default:
                // No specific status filter, fetch all
                break;
        }

        // Date filter
        if ($startDate && $endDate) {
            $query->where('time_out', '>=', $startDate)
                ->where('time_out', '<=', $endDate);
        }

        // Search filter
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

        $query->orderBy('created_at');
        $taskUsers = $query->paginate(10);

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
            'task.meeting',
            'user.user_info'
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
                    ->orWhereHas('task.meeting', fn($mq) =>
                    $mq->where('title', 'like', "%{$search}%")
                        ->orWhere('scriptorium', 'like', "%{$search}%")
                    )
                    ->orWhereHas('user.user_info', fn($uq) =>
                    $uq->where('full_name', 'like', "%{$search}%")
                    );
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
