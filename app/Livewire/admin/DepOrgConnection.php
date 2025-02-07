<?php

namespace App\Livewire\admin;

use App\Models\Department;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DepOrgConnection extends Component
{
    public function render()
    {
        return view('livewire.admin.dep-org-connection');
    }


    #[Computed]
    public function departments()
    {
        return Department::with('organizations')->get();
    }


}
