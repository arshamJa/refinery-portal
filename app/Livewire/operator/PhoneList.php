<?php

namespace App\Livewire\operator;

use App\Models\Units;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PhoneList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public array $infos = ['id', 'user_id','full_name', 'department_id', 'work_phone', 'house_phone', 'phone'];
    public array $filtered_roles = ['admin', 'operator_phones', 'employee'];

    public ?string $search = '';
    public string $user_id = '';
    public string $work_phone = '';
    public string $house_phone = '';
    public string $phone = '';

    #[Locked]
    public $userInfoId = '';

    public function render()
    {
        return view('livewire.operator.phone-list', ['infos' => $this->infos, 'user_id' => $this->user_id]);
    }

    #[Computed]
    public function userInfos()
    {
       if (auth()->user()->role === 'admin') {
            if ($this->filtered_roles){
                $users = User::whereIn('role', $this->filtered_roles)->get();
                $users_names = [];
                foreach ($users as $user){
                    $users_names[] = $user->user_info->full_name;
                }
                $userInfo = UserInfo::with('user')
                    ->select($this->infos)
                    ->wherein('full_name', $users_names);
            }else{
                $userInfo = null;
            }

       }elseif (auth()->user()->role === 'operator_phones') {

            //get the operators and employees
            if ($this->filtered_roles){
                $users = User::whereNot('role', 'admin')->wherein('role', $this->filtered_roles)->get();
            }else{
                $users = User::whereNot('role', 'admin')->get();
            }
            //get the operators' names from operators
            $users_names = [];
            foreach ($users as $user){
                $users_names[] = $user->user_info->full_name;
            }

            //get only the operators and employees from user_infos' table using the operators_names variable
            $userInfo = UserInfo::with('user:id,role','department:id,department_name')->select($this->infos)
                ->wherein('full_name', $users_names);

        } elseif (auth()->user()->role === 'employee') {

            //get the employees
            $employees = User::where('role', 'employee')->get();

            //get the employees' names from employees
            $employees_names = [];
            foreach ($employees as $employee){
                $employees_names[] = $employee->user_info->full_name;
            }

            //get only the employees from user_infos' table using the employees_names variable
            $userInfo = UserInfo::with('user:id,role','department:id,department_name')->select($this->infos)
                ->wherein('full_name', $employees_names);
        }

        if ($userInfo != null){
            if ($this->search === '') {
                return $userInfo->paginate(5);
            } else {
                return $userInfo->where('full_name', 'like', '%'.$this->search.'%')
                    ->orWhere('work_phone', 'like', '%'.$this->search.'%')
                    ->orWhere('house_phone', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%')
                    ->paginate(5);
            }
        }
    }
    public function filter_roles($role)
    {
        if (in_array($role, $this->filtered_roles)) {
            $key = array_search($role, $this->filtered_roles);
            unset($this->filtered_roles[$key]);
        } else {
            $this->filtered_roles[] = $role;
        }
    }

    public function setUserId($user_id)
    {
        $this->removeUserId();
        return $this->user_id = $user_id;
    }
    #[On('removeUserId')]
    public function removeUserId()
    {
        return $this->user_id = '';
    }
    #[On('user-created')]
    public function refresh_users()
    {
        return $this->users();
    }

    /**
     * @throws AuthorizationException
     */
    public function openModalEdit($id)
    {
        $this->authorize('update-phone-list');
        $info = UserInfo::find($id);
        $this->house_phone = $info->house_phone;
        $this->work_phone = $info->work_phone;
        $this->phone = $info->phone;
        $this->userInfoId = $id;
        $this->dispatch('crud-modal', name: 'update');
    }
    /**
     * @throws AuthorizationException
     */
    public function updateInfos($id)
    {
        $this->authorize('update-phone-list');
        $this->validate([
            'work_phone'  => 'required|numeric',
            'house_phone'  => 'required|numeric',
            'phone' => 'required|numeric|digits:11',
        ]);
        UserInfo::where('id',$id)->update([
            'house_phone' => $this->house_phone,
            'work_phone' => $this->work_phone,
            'phone' => $this->phone,
        ]);
        $this->close();
        session()->flash('status', 'اطلاعات با موفقیت آپدیت شد');
    }

    public function close()
    {
        $this->userInfoId = '';
        $this->dispatch('close-modal');
        $this->resetValidation(['newPhone','newHousePhone','newWorkPhone']);
    }
}
