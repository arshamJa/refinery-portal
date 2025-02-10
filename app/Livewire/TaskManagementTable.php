<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TaskManagementTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        return view('livewire.task-management-table');
    }

    #[Computed]
    public function tasks()
    {
        $user = User::find(auth()->id());
        return Task::with('meeting', 'user')->whereBelongsTo($user)->paginate(5);
    }

    public function download($taskId)
    {
        $files = DB::table('task_user')
            ->where('task_id', '=', $taskId)
            ->value('file');
        return response()->download(public_path('storage/'.$files));
    }
}
