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
            'task' => function($query) {
                $query->select('id', 'meeting_id'); // Select only the necessary columns for task
            },
            'task.meeting' => function($query) {
                $query->select('id', 'title', 'scriptorium'); // Select necessary columns from meeting
            },
            'user' => function($query) {
                $query->select('id'); // Only select the user id to avoid duplicates
            },
            'user.user_info' => function($query) {
                $query->select('user_id', 'full_name'); // Select necessary columns from user_info
            }
        ])
            ->where('task_status', TaskStatus::ACCEPTED->value)
            ->whereColumn('sent_date', '<=', 'time_out');

        // Apply date range filter (using the Jalali date as string)
        if ($this->startDate && $this->endDate) {
            $query->where('time_out', '>=', $this->startDate)
                ->where('time_out', '<=', $this->endDate);
        }


        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('time_out', 'like', '%' . $this->search . '%')
                    ->orWhere('sent_date', 'like', '%' . $this->search . '%')
                    ->orWhereHas('task.meeting', function ($meetingQuery) {
                        $meetingQuery->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('scriptorium', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('user.user_info', function ($userInfoQuery) {
                        $userInfoQuery->where('full_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Fetch the filtered data with necessary columns and map it
        return $query->get()->map(function ($taskUser, $index) {
            return [
                'Row' => $index + 1,  // Add the row number
                'Meeting Title' => $taskUser->task->meeting->title ?? 'N/A',
                'Secretary' => $taskUser->task->meeting->scriptorium ?? 'N/A',
                'Task Performer' => $taskUser->user->user_info->full_name ?? 'N/A',
                'Sent Date' => $taskUser->sent_date,
                'Time Out' => $taskUser->time_out,
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
