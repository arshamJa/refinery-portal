<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\UserInfo;
use Illuminate\Http\Request;

class MeetingReportTableController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $startDate = trim($request->input('start_date'));
        $endDate = trim($request->input('end_date'));
        $statusFilter = $request->input('statusFilter');
        $meetings = Meeting::select([
                'id', 'title', 'scriptorium', 'boss', 'date', 'time', 'end_time', 'status',
            ])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('scriptorium', 'like', "%{$search}%")
                        ->orWhere('boss', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhere('time', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter !== null && $statusFilter !== '', function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->when(!empty($startDate), function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            })
            ->when(!empty($endDate), function ($query) use ($endDate) {
                $query->where('date', '<=', $endDate);
            })
            ->paginate(5);

        return view('reportsTable.meeting-report-table', [
            'meetings' => $meetings
        ]);
    }

    public function show(Meeting $meeting)
    {
        $bossInfo = UserInfo::where('full_name', $meeting->boss)->first();
        $guests = is_string($meeting->guest) ? json_decode($meeting->guest, true) : (array) $meeting->guest;
        $innerGuests = $meeting->meetingUsers->where('is_guest', true);
        $participants = $meeting->meetingUsers->where('is_guest', false);

        return view('reportsTable.meeting-details', [
            'meeting' => $meeting,
            'bossInfo'=> $bossInfo,
            'guests' => $guests,
            'innerGuests' => $innerGuests,
            'participants' => $participants
        ]);
    }

    public function taskTable()
    {
        $userInfos = UserInfo::with(['user:id', 'department:id,department_name'])
            ->where('full_name', '!=', 'Arsham Jamali')
            ->select('id','user_id','department_id','full_name','position')
            ->paginate(5);

        return view('reportsTable.task-report-table',['userInfos' => $userInfos]);
    }

}
