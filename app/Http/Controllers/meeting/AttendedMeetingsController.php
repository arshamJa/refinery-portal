<?php

namespace App\Http\Controllers\meeting;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class AttendedMeetingsController extends Controller
{
    public function index(Request $request)
    {
        $completedTask = Task::where('user_id',auth()->user()->id)->where('is_completed',true)->count();
        $uncompletedTask = Task::where('user_id',auth()->user()->id)->where('is_completed',false)->count();

//        $query = MeetingUser::with('meeting:id,title,scriptorium,date,time')
//            ->where('user_id', auth()->user()->id)
//            ->select(['id', 'meeting_id', 'user_id', 'is_present']);

        $query = Task::with('meeting')->where('user_id',auth()->user()->id);

//        if ($request->filled('search')){
//            $search = $request->input('search');
//            $query->whereHas('meeting' , function ($meeting) use ($search){
//                $meeting->where('title','like','%'.$search.'%')
//                    ->orWhere('scriptorium','like','%'.$search.'%')
//                    ->orWhere('date','like','%'.$search.'%')
//                    ->orWhere('time','like','%'.$search.'%');
//            });
//        }
        $tasks = $query->paginate(5);
//        $meetingUsers = $query->paginate(5);

        return view('meeting.attended-meetings',[
//            'meetingUsers' => $meetingUsers,
            'tasks' => $tasks,
            'completedTask' => $completedTask,
            'uncompletedTask' => $uncompletedTask
        ]);
    }
}
