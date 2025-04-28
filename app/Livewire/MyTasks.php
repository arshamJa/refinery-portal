<?php

namespace App\Livewire;

use App\Models\Meeting;
use App\Models\Task;
use App\Models\TaskUser;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MyTasks extends Component
{
    use WithPagination,WithoutUrlPagination;


    public function render()
    {
        return view('livewire.my-tasks');
    }

    #[Computed]
    public function taskUsers()
    {
        return TaskUser::with([
            'task.meeting', // Eager load task, and inside task, also meeting
            'user'          // If you still need the user relationship
        ])
            ->where('user_id', auth()->id())
            ->paginate(5);
    }
}
