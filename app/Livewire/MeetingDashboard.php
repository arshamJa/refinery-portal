<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\MeetingUser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MeetingDashboard extends Component
{
    public function render()
    {
        return view('livewire.meeting-dashboard');
    }


    #[Computed]
    public function meetings()
    {
        return Meeting::with([
            'meetingUsers:id,meeting_id,user_id,is_guest,is_present,reason_for_absent,read_by_scriptorium,read_by_user,replacement'
        ])
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'is_cancelled'])
            ->paginate(5);
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
                        'user:id',
                        'user.user_info:id,user_id,full_name,position,department_id',
                        'user.user_info.department:id,department_name',
                    ]);
            }
        ])
            ->select(
                'id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time',
                'unit_held', 'treat', 'applicant', 'position_organization', 'guest'
            )
            ->findOrFail($id);
        $this->dispatch('crud-modal', name:'view-meeting-modal');
    }

    public function deleteMeeting($id)
    {
        $this->selectedMeeting = Meeting::select('id', 'title', 'date', 'time')
            ->findOrFail($id);
        $this->dispatch('crud-modal', name: 'delete-meeting-modal');
    }

}
