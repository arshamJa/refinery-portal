<?php

namespace App\Policies;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    // Helper method to get today's Jalali date
    private function getTodayDate(): string
    {
        list($ja_year, $ja_month, $ja_day) = explode('/', gregorian_to_jalali(now()->year, now()->month, now()->day, '/'));
        return sprintf("%04d/%02d/%02d", $ja_year, $ja_month, $ja_day);
    }
    // Helper method to check if the task is not empty for `body_task` and `sent_date`
    private function areFieldsNotEmpty(Task $task): bool
    {
        return !empty(trim($task->sent_date)) &&
            !empty(trim($task->body_task));
    }
    // Helper method to check if the task is empty for `body_task` and `time_out`
    private function areFieldsEmpty(Task $task): bool
    {
        return empty(trim($task->body_task)) &&
            empty(trim($task->sent_date));
    }
    public function acceptOrDenyByParticipant(User $user, Task $task): bool
    {
        return $user->id === $task->user_id &&
            $task->task_status === TaskStatus::PENDING;
    }

    public function scriptoriumCanEdit(User $user, Task $task): bool
    {
        $todayDate = $this->getTodayDate();
        // Check if user is the scriptorium
        $isScriptorium = $user->user_info->full_name === $task->meeting->scriptorium;

        // Check if the task's time_out has passed
        $isAfterTimeOut = $todayDate >= $task->time_out;

        return $isScriptorium &&
            trim($task->body_task) === '' &&
            !$task->sent_date &&
            !$isAfterTimeOut;
    }

    public function participantCanWriteTask(User $user, Task $task): bool
    {
        $todayDate = $this->getTodayDate();
        $areFieldsEmpty = $this->areFieldsEmpty($task);
        // Return true if all conditions are met
        return $user->id === $task->user_id &&
            $task->task_status === TaskStatus::ACCEPTED &&
            $todayDate <= $task->time_out &&
            $areFieldsEmpty;
    }

    public function participantCanUpdateTask(User $user, Task $task): bool
    {
//        $todayDate = $this->getTodayDate();
        $areFieldsNotEmpty = $this->areFieldsNotEmpty($task);

//         Return true if all conditions are met
        return
            $user->id === $task->user_id &&
            $task->task_status === TaskStatus::IS_COMPLETED
            &&
//            $todayDate < $task->time_out &&
            $areFieldsNotEmpty;
    }

    public function view(User $user, Task $task): bool
    {
        return $user->user_info->full_name === $task->meeting->scriptorium ||
            $user->id === $task->user_id;
    }

}
