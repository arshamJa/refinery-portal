<?php

namespace App\Exports;

use App\Models\Task;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
class CompletedTasksWithDelayExport implements FromCollection, WithHeadings, WithColumnFormatting, WithTitle
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
            ->whereColumn('sent_date', '>', 'time_out'); // Tasks that are delayed

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

        // Get the tasks
        $tasks = $query->get();
        // Return the filtered data with delay duration
        return $tasks->map(function ($task, $index) {
            // Calculate delay duration, ensuring the result is always positive
            $sentDate = Carbon::parse($task->sent_date);
            $timeOut = Carbon::parse($task->time_out);

            // Ensure sent_date is after time_out to calculate delay correctly
            $delayDuration = $timeOut->diffInDays($sentDate);

            // Append "روز" to the delay duration
            $delayDurationWithText = $delayDuration . ' روز';

            return [
                'Row Number' => $index + 1, // Loop index (1-based)
                'Meeting Title' => $task->meeting->title ?? 'N/A',
                'Secretary' => $task->meeting->scriptorium ?? 'N/A',
                'Assigned User' => $task->user->user_info->full_name ?? 'N/A',
                'Action Date' => $task->sent_date,
                'Time Out' => $task->time_out,
                'Delay Duration' => $delayDurationWithText, // Added "روز" here
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ردیف',  // Row Number
            'موضوع جلسه',  // Meeting Title
            'دبیر جلسه',  // Secretary
            'افدام کننده',  // Assigned User
            'تاریخ انجام اقدام',  // Action Date (Sent Date)
            'تاریخ مهلت اقدام',  // Time Out
            'مدت زمان تاخیر',  // Delay Duration (in days)
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => 'yyyy-mm-dd',  // Action Date (Sent Date)
            'E' => 'yyyy-mm-dd',  // Time Out Date
        ];
    }

    public function title(): string
    {
        return 'Delayed Tasks';
    }
}
