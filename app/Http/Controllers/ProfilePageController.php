<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileInformationRequest;
use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class ProfilePageController extends Controller
{
    public function index()
    {
        Gate::authorize('profile-page');
        $users = User::with([
            'user_info:id,department_id,user_id,full_name,work_phone,house_phone,phone,n_code,position',
            'permissions'
        ])->find(auth()->id());
        return view('profile-page.index' , [
            'users' => $users,
        ]);
    }

    public function updateProfileInformation(UpdateProfileInformationRequest $request)
    {
        Gate::authorize('profile-page');
        $validated = $request->validated();

        $user = User::find(auth()->user()->id);
        $user->p_code = $validated['p_code'];
        $user->save();

        $userInfos = UserInfo::find($user->id);
        $userInfos->full_name = $validated['full_name'];
        $userInfos->work_phone = $validated['work_phone'];
        $userInfos->house_phone = $validated['house_phone'];
        $userInfos->phone = $validated['phone'];
        $userInfos->n_code = $validated['n_code'];
        $userInfos->save();

        return to_route('profile')->with('status','اطلاعات ذخیره شد');
    }

    public function updateProfilePhoto(Request $request)
    {
        Gate::authorize('profile-page');
        $validated = $request->validate([
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:1024']
        ]);
        // Only update if a file was uploaded
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            DB::table('users')->where('id', auth()->id())->update([
                'profile_photo_path' => $path
            ]);
            return to_route('profile')->with('status','عکس ذخیره شد');
        }
        return to_route('profile')->with('status', 'فایلی برای آپلود انتخاب نشد');
    }

    public function deleteProfilePhoto()
    {
        Gate::authorize('profile-page');
        $profile_path = public_path('storage/'.\auth()->user()->profile_photo_path);
        if (file_exists($profile_path)){
            unlink($profile_path);
        }
        DB::table('users')->where('id',auth()->id())->update([
            'profile_photo_path' => null
        ]);
        return to_route('profile')->with('status','عکس حذف شد');
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        Gate::authorize('profile-page');
        $validated = $request->validated();
        if (!Hash::check($validated['current_password'],auth()->user()->password)){
            throw ValidationException::withMessages([
                'current_password' => 'رمز فعلی شما اشتباه است'
            ]);
        }else{
            Auth::user()->update([
                'password' => $validated['password'],
            ]);
            return to_route('profile')->with('status','رمز جدید ثبت شد');
        }

    }


}
