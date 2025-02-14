<?php

namespace App\Livewire\admin;
use App\Models\Department;
use App\Models\Meeting;
use App\Models\MeetingUser;
use App\Models\User;
use App\Trait\MeetingsTasks;
use App\Trait\MessageReceived;
use App\Trait\Organizations;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks, MessageReceived;

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
    #[Computed]
    public function users()
    {
        return User::all()->count();
    }
    #[Computed]
    public function departments()
    {
        return Department::all()->count();
    }
}
