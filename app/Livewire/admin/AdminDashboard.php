<?php

namespace App\Livewire\admin;
use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\User;
use App\Trait\MeetingsTasks;
use App\Trait\MessageReceived;
use App\Trait\Organizations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AdminDashboard extends Component
{

    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks, MessageReceived;
    public $meetingTitle;
    public $meeting_id;
    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
    public $yearData = [];
    public $currentYear = 1403; // Default year
    public $currentMonth = 0; // Default month (0 for first 6, 1 for last 6)

    public function mount()
    {
        $this->fetchData();
    }

    public function updatedCurrentYear()
    {
        $this->fetchData();
    }

    public function updatedCurrentMonth()
    {
        $this->fetchData(); // Refetch data when month changes
    }
    private function fetchData()
    {
        $this->yearData = [];

        // Loop through years 1403 to 1450
        for ($year = 1403; $year <= 1450; $year++) {
            $processedData = [
                'done' => array_fill(1, 12, 0),
                'notDone' => array_fill(1, 12, 0),
                'delayed' => array_fill(1, 12, 0),
            ];

            // Fetch tasks for the current year
            $tasks = DB::table('tasks')
                ->select('sent_date', 'time_out', 'is_completed')
                ->get();

            foreach ($tasks as $task) {
                // Count notDone tasks based on time_out
                if ($task->is_completed === 0) {
                    if ($task->time_out !== null) {
                        $timeOutParts = explode('/', $task->time_out);
                        if (count($timeOutParts) === 3) {
                            $taskYear = (int) $timeOutParts[0];
                            $taskMonth = (int) $timeOutParts[1];

                            if ($taskYear === $year) {
                                if ($this->currentMonth === 0 && $taskMonth >= 1 && $taskMonth <= 6) {
                                    $processedData['notDone'][$taskMonth]++;
                                } elseif ($this->currentMonth === 1 && $taskMonth >= 7 && $taskMonth <= 12) {
                                    $processedData['notDone'][$taskMonth - 6]++;
                                }
                            }
                        }
                    }
                    continue; // Skip further processing for is_completed = 0
                }
                // Count done and delayed tasks based on sent_date
                if ($task->sent_date !== null) {
                    $sentDateParts = explode('/', $task->sent_date);
                    if (count($sentDateParts) === 3) {
                        $taskYear = (int) $sentDateParts[0];
                        $month = (int) $sentDateParts[1];
                        if ($taskYear === $year) {
                            if ($this->currentMonth === 0 && $month >= 1 && $month <= 6) {
                                if ($task->is_completed === 1) {
                                    $processedData['done'][$month]++;
                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
                                        $processedData['delayed'][$month]++;
                                    }
                                }
                            } elseif ($this->currentMonth === 1 && $month >= 7 && $month <= 12) {
                                if ($task->is_completed === 1) {
                                    $processedData['done'][$month - 6]++;
                                    if ($task->time_out !== null && strtotime($task->sent_date) > strtotime($task->time_out)) {
                                        $processedData['delayed'][$month - 6]++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->yearData[$year] = $processedData;
        }
    }
    #[Computed]
    public function users()
    {
        return User::all()->count();
    }
    #[Computed]
    public function departments()
    {
        return Department::all()->count();
    }
    #[Computed]
    public function meetings(){
        return Meeting::where('scriptorium', '=' ,auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=','-1')
            ->count();
    }


    /**
     * this is for scriptoriums only
     */
//    #[Computed]
//    public function meetingNotifications()
//    {
//        return Meeting::where('scriptorium',auth()->user()->user_info->full_name)
//            ->where('is_cancelled','-1')
//            ->get(['title','location','date','time']);
//    }
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
    public function acceptMeeting($meetingId)
    {
        $meeting = Meeting::find($meetingId);
        $meeting->is_cancelled = '-1';
        $meeting->save();
        return redirect()->back();
    }

    public function openModalDelete($meetingId)
    {
        $this->meetingTitle = Meeting::where('id',$meetingId)->value('title');
        $this->meeting_id = $meetingId;
        $this->dispatch('crud-modal', name: 'delete');
    }
    public function denyMeeting($meetingId)
    {
        $meeting = Meeting::find($meetingId);
        $meeting->is_cancelled = '1';
        $meeting->save();
        $this->close();
    }
    public function accept($meetingId)
    {
        MeetingUser::where('user_id', auth()->user()->id)->where('meeting_id', $meetingId)->update([
            'is_present' => '1'
        ]);
        return redirect()->back();
    }

    public function deny($meetingId)
    {
        MeetingUser::where('user_id', auth()->user()->id)->where('meeting_id', $meetingId)->update([
            'is_present' => '-1'
        ]);
        return redirect()->back();
    }
    public function close()
    {
        $this->dispatch('close-modal');
        return redirect()->back();
    }
}
