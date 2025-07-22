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

        $meetingName = $meeting->title ?? 'بدون عنوان';
        $meetingDate = $meeting->date ?? 'تاریخ مشخص نشده';
        $meetingTime = $meeting->time ?? 'ساعت مشخص نشده';

        foreach ($participantUserIds as $userId) {
            $notification = Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meeting->id)
                ->where('recipient_id', $userId)
                ->first();

            if ($notification) {
                // Update existing notification
                $notification->type = 'MeetingConfirmed';
                $notification->data = json_encode([
                    'message' => "جلسه '{$meetingName}' در تاریخ {$meetingDate} و ساعت {$meetingTime} برگزار خواهد شد."
                ]);
                $notification->recipient_read_at = null;
                $notification->updated_at = now();
                $notification->save();
            } else {
                // Optionally create a new notification if one doesn't exist
                Notification::create([
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meeting->id,
                    'recipient_id' => $userId,
                    'type' => 'MeetingConfirmed',
                    'data' => json_encode([
                        'message' => "جلسه '{$meetingName}' در تاریخ {$meetingDate} و ساعت {$meetingTime} برگزار خواهد شد."
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
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

        $meetingName = $meeting->title ?? 'بدون عنوان';
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
                'message' => "جلسه '{$meetingName}' در تاریخ {$meetingDate} و ساعت {$meetingTime} لغو شد."
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
    public function meetings()
    {
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'statusFilter' => $this->statusFilter,
            'scriptoriumFilter' => $this->scriptoriumFilter,
        ];
        return $this->baseFilteredMeetingQuery($filters)->paginate(5);
    }
    public function baseFilteredMeetingQuery($filters)
    {
        $search = trim($filters['search'] ?? '');
        $startDate = trim($filters['start_date'] ?? '');
        $endDate = trim($filters['end_date'] ?? '');
        $statusFilter = $filters['statusFilter'] ?? '';
        $scriptoriumFilter = $filters['scriptoriumFilter'] ?? 'all';
        $userId = auth()->id();

        return Meeting::with([
            'scriptorium.user_info.department',
            'boss.user_info.department',
            'meetingUsers.user.user_info.department',
        ])
            ->select([
                'id', 'title', 'scriptorium_id', 'boss_id', 'location',
                'date', 'time', 'end_time', 'status', 'unit_held', 'treat', 'guest'
            ])
            ->where(function ($query) use ($userId) {
                $query->where('scriptorium_id', $userId)
                    ->orWhereHas('meetingUsers', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
            })
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%")
                        ->orWhereHas('scriptorium.user_info', function ($sub) use ($search) {
                            $sub->where('full_name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('scriptorium.user_info.department', function ($sub) use ($search) {
                            $sub->where('department_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($statusFilter !== '', function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when(!empty($startDate) && !empty($endDate), function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($scriptoriumFilter === 'mine', function ($query) use ($userId) {
                $query->where('scriptorium_id', $userId);
            });
    }
    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'statusFilter' => $this->statusFilter,
            'scriptoriumFilter' => $this->scriptoriumFilter
        ];
        $meetings = $this->baseFilteredMeetingQuery($filters)
            ->with([
                'scriptorium.user_info.department',
                'boss.user_info.department',
                'meetingUsers.user.user_info.department',
            ])
            ->get();
        return Excel::download(new MeetingsExport($meetings), 'meetings.xlsx');
    }


    public $selectedMeeting;

    public function view($id)
    {
        $this->selectedMeeting = Meeting::with([
            'meetingUsers' => function ($query) {
                $query->select(
                    'id', 'meeting_id', 'user_id', 'is_guest', 'is_present', 'reason_for_absent', 'replacement'
                )->with([
                    'user.user_info.department'
                ]);
            },
            'scriptorium.user_info.department',
            'boss.user_info.department'
        ])
            ->select(
                'id', 'title', 'scriptorium_id', 'location', 'date', 'time',
                'unit_held', 'treat', 'guest', 'boss_id', 'status'
            )
            ->findOrFail($id);

        $this->dispatch('crud-modal', name: 'view-meeting-modal');
    }


    public function deleteMeeting($id)
    {
        $this->selectedMeeting = Meeting::select('id', 'title', 'date', 'time','scriptorium_id')
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
