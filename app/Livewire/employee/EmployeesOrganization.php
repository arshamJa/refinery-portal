<?php

namespace App\Livewire\employee;

use App\Models\Organization;
use App\Traits\MeetingsTasks;
use App\Traits\Organizations;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
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
    public ?string $search ='';
    #[Computed]
    public function organizationUsers()
    {
        return DB::table('organization_user')->where('user_id',auth()->user()->id)->get(['id','organization_id','user_id']);
    }
    #[Computed]
    public function organizations()
    {
        return Organization::where('organization_name','like', '%'.$this->search.'%')->get(['id','organization_name','url','image']);
    }
}
