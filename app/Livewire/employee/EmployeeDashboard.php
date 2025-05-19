<?php

namespace App\Livewire\employee;


use App\Enums\MeetingStatus;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
use App\Models\User;
use App\Traits\HasNotificationCount;
use App\Traits\MeetingsTasks;
use App\Traits\MessageReceived;
use App\Traits\Organizations;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Nette\Schema\ValidationException;

class EmployeeDashboard extends Component
{

    use MeetingsTasks,HasNotificationCount;


    #[Computed]
    public function getTodaysMeeting()
    {

        // Convert current Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        $newDate = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        return Meeting::where('date',$newDate)
            ->where('status',MeetingStatus::IS_NOT_CANCELLED->value)
            ->select('id','title','date','time','location')
            ->orderBy('time', 'asc')
            ->get();
    }
    

    public function render()
    {
        return view('livewire.employee.employee-dashboard');
    }


    #[Computed]
    public function meetings(){
        return Meeting::where('scriptorium', '=' ,auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=','-1')
            ->count();
    }

    #[Computed]
    public function meetingsSchedule()
    {
        return Meeting::with('meetingUsers')
            ->where('is_cancelled','=','-1')
            ->whereRelation('meetingUsers','user_id','=',auth()->user()->id)
            ->get(['title','location','date','time']);
    }
    #[Computed]
    public function invitation()
    {
        return MeetingUser::where('user_id',auth()->user()->id)->where('is_present',0)->count();
    }

}
