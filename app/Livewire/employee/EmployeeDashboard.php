<?php

namespace App\Livewire\employee;


use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\User;
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

    use MeetingsTasks, MessageReceived;


    #[Computed]
    public function getMeetingsToday()
    {

        $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $jaYear = $jalaliNow[0];
        $jaMonth = $jalaliNow[1];
        $jaDay = $jalaliNow[2];

        $new_month = sprintf("%02d", $jaMonth);
        $new_day = sprintf("%02d", $jaDay);

        $newDate = $jaYear . '/' . $new_month . '/' . $new_day;

        return Meeting::where('date',$newDate)->get();
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
