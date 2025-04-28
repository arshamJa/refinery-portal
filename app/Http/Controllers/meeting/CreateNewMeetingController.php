<?php

namespace App\Http\Controllers\meeting;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\meeting\MeetingStoreRequest;
use App\Http\Requests\MeetingUpdateRequest;
use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Null_;

class CreateNewMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Meeting::with('meetingUsers:id,meeting_id,user_id')
            ->where('scriptorium', auth()->user()->user_info->full_name)
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time']);

        // Get total count before filtering
        $originalMeetingsCount = (clone $query)->count();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('unit_organization', 'like', '%' . $search . '%')
                    ->orWhere('scriptorium', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%')
                    ->orWhere('time', 'like', '%' . $search . '%');
            });
        }
        $meetings = $query->paginate(5);
        $filteredMeetingsCount = $meetings->total(); // Count after filtering


        return view('meeting.crud.index' , [
            'meetings' => $meetings,
            'originalMeetingsCount' => $originalMeetingsCount,
            'filteredMeetingsCount' => $filteredMeetingsCount
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = UserInfo::with('department:id,department_name')
            ->whereHas('user', function ($query) {
            $query->whereDoesntHave('roles', function ($roleQuery) {
                $roleQuery->where('name', UserRole::SUPER_ADMIN->value);
            });
        })->select('id', 'user_id', 'full_name', 'department_id', 'position')
            ->get();

        $departments = Department::select('id','department_name')->get();
        return view('meeting.crud.create' , ['users' => $users,'departments'=>$departments]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(MeetingStoreRequest $request)
    {
        $request->validated();

        $innerGuests = collect($request->input('guests.inner', []))
            ->map(function ($guest) {
                return [
                    'name' => preg_replace('/\s+/', ' ', trim($guest['name'] ?? '')),
                    'department' => preg_replace('/\s+/', ' ', trim($guest['department'] ?? '')),
                ];
            })
            ->filter(function ($guest) {
                return $guest['name'] !== '' || $guest['department'] !== '';
            })
            ->values()
            ->all();

        $outerGuests = collect($request->input('guests.outer', []))
            ->map(function ($guest) {
                $name = preg_replace('/\s+/', ' ', trim($guest['name'] ?? ''));
                $companyName = preg_replace('/\s+/', ' ', trim($guest['companyName'] ?? ''));

                return [
                    'name' => $name !== '' ? $name : null,
                    'companyName' => $companyName !== '' ? $companyName : null,
                ];
            })
            ->filter(function ($guest) {
                return $guest['name'] !== null || $guest['companyName'] !== null;
            })
            ->values();


        // Convert current Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        // Prevent selecting past dates
        if ($request->year < $ja_year ||
            ($request->year == $ja_year && $request->month < $ja_month) ||
            ($request->year == $ja_year && $request->month == $ja_month && $request->day < $ja_day)
        ) {
            throw ValidationException::withMessages(['year' => 'تاریخ گذشته نباید باشد']);
        }

        // Format date correctly
        $newDate = sprintf('%04d/%02d/%02d', $request->year, $request->month, $request->day);

        $bossName = UserInfo::where('user_id',$request->boss)->value('full_name');


        // normalized time
        $time = $request->time;
        list($hour, $minute) = explode(':', $time);
        $normalizedTime = sprintf('%02d:%02d', $hour, $minute);


        if (Meeting::where('date', $newDate)->where('time', $request->time)->exists()) {
            throw ValidationException::withMessages([
                'time' => 'در این زمان جلسه ثبت شده است'
            ]);
        }else{
            $meeting = Meeting::create([
                'title' => $request->title,
                'unit_organization' => $request->unit_organization,
                'scriptorium' => $request->scriptorium,
                'boss' => $bossName,
                'location' => $request->location,
                'date' => $newDate,
                'time' => $normalizedTime,
                'unit_held' => $request->unit_held,
                'treat' => $request->treat,
                'guest' => $outerGuests ?? null,
                'applicant' => $request->applicant,
                'position_organization' => $request->position_organization,
            ]);

            $meetingUserRecords = [];

            // 1. Holders
            $holders = Str::of($request->holders)->explode(',');
            foreach ($holders as $holder) {
                $meetingUserRecords[] = [
                    'user_id' => trim($holder),
                    'meeting_id' => $meeting->id,
                    'is_guest' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 2. Inner Guests
            foreach ($innerGuests as $guest) {
                $userInfo = UserInfo::where('full_name', $guest['name'])->first();
                $meetingUserRecords[] = [
                    'user_id' => $userInfo?->user_id,
                    'meeting_id' => $meeting->id,
                    'is_guest' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // 3. Insert everything at once
            MeetingUser::insert($meetingUserRecords);

        }
        return to_route('dashboard.meeting')->with('status','جلسه جدبد ساخته و دعوتنامه به اعضا جلسه ارسال شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Eager load relationships
        $meeting = Meeting::with(['meetingUsers', 'tasks'])->findOrFail($id);

        // Fetch all users related to the meeting
        $userIds = $meeting->meetingUsers->pluck('user_id')->toArray();

        // Fetch user info in one query
        $userInfos = UserInfo::whereIn('user_id', $userIds)->pluck('full_name', 'user_id');

        // Optimize task lookup
        $tasks = $meeting->tasks->keyBy('user_id');

        return view('meeting.crud.show', [
            'meetings' => $meeting,
            'userInfos' => $userInfos,
            'tasks' => $tasks
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meeting = Meeting::with([
            'meetingUsers.user.user_info'
        ])->findOrFail($id);

        $date = $meeting->date;
        if ($date) {
            [$year, $month, $day] = explode('/', $date);
        } else {
            $year = $month = $day = null;
        }

//        $users = User::with('user_info:id,full_name,user_id')->whereNull('deleted_at')->get();
        $users = User::query()
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', [UserRole::SUPER_ADMIN->value,UserRole::ADMIN->value]);
            })
            ->join('user_infos', 'users.id', '=', 'user_infos.user_id')
            ->select('users.id as user_id', 'user_infos.full_name')
            ->whereNull('deleted_at')
            ->get();
        return view('meeting.crud.edit', [
            'meeting' => $meeting,
            'users' => $users,
            'userIds' => $meeting->meetingUsers,
            'year' => $year,
            'month' => $month,
            'day' => $day
        ]);
    }
    /**
     * Update the specified resource in storage.
     * @throws ValidationException
     */
    public function update(MeetingUpdateRequest $request, string $id)
    {
        $request->validated();

        // Convert current Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        // Prevent selecting past dates
        if ($request->year < $ja_year ||
            ($request->year == $ja_year && $request->month < $ja_month) ||
            ($request->year == $ja_year && $request->month == $ja_month && $request->day < $ja_day)
        ) {
            throw ValidationException::withMessages(['year' => 'تاریخ گذشته نباید باشد']);
        }

        // Format date correctly
        $newDate = sprintf('%04d/%02d/%02d', $request->year, $request->month, $request->day);

        // Retrieve meeting and eager-load users
        $meeting = Meeting::with('meetingUsers')->findOrFail($id);

        // Handle signature upload only if a new file is provided
        if ($request->hasFile('signature')) {
            if ($meeting->signature && file_exists(public_path('storage/' . $meeting->signature))) {
                unlink(public_path('storage/' . $meeting->signature));
            }
            $newSignaturePath = $request->file('signature')->store('signatures', 'public');
        } else {
            $newSignaturePath = $meeting->signature; // Keep existing signature
        }

        // Update meeting details in a single query
        $meeting->update([
            'title' => $request->title,
            'unit_organization' => $request->unit_organization,
            'scriptorium' => $request->scriptorium,
            'boss' => $request->boss,
            'location' => $request->location,
            'date' => $newDate,
            'time' => $request->time,
            'unit_held' => $request->unit_held,
            'treat' => $request->treat,
            'guest' => $request->guest,
            'applicant' => $request->applicant,
            'position_organization' => $request->position_organization,
            'signature' => $newSignaturePath,
            'reminder' => $request->reminder,
        ]);

        // Process meeting holders
        $holders = collect(explode(',', preg_replace('/\s+/', '', $request->holders)))
            ->filter(function ($value) {
                return is_numeric($value) && !empty($value);
            })
            ->map(fn($id) => (int)$id); // cast to int for safety

        // Get current user IDs in the meeting
        $existingUserIds = $meeting->meetingUsers->pluck('user_id')->toArray();

        // Determine which users need to be added and which need to be updated
        $newUserIds = $holders->diff($existingUserIds);
        $existingUserIdsToUpdate = $holders->intersect($existingUserIds);

        // Bulk update existing users
        MeetingUser::where('meeting_id', $meeting->id)
            ->whereIn('user_id', $existingUserIdsToUpdate)
            ->update([
                'is_present' => false,
                'reason_for_absent' => null,
                'read_by_scriptorium' => false,
                'read_by_user' => false,
                'replacement' => null
            ]);

        // Bulk insert new users
        $newMeetingUsers = $newUserIds->map(function ($userId) use ($meeting) {
            return [
                'meeting_id' => $meeting->id,
                'user_id' => $userId,
                'is_present' => false,
                'reason_for_absent' => null,
                'read_by_scriptorium' => false,
                'read_by_user' => false,
                'replacement' => null,
            ];
        })->toArray();

        if (!empty($newMeetingUsers)) {
            MeetingUser::insert($newMeetingUsers);
        }
        return to_route('meeting.table')->with('status',' جلسه با موفقیت بروز شد');
    }

    public function deleteUser(Request $request, $meetingId, $userId)
    {
        // Assuming $meetingId and $userId are passed as route parameters or request input
        MeetingUser::where('user_id', $userId)->where('meeting_id',$meetingId)->delete();
        return response()->json(['status' => 'این شخص از جلسه حذف شد']);
    }
    public function deleteGuest($meetingId, $index)
    {
        $meeting = Meeting::findOrFail($meetingId);
        $guests = $meeting->guest ?? [];

        if (!isset($guests[$index])) {
            return response()->json(['status' => 'مهمان یافت نشد'], 404);
        }

        unset($guests[$index]);
        $guests = array_values($guests); // Reindex

        $meeting->guest = $guests;
        $meeting->save();

        return response()->json(['status' => 'مهمان با موفقیت پاک شد']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meeting = Meeting::with('meetingUsers')->findOrFail($id);
        try {
            DB::transaction(function () use ($meeting) {
                // Soft delete related meeting users
                $meeting->meetingUsers()->delete();
                // Soft delete the meeting itself
                $meeting->delete();
            });
            return to_route('dashboard.meeting')->with('status',' جلسه با موفقیت حذف شد');
        } catch (\Exception $e) {
            return to_route('dashboard.meeting')->with('error', 'خطا در حذف جلسه: ' . $e->getMessage());
        }
    }

}
