<?php

namespace App\Exports;

use App\Models\Task;
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
        $query = Task::with('meeting', 'user')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '<=', 'time_out');

        // Apply date range filter (using the Jalali date as string)
        if ($this->startDate && $this->endDate) {
            $query->where('time_out', '>=', $this->startDate)
                ->where('time_out', '<=', $this->endDate);
        }


        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('time_out', 'like', '%'.$this->search.'%')
                    ->orWhere('sent_date', 'like', '%'.$this->search.'%')
                    ->orWhereHas('meeting', function ($meetingQuery) {
                        $meetingQuery->where('title', 'like', '%'.$this->search.'%')
                            ->orWhere('scriptorium', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->whereHas('user_info', function ($userInfoQuery) {
                            $userInfoQuery->where('full_name', 'like', '%'.$this->search.'%');
                        });
                    });
            });
        }

        // Return the filtered data with Jalali date format as strings
        return $query->get()->map(function ($task, $index) {
            return [
                'Row' => $index + 1,  // Add the row number
                'Meeting Title' => $task->meeting->title ?? 'N/A',
                'Secretary' => $task->meeting->scriptorium ?? 'N/A',
                'Task Performer' => $task->user->user_info->full_name ?? 'N/A',
                'Sent Date' => $task->sent_date,
                'Time Out' => $task->time_out,
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
