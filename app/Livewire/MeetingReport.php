<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Enums\TaskStatus;
use App\Exports\MeetingsExport;
use App\Models\Meeting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class MeetingReport extends Component
{
    use WithPagination;

    public $search = '';
    public $start_date = '';
    public $end_date = '';
    public $statusFilter = null;

    public function filterMeetings()
    {
        $this->resetPage();
    }

    #[On('updateStatusFilter')]
    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }


    #[Computed]
    public function meetings()
    {
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->statusFilter,
        ];
        return $this->baseFilteredMeetingQuery($filters)->paginate(5);
    }
    public function baseFilteredMeetingQuery($filters)
    {
        $search = trim($filters['search'] ?? '');
        $startDate = trim($filters['start_date'] ?? '');
        $endDate = trim($filters['end_date'] ?? '');
        $statusFilter = $filters['status'] ?? null;
        return Meeting::with([
            'scriptorium.user_info.department',
            'boss.user_info.department',
        ])
            ->select([
                'id', 'title', 'scriptorium_id', 'boss_id',
                'date', 'time', 'end_time', 'status','location','unit_held'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%")
                        ->orWhereHas('scriptorium.user_info', fn($sub) =>
                        $sub->where('full_name', 'like', "%{$search}%"))
                        ->orWhereHas('scriptorium.user_info.department', fn($sub) =>
                        $sub->where('department_name', 'like', "%{$search}%"))
                        ->orWhereHas('boss.user_info', fn($sub) =>
                        $sub->where('full_name', 'like', "%{$search}%"))
                        ->orWhereHas('boss.user_info.department', fn($sub) =>
                        $sub->where('department_name', 'like', "%{$search}%"));
                });
            })
            ->when($statusFilter !== null, fn($q) => $q->where('status', $statusFilter))
            ->when(!empty($startDate) && !empty($endDate), fn($query) => $query->whereBetween('date', [$startDate, $endDate]));
    }

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->statusFilter,
        ];
        $meetings = $this->baseFilteredMeetingQuery($filters)->get();
        return Excel::download(new MeetingsExport($meetings), 'meetings.xlsx');
    }


    #[Computed]
    public function percentages(): array
    {
        return Cache::remember('meeting_percentages', 3600, function () {
            $counts = DB::table('meetings')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            $totalMeetings = array_sum($counts);

            $percentages = [];
            foreach (MeetingStatus::cases() as $status) {
                $count = $counts[$status->value] ?? 0;
                $percentages[$status->value] = $totalMeetings > 0
                    ? round(($count / $totalMeetings) * 100, 2)
                    : 0;
            }

            return $percentages;
        });
    }



    public function render()
    {
        return view('livewire.meeting-report');
    }
}
