<?php

namespace App\Livewire\admin;

use App\Models\Department;
use App\Rules\farsi_chs;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
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
    /**
     * @throws AuthorizationException
     */
    public function createNewDepartment()
    {
//        $this->authorize('create-department-organization');
        $this->validate([
            'department' => ['bail','required','min:5','max:50','unique:departments,department_name', new farsi_chs()]
        ]);
        Department::create([
            'department_name' => $this->department
        ]);
        $this->close();
        session()->flash('status', 'دپارتمان جدید با موفقیت ثبت شد');
    }

    /**
     * @throws AuthorizationException
     */
    public function updateDep($id)
    {
//        $this->authorize('update-department-organization',$id);
        $this->validate([
            'department' => ['bail','required','min:5','max:50', new farsi_chs()]
        ]);
        Department::where('id', $id)->update(['department_name' => $this->department]);
        $this->close();
        session()->flash('status', 'دپارتمان با موفقیت آپدیت شد');
    }
    /**
     * @throws AuthorizationException
     */
    public function delete($id)
    {
//        $this->authorize('delete-department-organization',$id);
        $department = Department::find($id);
        $department->delete();
        $this->close();
        session()->flash('status', 'دپارتمان با موفقیت حذف شد');
    }
    public function close()
    {
        $this->dispatch('close-modal');
        $this->redirectRoute('departments.index');
    }
}
