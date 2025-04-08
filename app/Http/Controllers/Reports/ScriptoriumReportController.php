<?php

namespace App\Http\Controllers\Reports;

use App\Exports\MeetingsExport;
use App\Http\Controllers\Controller;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;



class ScriptoriumReportController extends Controller
{
    public function index(Request $request)
    {
        // Get the base filtered query
        $query = $this->filteredMeetingsQuery($request);

        // Paginate the filtered results
        $meetings = $query->paginate(5);

        // Transform to add `holders` property
        $meetings->getCollection()->transform(function ($meeting) {
            $meeting->holders = $meeting->meetingUsers
                ->map(fn($mu) => optional($mu->user->user_info)->full_name)
                ->filter()
                ->join(', ');
            return $meeting;
        });

        return view('reports.scriptorium-report', [
            'meetings' => $meetings,
        ]);
    }
    protected function filteredMeetingsQuery(Request $request)
    {
        // I used Scope in this function and scopeFunctions are in Meeting.php
        return Meeting::with([
            'meetingUsers:id,meeting_id,user_id',
            'meetingUsers.user:id',
            'meetingUsers.user.user_info:id,user_id,full_name',
        ])
            ->where('scriptorium', auth()->user()->user_info->full_name)
            ->where('is_cancelled', '-1')
            ->when($request->filled(['start_date', 'end_date']), function ($query) use ($request) {
                $query->dateRange($request->start_date, $request->end_date);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->search($request->search);
            })
            ->select(['id', 'title','unit_organization', 'scriptorium',
                    'location', 'date', 'time','unit_held','guest','applicant','position_organization']);
    }
    public function exportExcel(Request $request)
    {
        $meetings = $this->filteredMeetingsQuery($request)
            ->with('meetingUsers.user.user_info')
            ->get();
        return Excel::download(new MeetingsExport($meetings), 'meetings.xlsx');
    }
}
