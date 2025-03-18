<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;

class ScriptoriumReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::with(['meetingUsers' => function ($query) {
            $query->select('meeting_id', 'user_id'); // Select only needed columns from MeetingUser
        }, 'meetingUsers.user' => function ($query) {
            $query->select('id'); // Select only needed columns from User
        }, 'meetingUsers.user.user_info' => function ($query) {
            $query->select('user_id', 'full_name'); // Select only needed columns from UserInfo
        }])
            ->where('scriptorium', auth()->user()->user_info->full_name)
            ->where('is_cancelled', '=' , '-1')
            ->select(['id', 'title', 'scriptorium', 'location', 'date', 'time']);
        $originalMeetingsCount = $query->count(); // Count before any filtering

        // Date range filter
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('date', [$request->input('start_date'), $request->input('end_date')]);
        }
//        if ($request->filled('start_date') && $request->filled('end_date')) {
//            $startDate = $request->input('start_date');
//            $endDate = $request->input('end_date');
//            $query->where('date', '>=', $startDate)
//                ->where('date', '<=', $endDate);
//        }
        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('scriptorium', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhere('date', 'like', '%' . $search . '%');
            });
        }
//        if (auth()->check() && auth()->user()->user_info) {
//            $query->where('scriptorium', '=', auth()->user()->user_info->full_name);
//        }
//        $query->where('is_cancelled', '=', '-1');
        $meetings = $query->paginate(5);
        $filteredMeetingsCount = $meetings->total(); // Count after filtering

        return view('reports.scriptorium-report', [
            'meetings' => $meetings,
            'originalMeetingsCount' => $originalMeetingsCount,
            'filteredMeetingsCount' => $filteredMeetingsCount
            ]);
    }
}
