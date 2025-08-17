<?php

namespace App\Http\Controllers;

use App\Enums\MeetingStatus;
use App\Enums\TaskStatus;
use App\Enums\UserPermission;
use App\Exports\MeetingsExport;
use App\Exports\TaskUserExport;
use App\Models\Meeting;
use App\Models\TaskUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class MeetingReportTableController extends Controller
{

    public function show(Meeting $meeting)
    {
        Gate::authorize('has-permission', UserPermission::TASK_REPORT_TABLE);
        $meeting->load([
            'boss.user_info',
            'scriptorium.user_info',
            'meetingUsers.user.user_info.department'
        ]);
        $guests = is_string($meeting->guest) ? json_decode($meeting->guest, true) : (array) $meeting->guest;
        $innerGuests = $meeting->meetingUsers->where('is_guest', true);
        $participants = $meeting->meetingUsers->where('is_guest', false);
        return view('reportsTable.meeting-details', [
            'meeting' => $meeting,
            'guests' => $guests,
            'innerGuests' => $innerGuests,
            'participants' => $participants
        ]);
    }


//    private function calculatePastDifference($jalaliDate): ?string
//    {
//        $dateParts = explode('/', $jalaliDate);
//        [$year, $month, $day] = [$dateParts[0] ?? null, $dateParts[1] ?? null, $dateParts[2] ?? null];
//
//        if (!$year || !$month || !$day) {
//            return 'تاریخ نامعتبر';
//        }
//
//        $gregorianDate = Carbon::parse(jalali_to_gregorian($year, $month, $day, '/'));
//        $now = Carbon::now();
//
//        if ($now->lessThanOrEqualTo($gregorianDate)) {
//            return null; // Not in the past
//        }
//
//        $diff = $now->diff($gregorianDate);
//        $text = ($diff->m > 0 ? $diff->m . ' ماه ' : '') . ($diff->d > 0 ? $diff->d . ' روز ' : '');
//
//        return 'گذشته: ' . ($text ?: 'کمتر از یک روز');
//    }
//    private function calculateRemainingDifference($jalaliDate): ?string
//    {
//        $dateParts = explode('/', $jalaliDate);
//        [$year, $month, $day] = [$dateParts[0] ?? null, $dateParts[1] ?? null, $dateParts[2] ?? null];
//
//        if (!$year || !$month || !$day) {
//            return 'تاریخ نامعتبر';
//        }
//
//        $gregorianDate = Carbon::parse(jalali_to_gregorian($year, $month, $day, '/'));
//        $now = Carbon::now();
//
//        if ($now->greaterThanOrEqualTo($gregorianDate)) {
//            return null; // Not remaining
//        }
//
//        $diff = $gregorianDate->diff($now);
//        $text = ($diff->m > 0 ? $diff->m . ' ماه ' : '') . ($diff->d > 0 ? $diff->d . ' روز ' : '');
//
//        return 'باقی مانده: ' . ($text ?: 'امروز');
//    }

}
