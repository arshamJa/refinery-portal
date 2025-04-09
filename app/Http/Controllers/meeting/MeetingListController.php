<?php

namespace App\Http\Controllers\meeting;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\Task;
use Illuminate\Http\Request;

class MeetingListController extends Controller
{
    public function index(Request $request)
    {

        $userFullName = auth()->user()->user_info->full_name;

        $query = Meeting::with([
            'meetingUsers' => function ($query) {
                $query->select('meeting_id', 'user_id', 'is_present');
            },
            'meetingUsers.user' => function ($query) {
                $query->select('id'); // Only need the user ID
            },
            'meetingUsers.user.user_info' => function ($query) {
                $query->select('user_id', 'full_name'); // Only need user ID and full name
            },
            'tasks' => function ($query) {
                $query->whereNotNull('request_task')->select('meeting_id', 'request_task');
            }
        ])
            ->where('scriptorium', $userFullName)
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled']);
        $originalMeetingsCount = $query->count(); // Count before any filtering

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('unit_organization', 'like', '%' . $search . '%')
                    ->orWhere('scriptorium', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%')
                    ->orWhere('time', 'like', '%' . $search . '%')
                    ->orWhere('is_cancelled', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('is_cancelled')) {
            $isCancelledFilter = $request->input('is_cancelled');
            if (in_array($isCancelledFilter, ['1', '-1', '0'])) {
                $query->where('is_cancelled', $isCancelledFilter);
            }
        }
        $meetings = $query->paginate(5);

        $allUsersHaveTasks = []; // Initialize the array outside the loop
        foreach ($meetings as $meeting) {
            $userIds = $meeting->meetingUsers->pluck('user_id')->toArray(); // Get user IDs directly from relationship
            if (empty($userIds)) {
                $allUsersHaveTasks[$meeting->id] = false;
            } else {
                $taskCounts = Task::where('meeting_id', $meeting->id)
                    ->whereIn('user_id', $userIds)
                    ->groupBy('user_id')
                    ->pluck('user_id')
                    ->toArray();
                $allUsersHaveTasks[$meeting->id] = count($taskCounts) === count($userIds);
            }
        }

        $filteredMeetingsCount = $meetings->total(); // Count after filtering

        return view('meeting.meetings-list',[
            'meetings'=> $meetings ,
            'allUsersHaveTasks' => $allUsersHaveTasks ,
            'originalMeetingsCount' => $originalMeetingsCount,
            'filteredMeetingsCount' => $filteredMeetingsCount
            ]);
    }

}
