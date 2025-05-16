<?php

namespace App\Http\Controllers\meeting;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\meeting\MeetingStoreRequest;
use App\Http\Requests\MeetingUpdateRequest;
use App\Jobs\SendNotificationJob;
use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Notification;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
//        $users = UserInfo::with('department:id,department_name')
//            ->whereHas('user', function ($query) {
//            $query->whereDoesntHave('roles', function ($roleQuery) {
//                $roleQuery->where('name', UserRole::SUPER_ADMIN->value);
//            });
//        })->select('id', 'user_id', 'full_name', 'department_id', 'position')
//            ->get();
//
//        $departments = Department::select('id','department_name')->get();

        $users = Cache::remember('users_without_super_admin', 3600, function () {
            return UserInfo::with('department:id,department_name')
                ->whereHas('user', function ($query) {
                    $query->whereDoesntHave('roles', function ($roleQuery) {
                        $roleQuery->where('name', UserRole::SUPER_ADMIN->value);
                    });
                })
                ->select('id', 'user_id', 'full_name', 'department_id', 'position')
                ->get();
        });
        $departments = Cache::remember('departments_list', 3600, function () {
            return Department::select('id', 'department_name')->get();
        });

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
        $newDate = $this->getCurrentDate($request->year,$request->month,$request->day);

        $boss = UserInfo::where('user_id', $request->boss)->first();
        $bossName = $boss ? $boss->full_name : 'Unknown';

        $request->merge([
            'time' => str_contains($request->time, ':') ? $request->time : sprintf('%02d:00', intval($request->time))
        ]);

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
                'time' => $request->time,
                'unit_held' => $request->unit_held,
                'treat' => $request->treat,
                'guest' => $outerGuests ?? null,
                'applicant' => $request->applicant,
                'position_organization' => $request->position_organization,
            ]);

            $meetingUserRecords = [];

            // Collect recipients (holders + inner guests)
            $recipients = collect();

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
                $recipients->push(trim($holder));
            }

            // 2. Inner Guests
            foreach ($innerGuests as $guest) {
                $userInfo = UserInfo::where('full_name', $guest['name'])->first();
                if ($userInfo) {
                    $recipients->push((string) $userInfo->user_id);
                }
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

            if ($boss) {
                $recipients->push((string) $boss->user_id);
            }

            // Create Notifications
            foreach ($recipients as $recipientId) {
                $notificationMessage = 'شما در جلسه: ' . $meeting->title .
                    ' در تاریخ ' . $newDate . ' و در ساعت ' . $request->time . 'دعوت شده اید';

                Notification::create([
                    'type' => 'Meeting Invitation',
                    'data' => json_encode(['message' => $notificationMessage]),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $recipientId,
                ]);

                // use this for jobs: php artisan queue:work --queue=notifications and below
//                dispatch(new SendNotificationJob([
//                    'type' => 'Meeting Invitation',
//                    'data' => json_encode(['message' => $notificationMessage]),
//                    'notifiable_type' => \App\Models\Meeting::class,
//                    'notifiable_id' => $meeting->id,
//                    'sender_id' => auth()->id(),
//                    'recipient_id' => $recipientId,
//                ]))->onQueue('notifications');
            }


        }
        return to_route('dashboard.meeting')->with('status', __('جلسه جدبد ساخته و دعوتنامه به اعضا جلسه ارسال شد'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meeting = Meeting::with([
            'meetingUsers.user.user_info.department',
        ])
            ->select('id','title',
                'unit_organization', 'scriptorium', 'boss',
                'location', 'date', 'time', 'unit_held', 'treat', 'guest',
                'applicant', 'position_organization')
            ->findOrFail($id);

        // Date processing
        $date = $meeting->date;
        if ($date) {
            [$year, $month, $day] = explode('/', $date);
        } else {
            $year = $month = $day = null;
        }

        // Fetching the boss with department and position
        $bossInfo = null;
        if ($meeting->boss) {
            $bossInfo = UserInfo::with(['department'])
                ->where('full_name', $meeting->boss)
                ->first();
        }

        // Map inner guests with department names
        $innerGuests = $meeting->meetingUsers->filter(function ($meetingUser) {
            return $meetingUser->is_guest;  // Only filter inner guests
        })->map(function ($meetingUser) {
            if ($meetingUser->user && $meetingUser->user->user_info) {
                $meetingUser->department_name = $meetingUser->user->user_info->department->department_name ?? 'Unknown Department';
            }
            return $meetingUser;
        });


        // Get users (non-guests) excluding current user and super admins
        $users = UserInfo::with('department:id,department_name')
            ->whereHas('user', function ($query) {
                $query->whereDoesntHave('roles', function ($roleQuery) {
                    $roleQuery->where('name', UserRole::SUPER_ADMIN->value);
                });
            })
            ->where('user_id', '!=', auth()->id())
            ->select('id', 'user_id', 'full_name', 'department_id', 'position')
            ->get();

        return view('meeting.crud.edit', [
            'meeting' => $meeting,
            'users' => $users,
            'userIds' => $meeting->meetingUsers->where('is_guest',false),
            'innerGuests' => $innerGuests,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'bossInfo' => $bossInfo,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(MeetingUpdateRequest $request, string $id)
    {
        // Validate request
        $request->validated();

        // Convert current Gregorian date to Jalali
        $newDate = $this->getCurrentDate($request->year, $request->month, $request->day);

        // Retrieve meeting
        $meeting = Meeting::with('meetingUsers')->findOrFail($id);

        // Get Boss Name from UserInfo
        $bossName = UserInfo::where('user_id', $request->boss)->value('full_name');

        // Merge Old and New Outer Guests
        $existingOuterGuests = collect($meeting->guest ?? []);
        $newOuterGuests = collect($request->input('guests.outer', []))
            ->map(fn($g) => [
                'name' => trim($g['name']),
                'companyName' => trim($g['companyName'])
            ])
            ->filter(fn($g) => $g['name'] !== '' || $g['companyName'] !== '')->values();

        $mergedOuterGuests = $existingOuterGuests->merge($newOuterGuests)->unique('name')->values()->toArray();

        // Update meeting details
        $meeting->update([
            'title' => $request->title,
            'unit_organization' => $request->unit_organization,
            'scriptorium' => $request->scriptorium,
            'boss' => $bossName,
            'location' => $request->location,
            'date' => $newDate,
            'time' => $request->time,
            'unit_held' => $request->unit_held,
            'treat' => $request->treat,
            'guest' => $mergedOuterGuests,
            'applicant' => $request->applicant,
            'position_organization' => $request->position_organization,
        ]);

        // Manage Inner Guests (Keep old, add new)
        $innerGuests = collect($request->input('guests.inner', []))->pluck('name')->unique();
        $guestUserIds = UserInfo::whereIn('full_name', $innerGuests)->pluck('user_id');

        $existingGuests = $meeting->meetingUsers()->where('is_guest', true)->pluck('user_id');
        $newGuests = $guestUserIds->diff($existingGuests);

        $newGuests->each(fn($id) => $meeting->meetingUsers()->create(['user_id' => $id, 'is_guest' => true]));

        // Manage Holders (Keep old, add new)
        $holders = collect(explode(',', preg_replace('/\s+/', '', $request->holders ?? '')))
            ->filter(fn($id) => is_numeric($id))->unique()->map(fn($id) => (int) $id);

        $existingHolders = $meeting->meetingUsers()->pluck('user_id');
        $newHolders = $holders->diff($existingHolders);

        $newHolders->each(fn($id) => $meeting->meetingUsers()->create([
            'user_id' => $id,
            'is_present' => false,
            'read_by_user' => false,
            'read_by_scriptorium' => false
        ]));
        // List of fields to watch for changes
        $watchedFields = [
            'title', 'unit_organization', 'scriptorium', 'boss',
            'location', 'date', 'time', 'unit_held',
            'treat', 'guest', 'applicant', 'position_organization'
        ];

        // Check if any of these fields have changed
        $originalMeeting = $meeting->getOriginal();
        $hasChanged = collect($watchedFields)->contains(function ($field) use ($originalMeeting, $request) {
            return $originalMeeting[$field] != $request->$field;
        });

        // Manage Inner Guests (Keep old, add new)
        $innerGuests = collect($request->input('guests.inner', []))->pluck('name')->unique();
        $guestUserIds = UserInfo::whereIn('full_name', $innerGuests)->pluck('user_id');

        $existingGuests = $meeting->meetingUsers()->where('is_guest', true)->pluck('user_id');
        $newGuests = $guestUserIds->diff($existingGuests);

        // Manage Participants (Keep old, add new)
        $participants = collect(explode(',', preg_replace('/\s+/', '', $request->holders ?? '')))
            ->filter(fn($id) => is_numeric($id))->unique()->map(fn($id) => (int) $id);

        $existingParticipants = $meeting->meetingUsers()->where('is_guest', false)->pluck('user_id');
        $newParticipants = $participants->diff($existingParticipants);

        // Define the notification message
        $notificationMessage = 'شما در جلسه: ' . $meeting->title .
            ' در تاریخ ' . $newDate . ' و در ساعت ' . $request->time . ' دعوت شده اید';

        // Collect all current participants and guests
        $allUserIds = $guestUserIds->merge($participants)->unique();
        $notificationsData = [];

        // Prepare data for upsert
        foreach ($allUserIds as $userId) {
            $notificationsData[] = [
                'type' => 'Meeting Invitation',
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Upsert (Insert or Update) notifications in a single query
        Notification::upsert(
            $notificationsData,
            ['notifiable_type', 'notifiable_id', 'recipient_id'], // Unique constraints to match
            ['data', 'sender_id', 'updated_at'] // Fields to update if existing
        );
        return to_route('dashboard.meeting')->with('status', __('جلسه با موفقیت بروز شد'));
    }

    public function deleteUser(Request $request, $meetingId, $userId)
    {

        // Delete the meeting user record
        MeetingUser::where('user_id', $userId)
            ->where('meeting_id', $meetingId)
            ->delete();

        // Delete the notification(s) related to this user and meeting
        Notification::where('notifiable_type', Meeting::class)
            ->where('notifiable_id', $meetingId)
            ->where('recipient_id', $userId)
            ->delete();

        return response()->json(['status' => 'این شخص از جلسه حذف شد']);
    }

    public function deleteGuest(Request $request, $guestId)
    {
        // Get meeting_id and guest_id from the request
        $meetingId = $request->meeting_id;
        $guestId = $request->guest_id;

        // Check if the guest is part of the meeting and is marked as a guest
        $meetingUser = MeetingUser::where('meeting_id', $meetingId)
            ->where('user_id', $guestId)
            ->where('is_guest', true)
            ->first();

        // If the guest is found, delete
        if ($meetingUser) {
            $meetingUser->delete();  // Delete the guest from the meeting_user table


            // Delete related notifications for this guest and meeting
            Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meetingId)
                ->where('recipient_id', $guestId)
                ->delete();


            // Respond with a success message
            return response()->json(['status' => 'مهمان از جلسه حذف شد.']);
        }

        // If the guest is not found or not part of the meeting as a guest
        return response()->json(['status' => 'مهمان پیدا نشد یا عضویت او به عنوان مهمان تایید نشده است.'], 404);
    }
    public function deleteOuterGuest($meetingId, $guestIndex)
    {
        $meeting = Meeting::findOrFail($meetingId);

        if (is_array($meeting->guest) && isset($meeting->guest[$guestIndex])) {
            // Remove the guest at the given index
            $guests = $meeting->guest;
            unset($guests[$guestIndex]);
            $meeting->guest = array_values($guests); // Re-index the array
            $meeting->save();
            return response()->json(['status' => 'مهمان با موفقیت حذف شد.']);
        }

        return response()->json(['status' => 'مهمان یافت نشد.'], 404);
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

    /**
     * Validate and format a Jalali date.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return string
     * @throws ValidationException
     */
    public function getCurrentDate(int $year, int $month, int $day): string
    {
        // Convert current Gregorian date to Jalali
        list($ja_year, $ja_month, $ja_day) = explode('/',
            gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));

        // Prevent selecting past dates
        if ($year < $ja_year ||
            ($year == $ja_year && $month < $ja_month) ||
            ($year == $ja_year && $month == $ja_month && $day < $ja_day)
        ) {
            throw ValidationException::withMessages(['year' => 'The selected date cannot be in the past.']);
        }

        // Return the formatted date
        return sprintf('%04d/%02d/%02d', $year, $month, $day);
    }
}
