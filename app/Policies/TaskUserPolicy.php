<?php

namespace App\Policies;

use App\Enums\TaskStatus;
use App\Models\TaskUser;
use App\Models\User;

class TaskUserPolicy
{
    // Helper method to get today's Jalali date
    private function getTodayDate(): string
    {
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        return sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
    }
    // Helper method to check if the task is not empty for `body_task` and `sent_date`
    private function areFieldsNotEmpty(TaskUser $taskUser): bool
    {
        return isset($taskUser->sent_date) && isset($taskUser->body_task) &&
            !empty(trim($taskUser->sent_date)) && !empty(trim($taskUser->body_task));
    }
    // Helper method to check if the task is empty for `body_task` and `time_out`
    private function areFieldsEmpty(TaskUser $taskUser): bool
    {
        return isset($taskUser->task->time_out) && isset($taskUser->body_task) &&
            empty(trim($taskUser->body_task)) && empty(trim($taskUser->task->time_out));
    }


    public function acceptOrDeny(User $user, TaskUser $taskUser): bool
    {
        return $user->id === $taskUser->user_id &&
            $taskUser->task_status === TaskStatus::PENDING;
    }

    public function scriptoriumCanEdit(User $user, TaskUser $taskUser): bool
    {
        $todayDate = $this->getTodayDate();
        // Check if user is the scriptorium
        $isScriptorium = $user->user_info->full_name === $taskUser->task->meeting->scriptorium;

        // Check if the task's time_out has passed
        $isAfterTimeOut = $todayDate >= $taskUser->task->time_out;

        return $isScriptorium &&
            trim($taskUser->body_task) === '' &&
            !$taskUser->sent_date &&
            !$isAfterTimeOut;
    }

    public function writeTask(User $user, TaskUser $taskUser): bool
    {
        $todayDate = $this->getTodayDate();
        $areFieldsEmpty = $this->areFieldsEmpty($taskUser);


        // Return true if all conditions are met
        return $user->id === $taskUser->user_id &&
            $taskUser->task_status === TaskStatus::ACCEPTED &&
            $todayDate <= $taskUser->task->time_out &&
            $areFieldsEmpty;
    }

    public function updateTask(User $user, TaskUser $taskUser): bool
    {
        $todayDate = $this->getTodayDate();
        $areFieldsNotEmpty = $this->areFieldsNotEmpty($taskUser);

        // Return true if all conditions are met
        return $user->id === $taskUser->user_id &&
            $taskUser->task_status === TaskStatus::ACCEPTED &&
            $todayDate <= $taskUser->task->time_out &&
            $areFieldsNotEmpty;
    }

    public function view(User $user, TaskUser $taskUser): bool
    {
        return $user->user_info->full_name === $taskUser->task->meeting->scriptorium ||
            $user->id === $taskUser->user_id;
    }

}
