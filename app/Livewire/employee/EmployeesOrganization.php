<?php

namespace App\Livewire\employee;

use App\Traits\MeetingsTasks;
use App\Traits\Organizations;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeesOrganization extends Component
{
    use WithPagination, WithoutUrlPagination, Organizations, MeetingsTasks;

    public function render()
    {
        return view('livewire.employee.employees-organization');
    }
}
