<?php

namespace App\Livewire\admin;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class NewUserTable extends Component
{

    use WithPagination,WithFileUploads, WithoutUrlPagination;
    public ?string $search = '';
    public string $user_name = '';
    #[Locked]
    public $userInfo_id;

    public function render()
    {
        return view('livewire.admin.new-user-table');
    }
    #[Computed]
    public function userInfos()
    {
        return UserInfo::with('user:id,p_code','department:id,department_name')
            ->where('full_name','like','%'.$this->search.'%')
            ->where('position','like','%'.$this->search.'%')
            ->orWhere('n_code','like','%'.$this->search.'%')
            ->orWhereRelation('user','p_code','like','%'.$this->search.'%')
//            ->orWhereRelation('user','role','like','%'.$this->search.'%')
            ->orWhereRelation('department','department_name','like','%'.$this->search.'%')
            ->select(['id','user_id','department_id','full_name','n_code','position'])
            ->paginate(5);
    }
    /**
     * @throws AuthorizationException
     */
    public function openModalDelete($userInfoId)
    {
        $this->authorize('delete-user',$userInfoId);
        $this->user_name = UserInfo::where('id',$userInfoId)->value('full_name');
        $this->userInfo_id = $userInfoId;
        $this->dispatch('crud-modal',name:'delete');
    }

    /**
     * @throws AuthorizationException
     */
    public function delete($user_id)
    {
        $this->authorize('delete-user',$user_id);
        $userInfo = UserInfo::find($user_id);
        $userInfo->delete();
        $user = User::find($userInfo->user_id);
        $user->delete();
        DB::table('organization_user')->where('user_id',$user->id)->delete();
        $this->close();
        session()->flash('status', 'کاربر با موفقیت حذف شد');
    }

    public function close()
    {
        $this->dispatch('close-modal');
        $this->redirectRoute('newUser.index');
    }
}
