<?php

namespace App\Livewire;

use App\Enums\MeetingStatus;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PresentUsers extends Component
{
    public $meetingTitle;
    public $meetingId;

    public function render()
    {
        return view('livewire.present-users');
    }


    #[Computed]
    public function meetingUsers()
    {
        return MeetingUser::with(['user', 'meeting', 'user.user_info'])
            ->where('meeting_id',$this->meetingId)
            ->get(['meeting_id','user_id','is_guest','is_present','reason_for_absent','replacement']);
    }

    #[Computed]
    public function meetingStatus()
    {
        return Meeting::where('id',$this->meetingId)->value('status');
    }

    #[Computed]
    public function present()
    {
        return MeetingUser::where('meeting_id',$this->meetingId)->where('is_present',1)->count();
    }

    #[Computed]
    public function absent()
    {
        return MeetingUser::where('meeting_id',$this->meetingId)->where('is_present',-1)->count();
    }

    #[Computed]
    public function not_sent()
    {
        return MeetingUser::where('meeting_id',$this->meetingId)->where('is_present',0)->where('is_guest',0)->count();
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
        return to_route('dashboard.meeting')->with('status', 'جلسه با موفقیت تایید نهایی شد و شرکت‌کنندگان مطلع شدند.');
    }

    public function openModalDeny($department_id)
    {
        $this->meetingTitle = Meeting::where('id',$department_id)->value('title');
        $this->meetingId = $department_id;
        $this->dispatch('crud-modal', name: 'delete');
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
    public function accept($meetingId)
    {
        MeetingUser::where('user_id', auth()->user()->id)->where('meeting_id', $meetingId)->update([
            'is_present' => '1'
        ]);
        return redirect()->back();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return redirect()->back();
    }




}
