<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingListController extends Controller
{
    public function index()
    {
        $meetings =  Meeting::with('meetingUsers')
//            ->where('title', 'like', '%'.$this->search.'%')
            ->where('scriptorium',auth()->user()->user_info->full_name)
            ->select(['id','title','unit_organization','scriptorium','location','date','time','reminder','is_cancelled'])
            ->paginate(5);
        return view('meetings-list',['meetings'=>$meetings]);
    }
}
