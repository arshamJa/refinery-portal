<?php

namespace App\Livewire\admin;

use App\Models\Department;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class DepartmentTable extends Component
{
    use WithPagination, WithoutUrlPagination;
    public ?string $department;
    public $departmentId;
    public $isUpdate = false;
    public ?string $search = '';

    public function filterDepartments()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.department-table');
    }

    #[Computed]
    public function departments()
    {
        return Department::where('department_name', 'like', '%'.$this->search.'%')->paginate(5);
    }
    public function openModalImport()
    {
        $this->dispatch('crud-modal', name: 'import');
    }
    public function openModalCreate()
    {
        $this->dispatch('crud-modal', name: 'create');
    }
    public function openModalEdit($id)
    {
        $departmentName = Department::findOrFail($id);
        $this->department = $departmentName->department_name;
        $this->departmentId = $id;
        $this->dispatch('crud-modal', name: 'update');
    }
    public function openModalDelete($department_id)
    {
        $this->department = Department::where('id',$department_id)->value('department_name');
        $this->departmentId = $department_id;
        $this->dispatch('crud-modal', name: 'delete');
    }

    public function createNewDepartment()
    {
        $validated = Validator::make(
            ['department' => $this->department],
            [
                'department' => ['bail', 'required', 'min:5', 'max:255', 'unique:departments,department_name']
            ],
            ['department.required' => 'نام دپارتمان اجباری است.', 'department.min' => 'نام دپارتمان باید حداقل 5 کاراکتر باشد.',
                'department.max' => 'نام دپارتمان نباید بیشتر از 255 کاراکتر باشد.', 'department.unique' => 'این دپارتمان قبلا ثبت شده است.',
            ]
        );
        Department::create([
            'department_name' => $validated['department'],
        ]);
        $this->close();
        session()->flash('status', 'دپارتمان جدید با موفقیت ثبت شد');
    }

    /**
     * @throws AuthorizationException
     */
    public function updateDep($id)
    {
        $validated = $this->validate(
            [
                'department' => ['bail', 'required', 'min:5', 'max:255', 'unique:departments,department_name'],
            ],
            [
                'department.required' => 'نام دپارتمان اجباری است.',
                'department.min' => 'نام دپارتمان باید حداقل 5 کاراکتر باشد.',
                'department.max' => 'نام دپارتمان نباید بیشتر از 255 کاراکتر باشد.',
                'department.unique' => 'این دپارتمان قبلا ثبت شده است.',
            ]
        );
        Department::where('id', $id)->update(['department_name' => $validated['department']]);
        $this->close();
        session()->flash('status', 'دپارتمان با موفقیت آپدیت شد');
    }
    public function close()
    {
        $this->dispatch('close-modal');
        $this->redirectRoute('departments.index');
    }
}
