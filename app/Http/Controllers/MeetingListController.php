<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingListController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::with('meetingUsers')
            ->where('scriptorium', auth()->user()->user_info->full_name)
            ->select(['id', 'title', 'unit_organization', 'scriptorium', 'location', 'date', 'time', 'reminder', 'is_cancelled']);

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
            if ($isCancelledFilter === '1' || $isCancelledFilter === '-1' || $isCancelledFilter === '0') {
                $query->where('is_cancelled', $isCancelledFilter);
            }
        }
        $meetings = $query->paginate(5);
        return view('meetings-list',['meetings'=>$meetings]);
    }
}
