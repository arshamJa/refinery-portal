<?php

namespace App\Http\Controllers\meeting;

use App\Http\Controllers\Controller;
use App\Http\Requests\meeting\MeetingStoreRequest;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateNewMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::with('meetingUsers')
//            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium','=',auth()->user()->user_info->full_name)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
            ->paginate(3);
        return view('meeting.crud.index' , [
            'meetings' => $meetings
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = UserInfo::whereHas('user', fn($query) => $query->where('role', 'employee')->orWhere('role','boss'))
            ->get(['id','user_id','full_name']);
        return view('meeting.crud.create' , ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(MeetingStoreRequest $request)
    {
        $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $jaYear = $jalaliNow[0];
        $jaMonth = $jalaliNow[1];
        $jaDay = $jalaliNow[2];

        // this is for not selecting the past
        if ($request->year < $jaYear ||
            ($request->year == $jaYear && $request->month < $jaMonth) ||
            ($request->year == $jaYear && $request->month == $jaMonth && $request->day < $jaDay)
        ) {
            throw ValidationException::withMessages(['year' => 'تاریخ گذشته نباید باشد']);
        }

        // two lines below will check if the month and day is one digit , which will add 0 before it .
        $new_month = sprintf("%02d", $request->month);
        $new_day = sprintf("%02d", $request->day);

        $newDate = $request->year . '/' . $new_month . '/' . $new_day;

        $holders = Str::of($request->holders)->split('/[\s,]+/');

        if (Meeting::where('date', $newDate)->where('time', $request->time)->exists()) {
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
                'date' => $newDate,
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
        return to_route('dashboard')->with('status','جلسه جدبد ساخته و دعوتنامه به اعضا جلسه ارسال شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meetings = Meeting::findOrFail($id);
        $userIds = MeetingUser::where('meeting_id',$meetings->id)->get('user_id');
        $tasks = Task::where('meeting_id',$meetings->id)->get();
        return view('meeting.crud.show',[
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
        return view('meeting.crud.edit',['meeting' => $meeting , 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(MeetingStoreRequest $request, string $id)
    {
        $request->validated();

        $jalaliNow = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        $jaYear = $jalaliNow[0];
        $jaMonth = $jalaliNow[1];
        $jaDay = $jalaliNow[2];

        // this is for not selecting the past
        if ($request->year < $jaYear ||
            ($request->year == $jaYear && $request->month < $jaMonth) ||
            ($request->year == $jaYear && $request->month == $jaMonth && $request->day < $jaDay)
        ) {
            throw ValidationException::withMessages(['year' => 'تاریخ گذشته نباید باشد']);
        }

        // two lines below will check if the month and day is one digit , which will add 0 before it .
        $new_month = sprintf("%02d", $request->month);
        $new_day = sprintf("%02d", $request->day);
        $newDate = $request->year . '/' . $new_month . '/' . $new_day;

        $meeting = Meeting::findOrFail($id);
        $holders =  Str::of($request->holders)->split('/[\s,]+/');

        if ($meeting->signature && file_exists(public_path('storage/' . $meeting->signature))) {
            unlink(public_path('storage/' . $meeting->signature));
        }

        $new_signature_path = $request->signature->store('signatures','public');

        $meeting->title = $request->title;
        $meeting->unit_organization = $request->unit_organization;
        $meeting->scriptorium = $request->scriptorium;
        $meeting->location = $request->location;
        $meeting->date = $newDate;
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
        return to_route('meeting.table')->with('status',' جلسه با موفقیت بروز شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meeting = Meeting::find($id)->delete();
        return to_route('meeting.table')->with('status',' جلسه با موفقیت حذف شد');

    }
}
