<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\UserInfo;

class ParticipantsTaskController extends Controller
{
    public function index($meeting_id, $user_id)
    {
        $userInfo = UserInfo::with(['user:id'])
            ->select('id', 'user_id', 'position', 'department_id','full_name')
            ->findOrFail($user_id);

        $meeting = Meeting::with([
            'tasks.taskUsers' => function ($q) use ($userInfo) {
                $q->where('user_id', $userInfo->user_id)
                    ->with(['user.user_info:id,user_id,full_name', 'taskUserFiles']);
            },
        ])->findOrFail($meeting_id);
        $bossInfo = UserInfo::where('full_name', $meeting->boss)->first();

        return view('reports.participant-task', [
            'userInfo' => $userInfo,
            'meeting' => $meeting,
            'bossInfo'=> $bossInfo,
        ]);
    }
}
