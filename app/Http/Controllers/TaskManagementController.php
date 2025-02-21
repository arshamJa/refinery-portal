<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\UserInfo;
use App\Rules\DateRule;
use App\Rules\farsi_chs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TaskManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('task.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $meeting)
    {
        $meetings = Meeting::find($meeting);
        $meetingUsers = MeetingUser::where('meeting_id',$meeting)->where('is_present','=','1')->get();
        $tasks = Task::with('user')->where('meeting_id',$meeting)->get();
        return view('task.create' , [
            'meetings' => $meetings,
            'meetingUsers' => $meetingUsers,
            'tasks' => $tasks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request,string $meeting)
    {
        $validated = $request->validate([
           'initiator' => ['required'],
//            'time_out' => ['required','date_format:Y/m/d' , new DateRule()],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'body' => ['required','string']
        ]);
        $gr_day = now()->day;
        $gr_month = now()->month;
        $gr_year = now()->year;
        $time = gregorian_to_jalali($gr_year,$gr_month,$gr_day,'/');
        $parts = explode("/", $time);
        $ja_year = $parts[0];
        $ja_month = $parts[1];
        $ja_day = $parts[2];

        $date = '';
        if ($request->year < $ja_year){
            throw ValidationException::withMessages([
                'year' => 'سال گذشته نباید باشد'
            ]);
        }else{
            if ($request->month < $ja_month){
                throw ValidationException::withMessages([
                    'month' => 'ماه گذشته نباید باشد'
                ]);
            }else{
                if ($request->day < $ja_day){
                    throw ValidationException::withMessages([
                        'day' => 'روز گذشته نباید باشد'
                    ]);
                }
                else{
                    $date = $request->year .'/'. $request->month .'/'. $request->day;
                }
            }
        }
        $body = Str::deduplicate($request->body);
        $task = Task::create([
            'meeting_id' => $meeting,
            'user_id' => $request->initiator,
            'body' => $body,
            'time_out' => $date
        ]);
        return to_route('tasks.create',$meeting)->with('status','درج اقدام انجام شد');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::find($id);
        $taskUsers = DB::table('task_user')->get(['id','task_id','file']);
        return view('task.show',['task'=>$task , 'taskUsers' => $taskUsers]);
    }

    public function complete(string $id)
    {
        $task = Task::find($id);
        $task->is_completed = true;
        $task->save();
        return to_route('tasks.index')->with('status','وظیفه انجام شد');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request , string $id)
    {
        $request->validate([
            'body' => 'required'
        ]);
        $task = Task::find($id);
        $task->request_task = $request->body;
        $task->save();
        return to_route('tasks.show',$id)->with('status','درخواست شما ارسال شد');
    }

    public function editUserTask(string $taskId)
    {
        $task = Task::with('meeting')->find($taskId);
        return view('task.editUserTask',['task' => $task]);
    }

    public function updateUserTask(Request $request , string $taskId)
    {
        $validated = $request->validate([
            'title' => 'required',
            'body' => 'required',
            'sent_date' => 'required',
            'time_out' => 'required',
            'files' => 'nullable',
        ]);
        $task = Task::find($taskId);
        $task->title = $request->title;
        $task->body = $request->body;
        $task->sent_date = $request->sent_date;
        $task->time_out = $request->time_out;
        $task->request_task = null;
        $task->save();
        return redirect()->signedRoute('meetings.show',$task->meeting_id)->with('status','درخواست کارمند انجام شد');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
