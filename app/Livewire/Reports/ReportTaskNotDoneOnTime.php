<?php
//
//namespace App\Livewire\Reports;
//
//use App\Models\Task;
//use Livewire\Attributes\Computed;
//use Livewire\Component;
//use Livewire\WithoutUrlPagination;
//use Livewire\WithPagination;
//
//class ReportTaskNotDoneOnTime extends Component
//{
//    use WithPagination, WithoutUrlPagination;
//
//    public $start_date = '';
//    public $end_date = '';
//    public ?string $search = '';
//
//    public function render()
//    {
//        return view('livewire.reports.report-task-not-done-on-time');
//    }
//    #[Computed]
//    public function tasks()
//    {
//        $query = Task::with('meeting')
//            ->where('is_completed', false)
//            ->where('sent_date', null);
//
//        $startDate = trim($this->start_date);
//        $endDate = trim($this->end_date);
//
//        if ($startDate && $endDate) {
//            $query->where('time_out', '>=', $startDate)
//                ->where('time_out', '<=', $endDate);
//        }
//        return $query->get();
//    }
//}
