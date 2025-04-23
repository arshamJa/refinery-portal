<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\TaskUser;
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

        $employees = MeetingUser::with('user.user_info') // Eager load user and userInfo
        ->where('meeting_id', $meeting)
            ->get();


        // Eager load user and userInfo
        $tasks = Task::with('taskUsers')
            ->where('meeting_id', $meeting)
            ->get();

        $taskUsers = TaskUser::all();



        return view('task.create' , [
            'meetings' => $meetings,
            'employees' => $employees,
            'tasks' => $tasks,
            'taskUsers' => $taskUsers
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request,string $meeting)
    {
        $request->validate([
           'holders' => ['required'],
//            'time_out' => ['required','date_format:Y/m/d' , new DateRule()],
            'year' => ['required'],
            'month' => ['required'],
            'day' => ['required'],
            'body' => ['required', 'string', 'min:5']
        ]);
        $gr_day = now()->day;
        $gr_month = now()->month;
        $gr_year = now()->year;
        $time = gregorian_to_jalali($gr_year,$gr_month,$gr_day,'/');
        $parts = explode("/", $time);
        $ja_year = $parts[0];
        $ja_month = $parts[1];
        $ja_day = $parts[2];

        if (
            ($request->year < $ja_year) ||
            ($request->year == $ja_year && $request->month < $ja_month) ||
            ($request->year == $ja_year && $request->month == $ja_month && $request->day < $ja_day)
        ) {
            throw ValidationException::withMessages([
                'year' => 'تاریخ نمی‌تواند در گذشته باشد.'
            ]);
        }

        $new_month = sprintf("%02d", $request->month);
        $new_day = sprintf("%02d", $request->day);
        $newTime = $request->year . '/' . $new_month . '/' . $new_day;

        $body = Str::deduplicate($request->body);
        $initiators = Str::of($request->holders)->split('/[\s,]+/');


        $task = Task::create([
            'meeting_id' => $meeting,
            'body' => $body,
            'time_out' => $newTime
        ]);


        foreach ($initiators as $initiator) {
            TaskUser::create([
                'task_id' => $task->id,
                'user_id' => $initiator,
                'sent_date' => null,
                'is_completed' => false,
                'request_task' => null,
            ]);
        }









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
