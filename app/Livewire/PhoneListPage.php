<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\Organization;
use App\Models\Role;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\Features\SupportLockedProperties\BaseLocked;
use Livewire\WithPagination;

class PhoneListPage extends Component
{
    use WithPagination;

//    public ?string $department;
    public ?string $full_name;
    public ?string $phone;
    public ?string $house_phone;
    public ?string $work_phone;
    public ?string $role = '';

//    public ?string $search = '';
    public ?string $query = '';

    public function search()
    {
        $this->resetPage(); // This is correct
    }

    #[BaseLocked]
    public $editingId;

    public $showAllColumns;

    #[Computed]
    public function userInfos()
    {
        $role = trim($this->role ?? '');
        $searchTerm = '%' . trim($this->query) . '%';

        return UserInfo::with([
            'user.roles:id,name',
            'department:id,department_name',
        ])
            ->select('id', 'user_id', 'department_id', 'full_name', 'work_phone', 'house_phone', 'phone')
            ->whereHas('user.roles', fn ($q) =>
            $q->where('name', '!=', UserRole::SUPER_ADMIN->value)
            )
            ->when($this->query, function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('full_name', 'like', $searchTerm)
                        ->orWhere('phone', 'like', $searchTerm)
                        ->orWhere('house_phone', 'like', $searchTerm)
                        ->orWhere('work_phone', 'like', $searchTerm)
                        ->orWhereHas('department', fn ($d) =>
                        $d->where('department_name', 'like', $searchTerm))
                        ->orWhereHas('user.roles', fn ($r) =>
                        $r->where('name', 'like', $searchTerm));
                });
            })
            ->when($this->role, fn ($q) =>
            $q->whereHas('user.roles', fn ($r) =>
            $r->where('roles.id', $this->role)))
            ->paginate(5);
    }



    public function editUserInfo($id)
    {
        $userInfo = UserInfo::findOrFail($id);
        $this->full_name = $userInfo->full_name;
        $this->phone = $userInfo->phone;
        $this->house_phone = $userInfo->house_phone;
        $this->work_phone = $userInfo->work_phone;
        $this->editingId = $id;
        $this->resetValidation();
        $this->dispatch('crud-modal',name:'update');
    }

    public function updatePhone()
    {
        $this->validate([
            'phone' => ['required', 'numeric', 'digits:11','regex:/^\+?\d{10,15}$/'],
            'house_phone' => ['required', 'numeric'],
            'work_phone' => ['required', 'numeric'],
        ]);

        $phone = preg_replace('/[^\d+]/', '', $this->phone);
        $work_phone = preg_replace('/[^\d+]/', '', $this->work_phone);
        $house_phone = preg_replace('/[^\d+]/', '', $this->house_phone);

        $userInfo = UserInfo::findOrFail($this->editingId);

        $userInfo->update([
            'phone' => $phone,
            'house_phone' => $house_phone,
            'work_phone' => $work_phone,
        ]);
        $this->dispatch('close-modal');
        return to_route('phone-list.index')->with('status','اطلاعات با موفقیت بروز شد');
    }

    public function closeModal(){
        $this->dispatch('close-modal',name:'update');
        $this->reset();
    }


    #[Computed]
    public function showAllColumns()
    {
        $user = auth()->user();
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }

    #[Computed]
    public function roles()
    {
        return Role::where('name', '!=', UserRole::SUPER_ADMIN->value)
            ->select(['id', 'name'])
            ->get();
    }

    #[Computed]
    public function originalUsersCount()
    {
        return UserInfo::whereHas('user.roles', fn($q) => $q->where('name', '!=', UserRole::SUPER_ADMIN->value)
        )->count();
    }

    #[Computed]
    public function filteredUsersCount()
    {
        return $this->userInfos->total();
    }

    public function render()
    {
        return view('livewire.phone-list-page');
    }
}
