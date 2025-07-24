<?php

namespace App\Livewire;

use App\Enums\TaskStatus;
use App\Models\Meeting;
use App\Models\TaskUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TaskReport extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date = '';
    public $end_date = '';
    public $statusFilter = null;

    public function filterTasks()
    {
        $this->resetPage();
    }

    #[On('updateTaskStatusFilter')]
    public function setTaskStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.task-report');
    }


    #[Computed]
    public function tasks()
    {
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->statusFilter,
        ];

        $paginated = $this->baseFilteredMeetingQuery($filters)->paginate(5);

        foreach ($paginated as $taskUser) {
            if (empty($taskUser->sent_date)) {
                $taskUser->remaining_diff = $this->calculateRemainingDifference($taskUser->time_out);
                $taskUser->past_diff = $this->calculatePastDifference($taskUser->time_out);
            }
        }

        return $paginated;
    }

    public function baseFilteredMeetingQuery($filters)
    {
        $search = strip_tags(trim($filters['search'] ?? ''));
        $startDate = strip_tags(trim($filters['start_date'] ?? ''));
        $endDate = strip_tags(trim($filters['end_date'] ?? ''));

        $statusFilter = array_key_exists('status', $filters) && $filters['status'] !== null
            ? strip_tags(trim($filters['status']))
            : null;

        // Get current Jalali date in yyyy/mm/dd format
        [$ja_year, $ja_month, $ja_day] = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);

        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium_id',
            'task.meeting.scriptorium.user_info:id,user_id,full_name',
            'user:id',
            'user.user_info:id,user_id,full_name',
        ]);

        // Apply status filters
        switch ($statusFilter) {
            case '1': // On-time sent
                $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                    ->whereNotNull('sent_date')
                    ->whereColumn('sent_date', '<=', 'time_out');
                break;

            case '2': // Late sent
                $query->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
                    ->whereNotNull('sent_date')
                    ->whereColumn('sent_date', '>', 'time_out');
                break;

            case '3': // Pending, not yet overdue
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '>=', $now);
                break;

            case '4': // Pending and overdue
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '<=', $now);
                break;
        }

        // Apply date range
        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('time_out', [$startDate, $endDate]);
        }

        // Apply search filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', "%$search%")
                    ->orWhere('sent_date', 'like', "%$search%")
                    ->orWhereHas('task.meeting', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%$search%")
                            ->orWhereHas('scriptorium.user_info', function ($q3) use ($search) {
                                $q3->where('full_name', 'like', "%$search%");
                            });
                    })
                    ->orWhereHas('user.user_info', function ($q4) use ($search) {
                        $q4->where('full_name', 'like', "%$search%");
                    });
            });
        }

        return $query->orderBy('created_at');
    }


    #[Computed]
    public function percentages()
    {
        $counts = [
            1 => $this->tasksOnTime,
            2 => $this->tasksDoneWithDelay,
            3 => $this->tasksNotDoneOnTime,
            4 => $this->tasksNotDoneWithDelay,
        ];

        $total = array_sum($counts);

        return array_map(function ($count) use ($total) {
            return $total ? round(($count / $total) * 100, 1) : 0;
        }, $counts);
    }


    #[Computed]
    public function tasksOnTime()
    {
        return DB::table('task_users')
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '<=', 'time_out')
            ->count();
    }
    #[Computed]
    public function tasksDoneWithDelay()
    {
        return DB::table('task_users')
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '>', 'time_out')
            ->count();
    }
    #[Computed]
    public function tasksNotDoneOnTime()
    {
        $getDate = list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year,$ja_month,$ja_day);

        return DB::table('task_users')
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date',null)
            ->where('time_out', '>=' , $now)
            ->count();
    }
    #[Computed]
    public function tasksNotDoneWithDelay()
    {
        $getDate = list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $now = sprintf('%04d/%02d/%02d', $ja_year,$ja_month,$ja_day);

        return DB::table('task_users')
            ->where('task_status', TaskStatus::PENDING->value)
            ->where('sent_date',null)
            ->where('time_out', '<=' , $now)
            ->count();
    }

    private function calculatePastDifference($jalaliDate): ?string
    {
        [$year, $month, $day] = explode('/', $jalaliDate);
        if (!$year || !$month || !$day) return 'تاریخ نامعتبر';

        $gregorianDate = Carbon::parse(jalali_to_gregorian($year, $month, $day, '/'));
        $now = Carbon::now();

        if ($now->lessThanOrEqualTo($gregorianDate)) return null;

        $diff = $now->diff($gregorianDate);
        $text = ($diff->m ? $diff->m . ' ماه ' : '') . ($diff->d ? $diff->d . ' روز ' : '');

        return 'گذشته: ' . ($text ?: 'کمتر از یک روز');
    }
    private function calculateRemainingDifference($jalaliDate): ?string
    {
        [$year, $month, $day] = explode('/', $jalaliDate);
        if (!$year || !$month || !$day) return 'تاریخ نامعتبر';

        $gregorianDate = Carbon::parse(jalali_to_gregorian($year, $month, $day, '/'));
        $now = Carbon::now();

        if ($now->greaterThanOrEqualTo($gregorianDate)) return null;

        $diff = $gregorianDate->diff($now);
        $text = ($diff->m ? $diff->m . ' ماه ' : '') . ($diff->d ? $diff->d . ' روز ' : '');

        return 'باقی مانده: ' . ($text ?: 'امروز');
    }


}
