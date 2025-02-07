<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\User;
use App\Models\UserInfo;
use App\Rules\farsi_chs;
use App\Rules\NationalCodeRule;
use App\Rules\PhoneNumberRule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;
use Livewire\WithFileUploads;

class ProfilePage extends Component
{
    use WithFileUploads;

    public string $full_name = '';
    public string $p_code = '';
    public string $n_code = '';
    public string $work_phone = '';
    public string $house_phone = '';
    public string $phone = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $photo;
    public string $role = '';
    public string $department;
    public string $position = '';


    public function render()
    {
        return view('livewire.profile-page');
    }
    public function mount(): void
    {
        $department_id = \auth()->user()->user_info->department_id;
        $userinfo = UserInfo::where('user_id',\auth()->user()->id)->value('department_id');
        if ($userinfo){
            $department = Department::find($department_id)->value('department_name');
        }else{
            $department = 'دپارتمان وجود ندارد';
        }
        $this->role = Auth::user()->role;
        $this->p_code = Auth::user()->p_code;
        $this->full_name = Auth::user()->user_info->full_name;
        $this->department = $department;
        $this->position = Auth::user()->user_info->position;
        $this->work_phone = Auth::user()->user_info->work_phone;
        $this->house_phone = Auth::user()->user_info->house_phone;
        $this->phone = Auth::user()->user_info->phone;
        $this->n_code = Auth::user()->user_info->n_code;
    }

    /**
     * @throws AuthorizationException
     */
    public function updateProfileInformation(): void
    {
        $this->authorize('update-profile-page');
        $user = User::find(\auth()->user()->id);
        $validated = $this->validate([
            'full_name' => ['bail','required', 'string', 'max:255', new farsi_chs()],
            'n_code' => ['bail','required', 'numeric','digits:10', new NationalCodeRule()],
            'p_code' => ['bail','required', 'numeric', 'digits:6'],
            'phone' => ['bail','required', 'numeric','digits:11','starts_with:09', new PhoneNumberRule()],
            'house_phone' => ['bail','required', 'numeric'],
            'work_phone' => ['bail','required', 'numeric'],
        ]);
        $user->p_code = $this->p_code;
        $user->save();

        $userInfos = UserInfo::find($user->id);
        $userInfos->full_name = $this->full_name;
        $userInfos->work_phone = $this->work_phone;
        $userInfos->house_phone = $this->house_phone;
        $userInfos->phone = $this->phone;
        $userInfos->position = $this->position;
        $userInfos->n_code = $this->n_code;
        $userInfos->save();

        $this->dispatch('profile-updated');
    }

    /**
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function updatePassword(): void
    {
        $this->authorize('update-profile-page');
        try {
            $validated = $this->validate([
                'current_password' => ['bail','required', 'string', 'current_password'],
                'password' => ['bail','required', 'string', 'confirmed',Rules\Password::defaults()],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password','password_confirmation');
            throw $e;
        }
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->dispatch('password-updated');
        $this->redirectRoute('profile');
    }

    /**
     * @throws AuthorizationException
     */
    public function updateProfilePhoto()
    {
        $this->authorize('update-profile-page');
        $validated = $this->validate([
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
        ]);
        $path = $this->photo->store('profiles','public');
        DB::table('users')->where('id',\auth()->user()->id)->update([
            'profile_photo_path' => $path
        ]);
        $this->dispatch('profilePhoto-updated');
        $this->redirectRoute('profile');
    }

    /**
     * @throws AuthorizationException
     */
    public function deleteProfilePhoto()
    {
        $this->authorize('update-profile-page');
        $profile_path = public_path('storage/'.\auth()->user()->profile_photo_path);
        if (file_exists($profile_path)){
            unlink($profile_path);
        }
        DB::table('users')->where('id',\auth()->user()->id)->update([
            'profile_photo_path' => null
        ]);
        $this->dispatch('profilePhoto-deleted');
        $this->redirectRoute('profile');
    }
}
