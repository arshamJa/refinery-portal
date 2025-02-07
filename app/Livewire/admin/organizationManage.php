<?php

namespace App\Livewire\admin;

use App\Models\Department;
use App\Models\DepartmentUser;
use App\Models\DepartmentUserOrganization;
use App\Models\Organization;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class organizationManage extends Component
{
    use WithPagination,WithoutUrlPagination;

    public $search = '';

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.organization');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        return User::with('user_info:id,department_id,user_id,full_name')
            ->whereRelation('user_info','full_name', 'like','%'.$this->search.'%')
            ->paginate(5);
    }
    #[Computed]
    public function departments()
    {
        return DB::table('departments')->get(['id','department_name']);
    }
    #[Computed]
    public function organizations()
    {
        return Organization::with('department')->get(['id','department_id','organization_name']);
    }
    #[Computed]
    public function organizationUsers()
    {
        return DB::table('organization_user')->get(['organization_id','user_id']);
    }


}
