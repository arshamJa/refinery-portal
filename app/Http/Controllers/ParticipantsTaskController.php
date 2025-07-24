<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\TaskUser;
use App\Models\UserInfo;

class ParticipantsTaskController extends Controller
{
    public function index(string $taskUser_id)
    {
        $taskUser = TaskUser::with('task:id,meeting_id')->findOrFail($taskUser_id);

        $userInfo = UserInfo::with('user:id')
            ->where('user_id', $taskUser->user_id)
            ->select('id', 'user_id', 'position', 'department_id', 'full_name')
            ->firstOrFail();

        $meeting = Meeting::with([
            'tasks.taskUsers' => function ($q) use ($userInfo) {
                $q->where('user_id', $userInfo->user_id)
                    ->with(['user.user_info:id,user_id,full_name', 'taskUserFiles']);
            },
            'boss.user_info',
            'scriptorium.user_info',
        ])->findOrFail($taskUser->task->meeting_id);

        return view('reports.participant-task', [
            'userInfo' => $userInfo,
            'meeting' => $meeting,
            'bossInfo' => $meeting->boss->user_info ?? null,
        ]);
    }

}
