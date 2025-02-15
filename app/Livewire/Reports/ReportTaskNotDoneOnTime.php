<?php

namespace App\Livewire\Reports;

use App\Models\Task;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ReportTaskNotDoneOnTime extends Component
{
    use WithPagination, WithoutUrlPagination;

    public ?string $search = '';

    public function render()
    {
        return view('livewire.reports.report-task-not-done-on-time');
    }
    #[Computed]
    public function tasks()
    {
        return Task::with('meeting')
            ->where('is_completed',false)
            ->where('sent_date',null)
            ->paginate(3);
    }
}
