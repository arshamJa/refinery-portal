<?php

namespace App\Livewire\Reports;

use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ReportTaskDoneWithDelay extends Component
{
    use WithPagination, WithoutUrlPagination;
    public ?string $search = '';

    public function render()
    {
        return view('livewire.reports.report-task-done-with-delay');
    }
    #[Computed]
    public function tasks()
    {
        return Task::with('meeting')
            ->where('is_completed',true)
            ->whereColumn('sent_date', '>', 'time_out')
            ->where('sent_date','like','%'.$this->search.'%')
            ->paginate(3);
    }
}
