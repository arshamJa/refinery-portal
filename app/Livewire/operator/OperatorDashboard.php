<?php

namespace App\Livewire\operator;

use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Traits\MeetingsTasks;
use App\Traits\MessageReceived;
use App\Traits\Organizations;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class OperatorDashboard extends Component
{

    use MeetingsTasks, MessageReceived;



    public function render()
    {
        return view('livewire.operator.operator-dashboard');
    }


    #[Computed]
    public function getTodaysMeeting()
    {

        // Convert current Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        $newDate = sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);

        return Meeting::where('date',$newDate)
            ->where('is_cancelled','-1')
            ->select('id','title','date','time','location')
            ->orderBy('time', 'asc')
            ->get();
    }
}
