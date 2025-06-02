<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Exports\MeetingsExport;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
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


    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with(['user', 'meeting', 'user.user_info'])
            ->get(['meeting_id','user_id','is_guest','is_present','reason_for_absent','replacement']);
    }

    public function acceptMeeting($meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);
        $meeting->status = MeetingStatus::IS_NOT_CANCELLED->value;
        $meeting->save();

        // Fetch all users (participants + inner guests), regardless of attendance
        $participantUserIds = DB::table('meeting_users')
            ->where('meeting_id', $meeting->id)
            ->pluck('user_id');

        $meetingDate = $meeting->date ?? 'تاریخ مشخص نشده';
        $meetingTime = $meeting->time ?? 'ساعت مشخص نشده';

        foreach ($participantUserIds as $userId) {
            $notification = Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meeting->id)->where('recipient_id', $userId)
                ->first();
            // Update existing notification
            $notification->type = 'MeetingConfirmed';
            $notification->data = json_encode([
                'message' => "این جلسه در تاریخ {$meetingDate} و ساعت {$meetingTime} برگزار خواهد شد."
            ]);
            $notification->recipient_read_at = null;
            $notification->updated_at = now();
            $notification->save();
        }
        $this->dispatch('close-modal');
        return to_route('dashboard.meeting')->with('status', 'جلسه با موفقیت تایید نهایی شد و شرکت‌کنندگان مطلع شدند.');
    }
    public function denyMeeting($meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);
        $meeting->status = MeetingStatus::IS_CANCELLED->value;
        $meeting->save();

        // Fetch all users (participants + inner guests), regardless of attendance
        $participantUserIds = DB::table('meeting_users')
            ->where('meeting_id', $meeting->id)
            ->pluck('user_id');

        $meetingDate = $meeting->date ?? 'تاریخ مشخص نشده';
        $meetingTime = $meeting->time ?? 'ساعت مشخص نشده';

        foreach ($participantUserIds as $userId) {
            $notification = Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meeting->id)
                ->where('recipient_id', $userId)
                ->first();
            // Update existing notification
            $notification->type = 'MeetingCancelled';
            $notification->data = json_encode([
                'message' => "این جلسه در تاریخ {$meetingDate} و ساعت {$meetingTime} لغو شد."
            ]);
            $notification->recipient_read_at = null;
            $notification->updated_at = now();
            $notification->save();
        }
        $this->dispatch('close-modal');
        return to_route('dashboard.meeting')->with('status', 'جلسه با موفقیت لغو نهایی شد و شرکت‌کنندگان مطلع شدند');

    }


    public function render()
    {
        return view('livewire.meeting-dashboard');
    }


    public $search = '';
    public $statusFilter = '';
    public $scriptoriumFilter = '';
    public $start_date;
    public $end_date;
    public $bossInfo;
    public function filterMeetings()
    {
        $this->resetPage();
    }




    #[Computed]
    public function unreadReceivedCount()
    {
        return Notification::where('recipient_id', auth()->id())
            ->whereNull('recipient_read_at')
            ->count();
    }
    #[Computed]
    public function unreadSentCount()
    {
        return Notification::where('sender_id', auth()->id())
            ->whereNull('sender_read_at')
            ->count();
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
        $userId = auth()->id();
        $userFullName = auth()->user()->user_info->full_name;

        return Meeting::with([
            'meetingUsers:id,meeting_id,user_id,is_guest,is_present,reason_for_absent,replacement',
            'meetingUsers.user:id',
            'meetingUsers.user.user_info:id,user_id,full_name',
            'meetingUsers.user.user_info.department:id,department_name',
        ])
            ->select([
                'id', 'title', 'scriptorium_department', 'scriptorium', 'boss', 'location',
                'date', 'time','end_time','status', 'scriptorium_position', 'unit_held'
            ])
            ->where(function ($query) use ($userId, $userFullName) {
                $query->where('scriptorium', $userFullName)
                    ->orWhereHas('meetingUsers', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
            })
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('scriptorium_department', 'like', "%{$search}%")
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
                'meetingUsers:id,meeting_id,user_id,is_guest,is_present,reason_for_absent,replacement',
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
                    'id', 'meeting_id', 'user_id', 'is_guest', 'is_present', 'reason_for_absent', 'replacement'
                )
                    ->with([
                        'user.user_info.department'
                    ]);
            }
        ])
            ->select(
                'id', 'title', 'scriptorium_department', 'scriptorium', 'location', 'date', 'time',
                'unit_held', 'treat', 'scriptorium_position', 'guest', 'boss','status'
            )
            ->findOrFail($id);

        $this->bossInfo = \App\Models\UserInfo::with('department')
            ->where('full_name', $this->selectedMeeting->boss)
            ->first();


        $this->dispatch('crud-modal', name: 'view-meeting-modal');
    }


    public function deleteMeeting($id)
    {
        $this->selectedMeeting = Meeting::select('id', 'title', 'date', 'time','scriptorium','scriptorium_position')
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
