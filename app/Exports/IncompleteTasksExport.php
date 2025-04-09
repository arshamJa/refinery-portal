<?php

namespace App\Exports;

use App\Models\Task;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
class IncompleteTasksExport implements FromCollection, WithHeadings, WithColumnFormatting, WithTitle
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
            ->where('is_completed', false)  // Incomplete tasks filter
            ->where('sent_date', null);  // Only tasks that haven't been completed yet

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

        // Get the tasks and map them with necessary data
        return $query->get()->map(function ($task, $index) {
            // Get the current date in Jalali
            $gr_day = now()->day;
            $gr_month = now()->month;
            $gr_year = now()->year;
            $time = gregorian_to_jalali($gr_year, $gr_month, $gr_day, '/');

            // Calculate the difference between time_out and current date
            $date1 = Carbon::parse($task->time_out);
            $date2 = Carbon::parse($time);
            $diff = $date1->diff($date2);
            $formattedDiff = '';

            // Format the difference similar to your original method
            if ($diff->y > 0) {$formattedDiff .= $diff->y . ' سال';}
            if ($diff->m > 0) {$formattedDiff .= $diff->m . ' ماه';}
            if ($diff->d > 0) {$formattedDiff .= $diff->d . ' روز';}
            if ($diff->h > 0) {$formattedDiff .= $diff->h . ' ساعت';}
            if ($diff->i > 0) {$formattedDiff .= $diff->i . ' دقیقه';}
            if ($diff->s > 0) {$formattedDiff .= $diff->s . ' ثانیه';}

            // Remove trailing commas and spaces
            $formattedDiff = rtrim($formattedDiff, ', ');

            // Return the task data with formatted date difference
            return [
                'Row Number' => $index + 1,  // Loop index (1-based)
                'Meeting Title' => $task->meeting->title ?? 'N/A',
                'Secretary' => $task->meeting->scriptorium ?? 'N/A',
                'Assigned User' => $task->user->user_info->full_name ?? 'N/A',
                'Action Date' => $task->sent_date ?? 'N/A',
                'Time Out' => $task->time_out ?? 'N/A',
                'Time Passed' => $formattedDiff,  // Formatted duration in years, months, days, etc.
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ردیف',  // Row Number
            'موضوع جلسه',  // Meeting Title
            'دبیر جلسه',  // Secretary
            'اقدام کننده',  // Assigned User
            'تاریخ انجام اقدام',  // Action Date (Sent Date)
            'تاریخ مهلت اقدام',  // Time Out
            'مدت زمان گذشته',  // Time Passed (Formatted duration)
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
        return 'Incomplete Tasks';  // Title for the Excel sheet
    }
}
