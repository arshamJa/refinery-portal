<?php

namespace App\Livewire\admin;

use App\Models\UserInfo;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class EmployeeAccess extends Component
{
    use WithPagination,WithFileUploads, WithoutUrlPagination;
    public ?string $search = '';
    public function render()
    {
        return view('livewire.admin.employee-access');
    }
    #[Computed]
    public function userInfos()
    {
        return UserInfo::with('user:id,role')
            ->where('full_name', 'like', '%'.$this->search.'%')
            ->orWhereRelation('user','role','like','%'.$this->search.'%')
            ->paginate(5);
    }

    /**
     * @throws AuthorizationException
     */
    public function blogActive($blogId)
    {
        $userInfo = UserInfo::find($blogId);
        $userInfo->is_blog_allowed = !$userInfo->is_blog_allowed;
        $userInfo->save();
        $this->redirectRoute('employeeAccess');
    }


    public function createMeeting($blogId)
    {
        $userInfo = UserInfo::find($blogId);
        $userInfo->create_meeting = !$userInfo->create_meeting;
        $userInfo->save();
        $this->redirectRoute('employeeAccess');
    }
    /**
     * @throws AuthorizationException
     */
    public function chatActive($chatId)
    {
        $userInfo = UserInfo::find($chatId);
        $userInfo->is_chat_allowed = !$userInfo->is_chat_allowed;
        $userInfo->save();
        $this->redirectRoute('employeeAccess');
    }
    /**
     * @throws AuthorizationException
     */
    public function dictionaryActive($dictionaryId)
    {
        $userInfo = UserInfo::find($dictionaryId);
        $userInfo->is_dictionary_allowed = !$userInfo->is_dictionary_allowed;
        $userInfo->save();
        $this->redirectRoute('employeeAccess');
    }

    /**
     * @throws AuthorizationException
     */
    public function phoneActive($phoneId)
    {
        $userInfo = UserInfo::find($phoneId);
        $userInfo->is_phoneList_allowed = !$userInfo->is_phoneList_allowed;
        $userInfo->save();
        $this->redirectRoute('employeeAccess');
    }
}
