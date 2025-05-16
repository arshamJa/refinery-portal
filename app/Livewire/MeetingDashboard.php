<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Exports\MeetingsExport;
use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class MeetingDashboard extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.meeting-dashboard');
    }


    public $search = '';
    public $statusFilter = '';
    public $scriptoriumFilter = '';
    public $start_date;
    public $end_date;
    public function filterMeetings()
    {
        $this->resetPage();
    }



    #[Computed]
    public function meetings()
    {
        // Get the filters passed via request or Livewire properties
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'statusFilter' => $this->statusFilter,
            'scriptoriumFilter' => $this->scriptoriumFilter
        ];
        return $this->baseFilteredMeetingQuery($filters)->paginate(5);
    }

    public function baseFilteredMeetingQuery($filters)
    {
        // Extract filters with trimming applied
        $search = trim($filters['search'] ?? '');  // Get the search value
        $startDate = trim($filters['start_date'] ?? '');  // Get the start date value
        $endDate = trim($filters['end_date'] ?? '');  // Get the end date value
        $statusFilter = $filters['statusFilter'] ?? '';  // Get the status filter value
        $scriptoriumFilter = $filters['scriptoriumFilter'] ?? 'all';

        return Meeting::with([
            'meetingUsers:id,meeting_id,user_id,is_guest,is_present,reason_for_absent,read_by_scriptorium,read_by_user,replacement',
            'meetingUsers.user:id',
            'meetingUsers.user.user_info:id,user_id,full_name',
            'meetingUsers.user.user_info.department:id,department_name',
        ])
            ->select([
                'id', 'title', 'unit_organization', 'scriptorium', 'boss', 'location',
                'date', 'time','end_time','status', 'position_organization', 'unit_held', 'applicant'
            ])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('unit_organization', 'like', "%{$search}%")
                        ->orWhere('scriptorium', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter !== '', fn($query) => $query->where('status', $statusFilter))
            ->when(!empty($startDate) && !empty($endDate), function ($query) use ($startDate, $endDate) {
                $query->dateRange($startDate, $endDate);  // Assuming dateRange is a scope or method for filtering dates
            })
//            this mine comes from the select value
            ->when($scriptoriumFilter === 'mine', function ($query) {
                $query->where('scriptorium', auth()->user()->user_info->full_name); // Assuming the scriptorium is the authenticated user's name
            });
    }
    public function exportExcel()
    {
        // Get the filters from the request
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'statusFilter' => $this->statusFilter,
            'scriptoriumFilter' => $this->scriptoriumFilter
        ];

        // Get the filtered meetings based on the provided filters
        $meetings = $this->baseFilteredMeetingQuery($filters)
            ->with([
                'meetingUsers:id,meeting_id,user_id,is_guest,is_present,reason_for_absent,read_by_scriptorium,read_by_user,replacement',
                'meetingUsers.user:id',
                'meetingUsers.user.user_info:id,user_id,full_name,department_id',
                'meetingUsers.user.user_info.department:id,department_name',
            ])
            ->get();// Get all relevant data including meeting users and their associated info
        // Export the filtered meetings to Excel
        return Excel::download(new MeetingsExport($meetings), 'meetings.xlsx');
    }

    public $selectedMeeting;

    public function view($id)
    {
        $this->selectedMeeting = Meeting::with([
            'meetingUsers' => function ($query) {
                $query->select(
                    'id', 'meeting_id', 'user_id', 'is_guest', 'is_present', 'reason_for_absent',
                    'read_by_scriptorium', 'read_by_user', 'replacement'
                )
                    ->with([
                        'user.user_info.department'
                    ]);
            }
        ])
            ->select(
                'id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time',
                'unit_held', 'treat', 'applicant', 'position_organization', 'guest', 'boss'
            )
            ->findOrFail($id);

        $this->dispatch('crud-modal', name: 'view-meeting-modal');
    }


    public function deleteMeeting($id)
    {
        $this->selectedMeeting = Meeting::select('id', 'title', 'date', 'time')
            ->findOrFail($id);
        $this->dispatch('crud-modal', name: 'delete-meeting-modal');
    }

    public function startMeeting($id)
    {
        Meeting::where('id', $id)->update([
            'status' => MeetingStatus::IS_IN_PROGRESS->value,
        ]);
    }

}
