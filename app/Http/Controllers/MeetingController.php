<?php

namespace App\Http\Controllers;

use App\DateConvertor;
use App\Events\NewMeetingCreated;
use App\Events\SetNewMeeting;
use App\Http\Requests\MeetingStoreRequest;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\User;
use App\Models\UserInfo;
use Closure;
use http\Encoding\Stream\Inflate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MeetingController extends Controller
{
    use DateConvertor;
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $users = User::query()
            ->join('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select('user_infos.id','user_infos.full_name')
            ->whereNull('deleted_at')
            ->where('role','=','employee')
            ->whereRelation('user_info','full_name','!=',auth()->user()->user_info->full_name)
            ->get();
        return view('meeting.create' , ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(MeetingStoreRequest $request)
    {


        $holders =  Str::of($request->holders)->split('/[\s,]+/');

        $date = $request->date;

        $g_day = jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[2] < 10 ?
            '0' . jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[2] :
            jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[2];

        $g_month = jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[1] < 10 ?
            '0' . jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[1]
            : jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[1];

        $g_year = jalali_to_gregorian(substr($date, 0, 4), substr($date, 5, 2), substr($date, 8, 2))[0];
        $gregorian_format = $g_month . '/' . $g_day . '/' . $g_year;


        $oldDate = Meeting::where('date',$request->date)->value('date');
        if (Meeting::where('date',$oldDate)->value('time') == $request->time){
            throw ValidationException::withMessages([
                'time' => 'در این زمان جلسه ثبت شده است'
            ]);
        }else{
            $request->validated();
            $signature_path = $request->signature->store('signatures','public');
            $meetings = Meeting::create([
                'title' => $request->title,
                'unit_organization' => $request->unit_organization,
                'scriptorium' => $request->scriptorium,
                'location' => $request->location,
                'date' => $request->date,
                'time' => $request->time,
                'unit_held' => $request->unit_held,
                'treat' => $request->treat,
                'guest' => $request->guest,
                'applicant' => $request->applicant,
                'position_organization' => $request->position_organization,
                'signature' => $signature_path,
                'reminder'  => $request->reminder,
            ]);
            foreach ($holders as $holder){
                MeetingUser::create([
                    'user_id' => $holder,
                    'meeting_id' => $meetings->id
                ]);
            }
        }
        return redirect()->route('dashboard')->with('status','جلسه جدبد ساخته و دعوتنامه به اعضا ارسال شد');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meetings = Meeting::find($id);
        $userIds = MeetingUser::where('meeting_id',$meetings->id)->get('user_id');
        $tasks = Task::where('meeting_id',$meetings->id)->get();
        return view('meeting.show',[
            'meetings' => $meetings ,
            'userIds' => $userIds ,
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meeting = Meeting::find($id);
        $users = User::query()
            ->join('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select('user_infos.id','user_infos.full_name')
            ->whereNull('deleted_at')
            ->get();

        return view('meeting.edit',['meeting' => $meeting , 'users' => $users]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingStoreRequest $request, string $id)
    {
        $request->validated();
        $meeting = Meeting::find($id);
        $holders =  Str::of($request->holders)->split('/[\s,]+/');

        $old_signature = public_path('storage/'. $meeting->signature);
        if (file_exists($old_signature)){
            unlink($old_signature);
        }

        $new_signature_path = $request->signature->store('signatures','public');

        $meeting->title = $request->title;
        $meeting->unit_organization = $request->unit_organization;
        $meeting->scriptorium = $request->scriptorium;
        $meeting->location = $request->location;
        $meeting->date = $request->date;
        $meeting->time = $request->time;
        $meeting->unit_held =  $request->unit_held;
        $meeting->treat = $request->treat;
        $meeting->guest = $request->guest;
        $meeting->applicant = $request->applicant;
        $meeting->position_organization = $request->position_organization;
        $meeting->signature = $new_signature_path;
        $meeting->reminder = $request->reminder;
        $meeting->save();


        foreach ($holders as $holder){
            if (MeetingUser::where('meeting_id',$meeting->id)->where('user_id',$holder)->exists()){
                MeetingUser::updateOrCreate(
                    ['user_id' => $holder],
                    ['meeting_id' => $meeting->id]
                );
            }else{
                MeetingUser::create([
                    'user_id' => $holder,
                    'meeting_id' => $meeting->id
                ]);
            }
        }
        return redirect()->signedRoute('meetings.index')->with('status',' جلسه با موفقیت بروز شد');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        dd($id);
    }
}
