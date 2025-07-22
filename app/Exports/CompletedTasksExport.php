<?php

namespace App\Exports;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\TaskUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class CompletedTasksExport implements FromCollection, WithHeadings, WithColumnFormatting, WithTitle
{
    protected $startDate;
    protected $endDate;
    protected $search;

    public function __construct($startDate, $endDate, $search)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
    }

    public function collection()
    {

        $query = TaskUser::with([
            'task:id,meeting_id',
            'task.meeting:id,title,scriptorium_id',
            'task.meeting.scriptorium.user_info:id,user_id,full_name',
            'user:id',
            'user.user_info:id,user_id,full_name',
        ])
            ->where('task_status', TaskStatus::SENT_TO_SCRIPTORIUM->value)
            ->whereColumn('sent_date', '<=', 'time_out');

        // Apply date range filter (using the Jalali date as string)
        if ($this->startDate && $this->endDate) {
            $query->where('time_out', '>=', $this->startDate)
                ->where('time_out', '<=', $this->endDate);
        }


        // Apply search filter
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('time_out', 'like', "%{$search}%")
                    ->orWhere('sent_date', 'like', "%{$search}%")
                    ->orWhereHas('task.meeting', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%");
                    })
                    ->orWhereHas('task.meeting.scriptorium.user_info', function ($q3) use ($search) {
                        $q3->where('full_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user.user_info', function ($q4) use ($search) {
                        $q4->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        // Fetch the filtered data with necessary columns and map it
        return $query->get()->map(function ($taskUser, $index) {
            return [
                'Row' => $index + 1,
                'Meeting Title' => $taskUser->task->meeting->title ?? 'N/A',
                'Secretary' => $taskUser->task->meeting->scriptorium->user_info->full_name ?? 'نامشخص',
                'Task Performer' => $taskUser->user->user_info->full_name ?? 'نامشخص',
                'Sent Date' => $taskUser->sent_date ?? 'N/A',
                'Time Out' => $taskUser->time_out ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ردیف',  // Row number
            'موضوع جلسه', // Meeting Title
            'دبیر جلسه',  // Secretary
            'اقدام کننده',  // Task Performer
            'تاریخ انجام اقدام',  // Sent Date
            'تاریخ مهلت اقدام',  // Time Out
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => 'yyyy-mm-dd',  // Sent Date
            'F' => 'yyyy-mm-dd',  // Time Out
        ];
    }

    public function title(): string
    {
        return 'Completed Tasks';
    }
}
