<?php

namespace App\Livewire\Reports;

use App\Models\Meeting;
use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


class ReportTasksDone extends Component
{
    use WithPagination, WithoutUrlPagination;


    public $start_date = '';
    public $end_date = '';
    public ?string $search = '';

    public function render()
    {
        return view('livewire.reports.report-tasks-done');
    }
//    #[Computed]
//    public function tasks()
//    {
//        $startDate = trim($this->start_date);
//        $endDate = trim($this->end_date);
//        if ($startDate && $endDate) {
//            $query = Task::query();
//            $query->where('time_out', '>', $startDate)
//                ->where('time_out', '<', $endDate);
//            return $query->paginate(3);
//        } else {
//            return Task::with('meeting')
//                ->where('is_completed', true)
//                ->whereColumn('sent_date', '<=', 'time_out')
//                ->where('sent_date', 'like', '%'.$this->search.'%')
//                ->paginate(3);
//        }
//    }


    #[Computed]
    public function tasks()
    {
        $tasks = Task::with('meeting')
            ->where('is_completed', true)
            ->whereColumn('sent_date', '<=', 'time_out')
//            ->where('sent_date', 'like', '%'.$this->search.'%')
            ->get();

        $startDate = trim($this->start_date);
        $endDate = trim($this->end_date);
        if ($startDate && $endDate) {
            $query = Task::query();
            $query->where('time_out', '>', $startDate)
                ->where('time_out', '<', $endDate);
            return $query->get();
        } else {
            return $tasks;
        }
    }
}
