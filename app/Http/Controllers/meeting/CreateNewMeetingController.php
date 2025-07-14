<?php

namespace App\Http\Controllers\meeting;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingStoreRequest;
use App\Http\Requests\MeetingUpdateRequest;
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
use Livewire\Attributes\Computed;

class CreateNewMeetingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unreadReceivedCount =  Notification::where('recipient_id', auth()->id())
            ->whereNull('recipient_read_at')
            ->count();

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
        $participants = $users->filter(function ($userInfo) {
            return $userInfo->user_id !== auth()->id();
        })->values();
        return view('meeting.create' , [
            'users' => $users,
            'participants'=>$participants,
            'unreadReceivedCount' =>$unreadReceivedCount
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(MeetingStoreRequest $request)
    {
        $validated = $request->validated();
        return DB::transaction(function () use ($validated, $request) {
            $innerGuestIds = collect(explode(',', $request->input('innerGuest', '')))
                ->filter()->unique()->values();

            $innerGuests = UserInfo::whereIn('user_id', $innerGuestIds)->get();

            $outerGuests = collect($request->input('guests.outer', []))
                ->map(function ($guest) {
                    $name = preg_replace('/\s+/', ' ', trim($guest['name'] ?? ''));
                    $companyName = preg_replace('/\s+/', ' ', trim($guest['companyName'] ?? ''));
                    return [
                        'name' => $name !== '' ? $name : null,
                        'companyName' => $companyName !== '' ? $companyName : null,
                    ];
                })
                ->filter(fn($guest) => $guest['name'] !== null || $guest['companyName'] !== null)
                ->values();

            // Convert current Gregorian date to Jalali
            $newDate = $this->getCurrentDate($validated['year'], $validated['month'], $validated['day']);

            // Get boss and scriptorium together
            $userIdsToFetch = array_filter([$validated['boss'], $validated['scriptorium']]);
            $userInfos = UserInfo::whereIn('user_id', $userIdsToFetch)->get()->keyBy('user_id');

            $boss = $userInfos->get($validated['boss']);
            $bossName = $boss ? $boss->full_name : 'Unknown';

            $scriptorium = $userInfos->firstWhere('position', $validated['scriptorium_position']);
            $scriptoriumName = $scriptorium ? $scriptorium->full_name : 'Unknown';

            $validated['time'] = str_contains($validated['time'], ':')
                ? $validated['time']
                : sprintf('%02d:00', intval($validated['time']));

            if (Meeting::where('date', $newDate)->where('time', $validated['time'])->exists()) {
                throw ValidationException::withMessages([
                    'time' => 'در این زمان جلسه ثبت شده است',
                ]);
            }

            $meeting = Meeting::create([
                'title' => $validated['title'],
                'scriptorium' => $scriptoriumName,
                'scriptorium_department' => $validated['scriptorium_department'],
                'scriptorium_position' => $validated['scriptorium_position'],
                'boss' => $bossName,
                'location' => $validated['location'],
                'date' => $newDate,
                'time' => $validated['time'],
                'unit_held' => $validated['unit_held'],
                'treat' => $validated['treat'],
                'guest' => $outerGuests->isNotEmpty() ? $outerGuests : null,
            ]);

            $meetingUserRecords = [];
            $recipients = collect();

            // Holders
            $holders = Str::of($validated['holders'])->explode(',');
            foreach ($holders as $holder) {
                $holderId = trim($holder);
                $meetingUserRecords[] = [
                    'user_id' => $holderId,
                    'meeting_id' => $meeting->id,
                    'is_guest' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $recipients->push($holderId);
            }

            // Inner guests
            foreach ($innerGuests as $guest) {
                if ($guest->user_id) {
                    $meetingUserRecords[] = [
                        'user_id' => $guest->user_id,
                        'meeting_id' => $meeting->id,
                        'is_guest' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $recipients->push((string)$guest->user_id);
                }
            }

            // Prepare notifications
            $notifications = [];

            // Boss
            if ($boss) {
                $meetingUserRecords[] = [
                    'user_id' => $boss->user_id,
                    'meeting_id' => $meeting->id,
                    'is_guest' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $recipients->push((string)$boss->user_id);

                $notifications[] = [
                    'type' => 'MeetingInvitation',
                    'data' => json_encode([
                        'message' => 'شما به عنوان رئیس برای جلسه "' . $meeting->title .
                            '" در تاریخ ' . $newDate . ' و ساعت ' . $validated['time'] . ' دعوت شده‌اید.',
                    ]),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $boss->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert all meeting users at once
            MeetingUser::insert($meetingUserRecords);

            // Notifications for holders and guests except boss (already notified)
            foreach ($recipients->unique() as $recipientId) {
                if ($boss && (string)$boss->user_id === (string)$recipientId) {
                    continue;
                }

                $isGuest = $innerGuestIds->contains($recipientId);
                $notifications[] = [
                    'type' => $isGuest ? 'MeetingGuestInvitation' : 'MeetingInvitation',
                    'data' => json_encode([
                        'message' => $isGuest
                            ? 'شما به عنوان مهمان در جلسه: ' . $meeting->title . ' در تاریخ ' . $newDate . ' و در ساعت ' . $validated['time'] . ' دعوت شده‌اید'
                            : 'شما در جلسه: ' . $meeting->title . ' در تاریخ ' . $newDate . ' و در ساعت ' . $validated['time'] . ' دعوت شده‌اید',
                    ]),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $recipientId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Notification::insert($notifications);

            return to_route('dashboard.meeting')->with('status', __('جلسه جدید ساخته و دعوتنامه به اعضا جلسه ارسال شد'));
        });
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $meeting = Meeting::with([
            'meetingUsers.user.user_info.department',
        ])
            ->select('id','title', 'scriptorium_department',
                'scriptorium', 'boss', 'location', 'date', 'time',
                'unit_held', 'treat', 'guest', 'scriptorium_position')
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
            $bossInfo = UserInfo::with(['department:id,department_name'])
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
            ->select('id', 'user_id', 'full_name', 'department_id', 'position')
            ->get();


        $participants = $users->filter(function ($userInfo) {
            return $userInfo->user_id !== auth()->id();
        })->values();

        return view('meeting.edit', [
            'meeting' => $meeting,
            'users' => $users,
            'userIds' => $meeting->meetingUsers->where('is_guest',false),
            'innerGuests' => $innerGuests,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'bossInfo' => $bossInfo,
            'participants' => $participants
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(MeetingUpdateRequest $request, string $id)
    {

        $validated = $request->validated();

        $validated['time'] = str_contains($validated['time'], ':')
            ? $validated['time']
            : sprintf('%02d:00', intval($validated['time']));

        $newDate = $this->getCurrentDate($validated['year'], $validated['month'], $validated['day']);

        // Retrieve meeting with related users
        $meeting = Meeting::with('meetingUsers')->findOrFail($id);

        // Store original boss user ID for notification comparison
        $originalBossUserId = UserInfo::where('full_name', $meeting->boss)->value('user_id');

        // Get Boss Name from UserInfo if boss id provided, else keep current
        $bossName = $meeting->boss;
        if (!empty($validated['boss'])) {
            $bossName = UserInfo::where('user_id', $validated['boss'])->value('full_name') ?? $meeting->boss;
        }

        // Initialize with existing meeting values
        $scriptoriumName = $meeting->scriptorium;
        $scriptoriumDepartment = $meeting->scriptorium_department;
        $scriptoriumPosition = $meeting->scriptorium_position;

        // Override if valid scriptorium user ID and position provided
        if (!empty($validated['scriptorium'])) {
            $scriptorium = UserInfo::where('user_id', $validated['scriptorium'])
                ->where('position', $validated['scriptorium_position'])
                ->first();

            if ($scriptorium) {
                $scriptoriumName = $scriptorium->full_name;

                // Fetch department name using department_id from Department model
                $department = Department::find($scriptorium->department_id);
                $scriptoriumDepartment = $department ? $department->department_name : null;

                $scriptoriumPosition = $scriptorium->position;
            }
        }

        // Process inner guests: parse from comma-separated IDs like in store()
        $innerGuestIds = collect(explode(',', $request->input('innerGuest', '')))
            ->filter()->unique()->values();

        $innerGuests = UserInfo::whereIn('user_id', $innerGuestIds)->get();

        // Process outer guests like store()
        $newOuterGuests = collect($request->input('guests.outer', []))
            ->map(function ($guest) {
                $name = preg_replace('/\s+/', ' ', trim($guest['name'] ?? ''));
                $companyName = preg_replace('/\s+/', ' ', trim($guest['companyName'] ?? ''));
                return [
                    'name' => $name !== '' ? $name : null,
                    'companyName' => $companyName !== '' ? $companyName : null,
                ];
            })
            ->filter(fn($guest) => $guest['name'] !== null || $guest['companyName'] !== null)
            ->values();

        // Merge with existing outer guests without duplicates based on name + companyName
        $existingOuterGuests = collect($meeting->guest ?? []);
        $mergedOuterGuests = $existingOuterGuests->merge($newOuterGuests)
            ->unique(fn($guest) => $guest['name'].'-'.$guest['companyName'])
            ->values()
            ->toArray();

        // Update meeting details (including merged guests)
        $meeting->update([
            'title' => $validated['title'],
            'scriptorium' => $scriptoriumName,
            'scriptorium_department' => $scriptoriumDepartment,
            'scriptorium_position' => $scriptoriumPosition,
            'boss' => $bossName,
            'location' => $validated['location'],
            'date' => $newDate,
            'time' => $validated['time'],
            'unit_held' => $validated['unit_held'],
            'treat' => $validated['treat'],
            'guest' => $mergedOuterGuests,
        ]);


        // Add new inner guests that don't exist yet
        $existingGuestUserIds = $meeting->meetingUsers()
            ->where('is_guest', true)
            ->pluck('user_id');

        $newGuestUserIds = $innerGuestIds->diff($existingGuestUserIds);

        foreach ($newGuestUserIds as $userId) {
            $meeting->meetingUsers()->create([
                'user_id' => $userId,
                'is_guest' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Manage Holders (Keep old, add new)
        $holders = collect(explode(',', preg_replace('/\s+/', '', $request->holders ?? '')))
            ->filter(fn($id) => is_numeric($id))
            ->unique()
            ->map(fn($id) => (int) $id);

        $existingHolders = $meeting->meetingUsers()
            ->where('is_guest', false)
            ->pluck('user_id');

        $newHolders = $holders->diff($existingHolders);
        $newHolders->each(fn($id) => $meeting->meetingUsers()->create([
            'user_id' => $id,
            'is_guest' => false,
            'is_present' => false,
            'read_by_user' => false,
            'read_by_scriptorium' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        // Manage Inner Guests & Participants for notifications
        $participants = $holders;
        $allUserIds = $innerGuestIds
            ->merge($participants)
            ->push((int) $validated['boss'])
            ->unique()
            ->filter(fn($id) => is_numeric($id));

        // If boss changed, delete old boss notification
        if ($originalBossUserId && $originalBossUserId != (int) $validated['boss']) {
            Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meeting->id)
                ->where('recipient_id', $originalBossUserId)
                ->delete();
        }

        $notificationsData = [];

        foreach ($allUserIds as $userId) {
            $isGuest = $innerGuestIds->contains($userId);
            $notificationType = $isGuest ? 'MeetingGuestInvitation' : 'MeetingInvitation';
            $notificationMessage = 'شما ' . ($isGuest ? 'به عنوان مهمان ' : '') .
                'در جلسه: ' . $meeting->title .
                ' در تاریخ ' . $newDate .
                ' و در ساعت ' . $validated['time'] . ' دعوت شده اید';

            $notificationsData[] = [
                'type' => $notificationType,
                'data' => json_encode(['message' => $notificationMessage]),
                'notifiable_type' => Meeting::class,
                'notifiable_id' => $meeting->id,
                'sender_id' => auth()->id(),
                'recipient_id' => (int) $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Notification::upsert(
            $notificationsData,
            ['notifiable_type', 'notifiable_id', 'recipient_id'], // unique keys
            ['data', 'sender_id', 'updated_at'] // fields to update if exists
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

            // Delete related notifications
            Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meeting->id)
                ->delete();

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
            throw ValidationException::withMessages(['year' => 'تاریخ نباید گذشته باشد']);
        }

        // Return the formatted date
        return sprintf('%04d/%02d/%02d', $year, $month, $day);
    }
}
