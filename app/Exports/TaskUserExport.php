<?php

namespace App\Exports;

use App\Enums\TaskStatus;
use App\Models\TaskUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TaskUserExport implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate;
    protected $endDate;
    protected $search;
    protected $statusFilter;
    protected $now;

    public function __construct($startDate = null, $endDate = null, $search = null, $statusFilter = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
        $this->statusFilter = $statusFilter;

        // Generate Jalali now date (format YYYY/MM/DD)
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $this->now = sprintf('%04d/%02d/%02d', $ja_year, $ja_month, $ja_day);
    }

    public function collection()
    {
        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium_id,boss_id',
            'user:id',
            'user.user_info:id,user_id,full_name',
        ]);

        // Apply status filter logic
        switch ($this->statusFilter) {
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
                    ->where('time_out', '>=', $this->now);
                break;

            case '4': // Incomplete and delayed
                $query->where('task_status', TaskStatus::PENDING->value)
                    ->whereNull('sent_date')
                    ->where('time_out', '<=', $this->now);
                break;

            default:
                // No specific status filter, fetch all
                break;
        }

        // Apply date range filter
        if ($this->startDate && $this->endDate) {
            $query->where('time_out', '>=', $this->startDate)
                ->where('time_out', '<=', $this->endDate);
        }

        // Apply search filter
        if ($this->search) {
            $search = $this->search;
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

        $statusMap = [
            \App\Enums\TaskStatus::SENT_TO_SCRIPTORIUM->value => 'ارسال شده',
            \App\Enums\TaskStatus::PENDING->value => 'در انتظار',
        ];

        return $query->get()->map(function ($taskUser, $index) use ($statusMap) {
            return [
                'ردیف'            => $index + 1,
                'موضوع جلسه'      => $taskUser->task->meeting->title ?? 'N/A',
                'دبیر جلسه'       => $taskUser->task->meeting->scriptorium->user_info->full_name ?? 'N/A',
                'اقدام کننده'     => $taskUser->user->user_info->full_name ?? 'N/A',
                'تاریخ انجام اقدام' => $taskUser->sent_date ?? '---',
                'تاریخ مهلت اقدام' => $taskUser->time_out ?? '---',
                'وضعیت'           => $statusMap[$taskUser->task_status] ?? '---',
            ];
        });
    }
    public function headings(): array
    {

        return [
            'ردیف',
            'موضوع جلسه',
            'دبیر جلسه',
            'اقدام کننده',
            'تاریخ انجام اقدام',
            'تاریخ مهلت اقدام',
            'وضعیت'
        ];
    }
    public function title(): string
    {
        return 'Tasks Report';
    }
}
