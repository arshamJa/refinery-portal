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
use Illuminate\Support\Facades\Log;
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
        $unreadReceivedCount = Notification::where('recipient_id', auth()->id())
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

        // Map to include department_name directly for frontend JSON
        $usersForJson = $users->map(function ($user) {
            return [
                'user_id' => $user->user_id,
                'full_name' => $user->full_name,
                'position' => $user->position,
                'department_id' => $user->department_id,
                'department_name' => $user->department ? $user->department->department_name : '',
            ];
        });

        $participants = $usersForJson->filter(function ($user) {
            return $user['user_id'] !== auth()->id();
        })->values();

        return view('meeting.create', [
            'users' => $usersForJson,
            'participants' => $participants,
            'unreadReceivedCount' => $unreadReceivedCount,
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
            // Parse comma-separated inner guest IDs
            $innerGuestIds = collect(explode(',', $request->input('innerGuest', '')))
                ->map(fn($id) => (int) trim($id))
                ->filter()
                ->unique()
                ->values();

            $innerGuests = UserInfo::whereIn('user_id', $innerGuestIds)->get();

            // Clean up outer guests
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
            $getCurrentDate = $this->getCurrentDate( $validated['year'], $validated['month'], $validated['day']);
            $newDate = sprintf('%04d/%02d/%02d', $validated['year'], $validated['month'], $validated['day']);

            // Load boss and scriptorium user info
//            $userIdsToFetch = array_filter([$validated['boss'], $validated['scriptorium']]);
            $userIdsToFetch = array_filter([$validated['boss']]);
            $userInfos = UserInfo::whereIn('user_id', $userIdsToFetch)->get()->keyBy('user_id');

            $boss = $userInfos->get($validated['boss']);
//            $scriptorium = $userInfos->get($validated['scriptorium']);

            // Normalize time format (e.g., "8" to "08:00")
            $validated['time'] = str_contains($validated['time'], ':')
                ? $validated['time']
                : sprintf('%02d:00', intval($validated['time']));

            $oldDate = sprintf('%04d/%02d/%02d', $validated['year'], $validated['month'], $validated['day']);
            // Check for time conflict
            if (Meeting::where('date', $oldDate)->where('time', $validated['time'])->exists()) {
                throw ValidationException::withMessages([
                    'time' => 'در این زمان جلسه ثبت شده است',
                ]);
            }

            // Create meeting
            $meeting = Meeting::create([
                'title' => $validated['title'],
//                'scriptorium_id' => $validated['scriptorium'],
                'scriptorium_id' => auth()->id(),
                'boss_id' => $validated['boss'],
                'location' => $validated['location'],
                'date' => $newDate,
                'time' => $validated['time'],
                'unit_held' => $validated['unit_held'],
                'treat' => (bool) $validated['treat'],
                'guest' => $outerGuests->isNotEmpty() ? $outerGuests->toArray() : null,
            ]);

            $meetingUserRecords = [];
            $recipients = collect();

            // Add holders
            $holders = collect(explode(',', $validated['holders'] ?? ''))
                ->map(fn($id) => (int) trim($id))
                ->filter()
                ->unique();

            foreach ($holders as $holderId) {
                $meetingUserRecords[] = [
                    'user_id' => $holderId,
                    'meeting_id' => $meeting->id,
                    'is_guest' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $recipients->push($holderId);
            }

            // Add inner guests
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

            // Add boss
            if ($boss) {
                $meetingUserRecords[] = [
                    'user_id' => $boss->user_id,
                    'meeting_id' => $meeting->id,
                    'is_guest' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $recipients->push((string)$boss->user_id);
            }

            MeetingUser::insert($meetingUserRecords);

            // Create notifications with different types
            $notifications = [];

            foreach ($recipients->unique() as $recipientId) {
                $recipientIdInt = (int)$recipientId;

                if ($boss && $recipientIdInt === (int)$boss->user_id) {
                    $type = 'MeetingBossInvitation';
                    $message = 'شما به عنوان رئیس در جلسه: "' . $meeting->title .
                        '" در تاریخ ' . $newDate . ' و ساعت ' . $validated['time'] . ' دعوت شده‌اید.';
                } elseif ($innerGuestIds->contains($recipientIdInt)) {
                    $type = 'MeetingGuestInvitation';
                    $message = 'شما به عنوان مهمان در جلسه: ' . $meeting->title .
                        ' در تاریخ ' . $newDate . ' و در ساعت ' . $validated['time'] . ' دعوت شده‌اید.';
                } else {
                    $type = 'MeetingInvitation';
                    $message = 'شما در جلسه: ' . $meeting->title .
                        ' در تاریخ ' . $newDate . ' و در ساعت ' . $validated['time'] . ' دعوت شده‌اید.';
                }

                $notifications[] = [
                    'type' => $type,
                    'data' => json_encode(['message' => $message]),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $recipientIdInt,
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
        // Load meeting with meetingUsers and nested relations
        $meeting = Meeting::with([
            'meetingUsers.user.user_info.department',
            'boss.user_info.department',
            'scriptorium.user_info.department',
        ])
            ->select('id','title', 'scriptorium_id', 'boss_id',
                'location', 'date', 'time', 'unit_held', 'treat', 'guest')
            ->findOrFail($id);

        // Date processing
        $date = $meeting->date;
        if ($date) {
            [$year, $month, $day] = explode('/', $date);
        } else {
            $year = $month = $day = null;
        }

        // Map inner guests with department names
        $innerGuests = $meeting->meetingUsers->filter(fn($mu) => $mu->is_guest)->map(function ($meetingUser) {
            if ($meetingUser->user && $meetingUser->user->user_info) {
                $meetingUser->department_name = $meetingUser->user->user_info->department->department_name ?? 'Unknown Department';
            }
            return $meetingUser;
        });

        // Get users (non-guests), excluding current user and super admins
        $users = UserInfo::with('department:id,department_name')
            ->whereHas('user', function ($query) {
                $query->whereDoesntHave('roles', function ($roleQuery) {
                    $roleQuery->where('name', UserRole::SUPER_ADMIN->value);
                });
            })
            ->select('id', 'user_id', 'full_name', 'department_id', 'position')
            ->get();

        $users = $users->map(function ($user) {
            $user->department_name = $user->department->department_name ?? 'نامشخص';
            return $user;
        });

        // Filter participants excluding current logged-in user
        $participants = $users->filter(fn($userInfo) => $userInfo->user_id !== auth()->id())->values();

        // Pass meetingUsers who are not guests
        $userIds = $meeting->meetingUsers->where('is_guest', false);

        return view('meeting.edit', [
            'meeting' => $meeting,
            'users' => $users,
            'userIds' => $userIds,
            'innerGuests' => $innerGuests,
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'participants' => $participants,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function update(MeetingUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        $validated['time'] = str_contains($validated['time'], ':')
            ? $validated['time'] : sprintf('%02d:00', intval($validated['time']));

        $getCurrentDate = $this->getCurrentDate( $validated['year'], $validated['month'], $validated['day']);

        $newDate = sprintf('%04d/%02d/%02d', $validated['year'], $validated['month'], $validated['day']);
        $oldDate = sprintf('%04d/%02d/%02d', $validated['year'], $validated['month'], $validated['day']);
        // Check for time conflict
        if (Meeting::where('date', $oldDate)->where('time', $validated['time'])->exists()) {
            throw ValidationException::withMessages([
                'time' => 'در این زمان جلسه ثبت شده است',
            ]);
        }
        $meeting = Meeting::with('meetingUsers')->findOrFail($id);

        $newBossId = $validated['boss'];
        $originalBossId =  $meeting->boss_id;

        // Guests (excluding boss)
        $innerGuestIds = collect(explode(',', $request->input('innerGuest', '')))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        // Outer Guests
        $newOuterGuests = collect($request->input('guests.outer', []))
            ->map(fn($guest) => [
                'name' => trim(preg_replace('/\s+/', ' ', $guest['name'] ?? '')) ?: null,
                'companyName' => trim(preg_replace('/\s+/', ' ', $guest['companyName'] ?? '')) ?: null,
            ])
            ->filter(fn($guest) => $guest['name'] || $guest['companyName'])
            ->values();

        $existingOuterGuests = collect($meeting->guest ?? []);
        $mergedOuterGuests = $existingOuterGuests
            ->merge($newOuterGuests)
            ->unique(fn($guest) => $guest['name'] . '-' . $guest['companyName'])
            ->values()
            ->toArray();

        // Update meeting info
        $meeting->update([
            'title' => $validated['title'],
            'scriptorium_id' => auth()->id(),
            'boss_id' => $newBossId,
            'location' => $validated['location'],
            'date' => $newDate,
            'time' => $validated['time'],
            'unit_held' => $validated['unit_held'],
            'treat' => $validated['treat'],
            'guest' => $mergedOuterGuests,
        ]);


        // Holders - exclude boss user_id here
        $holders = collect(explode(',', preg_replace('/\s+/', '', $request->holders ?? '')))
            ->filter(fn($id) => is_numeric($id))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->reject(fn($id) => $id === $newBossId); // Exclude boss from holders

        $existingHolders = $meeting->meetingUsers()->where('is_guest', false)
            ->pluck('user_id');

        $newHolders = $holders->diff($existingHolders);

        foreach ($newHolders as $id) {
            $meeting->meetingUsers()->create([
                'user_id' => $id,
                'is_guest' => false,
                'is_present' => false,
                'read_by_user' => false,
                'read_by_scriptorium' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Boss user update - update old boss invitation notification if changed
        if ($originalBossId !== $newBossId) {
            $bossMessage = 'شما به عنوان رئیس در جلسه: ' . $meeting->title . ' در تاریخ ' . $newDate . ' و در ساعت ' . $validated['time'] . ' دعوت شده‌اید.';

            Notification::where('notifiable_type', Meeting::class)
                ->where('notifiable_id', $meeting->id)
                ->where('recipient_id', $originalBossId)
                ->where('type', 'MeetingBossInvitation')
                ->update([
                    'recipient_id' => $newBossId,
                    'data' => json_encode(['message' => $bossMessage]),
                    'sender_id' => auth()->id(),
                    'recipient_read_at' => null,
                    'updated_at' => now(),
                ]);
            $meeting->meetingUsers()->where('user_id',$originalBossId)
                ->update(['user_id' => $newBossId]);
        }



        // Inner Guests - add new guest meeting users
        $existingGuestUserIds = $meeting->meetingUsers()->where('is_guest', true)
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

        // Reset all present flags
        $meeting->meetingUsers()->update(['is_present' => false]);

        // Prepare participant list for notifications (holders + guests)
        $participants = $holders->merge($innerGuestIds);
        $allRelevantUserIds = $participants->unique();

        // Remove accept/deny notifications
        Notification::where('notifiable_type', Meeting::class)
            ->where('notifiable_id', $meeting->id)
            ->whereIn('type', ['AcceptInvitation', 'DenyInvitation'])
            ->forceDelete();

        $newlyAddedUserIds = collect()->merge($newGuestUserIds)->merge($newHolders)->unique();
        $notificationsData = [];

        foreach ($allRelevantUserIds as $userId) {
            $isGuest = $innerGuestIds->contains($userId);
            $type = $isGuest ? 'MeetingGuestInvitation' : 'MeetingInvitation';

            $message = 'شما ' . ($isGuest ? 'به عنوان مهمان ' : '') .
                'در جلسه: ' . $meeting->title .
                ' در تاریخ ' . $newDate .
                ' و در ساعت ' . $validated['time'] . ' دعوت شده‌اید.';

            if ($newlyAddedUserIds->contains($userId)) {
                $notificationsData[] = [
                    'type' => $type,
                    'data' => json_encode(['message' => $message]),
                    'notifiable_type' => Meeting::class,
                    'notifiable_id' => $meeting->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                Notification::where('notifiable_type', Meeting::class)
                    ->where('notifiable_id', $meeting->id)
                    ->where('recipient_id', $userId)
                    ->update([
                        'data' => json_encode(['message' => $message]),
                        'sender_id' => auth()->id(),
                        'recipient_read_at' => null,
                        'updated_at' => now(),
                    ]);
            }
        }

        if (!empty($notificationsData)) {
            Notification::insert($notificationsData);
        }

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
