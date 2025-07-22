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
        $query = Task::with([
            'meeting.scriptorium.user_info',
            'user.user_info'
        ])
            ->where('is_completed', true)
            ->whereColumn('sent_date', '>', 'time_out'); // delayed tasks

        // Apply date range filter (using the Jalali date as string)
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
                    ->orWhereHas('meeting', function ($meetingQuery) use ($search) {
                        $meetingQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhereHas('scriptorium.user_info', function ($scriptoriumQuery) use ($search) {
                                $scriptoriumQuery->where('full_name', 'like', '%' . $search . '%');
                            });
                    })
                    ->orWhereHas('user.user_info', function ($userInfoQuery) use ($search) {
                        $userInfoQuery->where('full_name', 'like', '%' . $search . '%');
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
                'Secretary'     => $task->meeting->scriptorium->user_info->full_name ?? 'N/A',
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
