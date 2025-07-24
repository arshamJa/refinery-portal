<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\TaskUser;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MyTasks extends Component
{
    use WithPagination,WithoutUrlPagination;

    public ?string $search = '';
    public $statusFilter = null;

    // Triggered when search value changes
    public function updatedSearch()
    {
        // Sanitize and reset pagination
        $this->resetPage();
        $this->search = trim(strip_tags($this->search));

        // Validate the search input
        $this->validate([
            'search' => 'nullable|string|max:100',
        ]);
    }


    public $selectedTaskUser = null;

    public function view($id)
    {
        $this->selectedTaskUser = TaskUser::with(['task.meeting'])->find($id);
        $this->dispatch('crud-modal', name:'view-task-details');
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();

        // Define the regex pattern for Persian date format (YYYY/MM/DD)
        $persianDatePattern = '/^\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])$/';

        // Check if the search matches the Persian date pattern
        if (preg_match($persianDatePattern, $this->search)) {
            // Sanitize the search value if necessary (but no strip_tags here)
            $this->search = trim($this->search);
        } else {
            $this->search = trim(strip_tags($this->search));
        }

        // Validate the search input
        $this->validate([
            'search' => 'nullable|string|max:100',
        ]);

    }
    public function render()
    {
        return view('livewire.my-tasks');
    }

    #[Computed]
    public function taskUsers()
    {
        return TaskUser::with([
            'user:id',
            'task:id,meeting_id,body',
            'task.meeting:id,title'
        ])
            ->where('user_id', auth()->id())

            // Combined search for meeting title OR task time_out
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('task.meeting', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
                    })->orWhereHas('task', function ($q) {
                        $q->where('time_out', 'like', '%' . $this->search . '%');
                    });
                });
            })

            // Apply status filter if selected
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'SENT_TO_SCRIPTORIUM') {
                    $query->where('task_status', \App\Enums\TaskStatus::SENT_TO_SCRIPTORIUM->value);
                } elseif ($this->statusFilter === 'PENDING') {
                    $query->where('task_status', \App\Enums\TaskStatus::PENDING->value);
                }
            })
            ->select('id', 'time_out','task_id', 'user_id', 'sent_date', 'task_status', 'body_task', 'request_task')
            ->paginate(5);
    }


    #[Computed]
    public function unreadReceivedCount()
    {
        return Notification::where('recipient_id', auth()->id())
            ->whereNull('recipient_read_at')
            ->count();
    }
}
