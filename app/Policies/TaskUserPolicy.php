<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;

class TaskUserPolicy
{
    // Helper method to get today's Jalali date
//    private function getTodayDate(): string
//    {
//        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
//        return sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
//    }
    // Helper method to check if the task is not empty for `body_task` and `sent_date`
//    private function areFieldsNotEmpty(Task $task): bool
//    {
//        return !empty(trim($task->sent_date)) &&
//            !empty(trim($task->body_task));
//    }
    // Helper method to check if the task is empty for `body_task` and `time_out`
//    private function areFieldsEmpty(Task $task): bool
//    {
//        return empty(trim($task->body_task)) &&
//            empty(trim($task->sent_date));
//    }


    public function scriptoriumTask(User $user, TaskUser $taskUser): bool
    {
        $meeting = $taskUser->task->meeting;
        // Check if the user has the SCRIPTORIUM role
        $isScriptorium = $user->hasPermissionTo(UserPermission::SCRIPTORIUM_PERMISSIONS->value);
        // Check if the user is the assigned scriptorium based on meeting.scriptorium_id
        $isAssignedScriptorium = $user->id === $meeting->scriptorium_id;
        // Check if the task has not been filled in yet
        $isTaskEmpty = trim($taskUser->task->body_task) === '';
        return $isScriptorium && $isAssignedScriptorium && $isTaskEmpty;
    }

    public function canViewDeniedTasks(User $user, TaskUser $taskUser): bool
    {
        $meeting = $taskUser->task->meeting;
        // Check if user is the scriptorium
        $isScriptorium = $user->id === $meeting->scriptorium_id;
        // Check if user is the boss
        $isBoss = $user->id === $meeting->boss_id;
        return $isScriptorium || $isBoss;
    }

    public function participantTask(User $user, TaskUser $taskUser): bool
    {
//        $todayDate = $this->getTodayDate();
//        $areFieldsEmpty = $this->areFieldsEmpty($task);
        // Return true if all conditions are met
        return $user->id === $taskUser->user_id;
//            &&
//            $todayDate <= $task->time_out &&
//            $areFieldsEmpty;
    }


    public function view(User $user, Task $task): bool
    {
        return $user->user_info->full_name === $task->meeting->scriptorium ||
            $user->id === $task->user_id;
    }

}
