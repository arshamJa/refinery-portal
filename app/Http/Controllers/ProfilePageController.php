<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileInformationRequest;
use App\Models\Department;
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

        $department_id = \auth()->user()->user_info->department_id;
        $userinfo = UserInfo::where('user_id',\auth()->user()->id)->value('department_id');
        if ($userinfo){
            $department = Department::find($department_id)->value('department_name');
        }else{
            $department = 'دپارتمان وجود ندارد';
        }

        $users = User::with('user_info')->find(auth()->user()->id);


        return view('profile-page.index' , [
            'users' => $users,
            'department' => $department
        ]);
    }

    public function updateProfileInformation(UpdateProfileInformationRequest $request)
    {
        Gate::authorize('update-profile-page');
        $request->validated();

        $user = User::find(auth()->user()->id);
        $user->p_code = $request->p_code;
        $user->save();

        $userInfos = UserInfo::find($user->id);
        $userInfos->full_name = $request->full_name;
        $userInfos->work_phone = $request->work_phone;
        $userInfos->house_phone = $request->house_phone;
        $userInfos->phone = $request->phone;
        $userInfos->n_code = $request->n_code;
        $userInfos->save();

        return redirect()->signedRoute('profile')->with('status','اطلاعات ذخیره شد');
    }
    public function updateProfilePhoto(Request $request)
    {
        Gate::authorize('update-profile-page');
        $validated = $request->validate([
            'photo' => ['nullable','mimes:jpg,png,jpeg,webp','max:1024']
        ]);
        $path = $request->file('photo')->store('profiles','public');
        DB::table('users')->where('id',\auth()->user()->id)->update([
            'profile_photo_path' => $path
        ]);
        return redirect()->signedRoute('profile')->with('status','عکس ذخیره شد');

    }
    public function deleteProfilePhoto(Request $request)
    {
        Gate::authorize('update-profile-page');
        $profile_path = public_path('storage/'.\auth()->user()->profile_photo_path);
        if (file_exists($profile_path)){
            unlink($profile_path);
        }
        DB::table('users')->where('id',\auth()->user()->id)->update([
            'profile_photo_path' => null
        ]);
        return redirect()->signedRoute('profile')->with('status','عکس حذف شد');
    }

    /**
     * @throws ValidationException
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        Gate::authorize('update-profile-page');
        $validated = $request->validated();
        if (!Hash::check($request->current_password,\auth()->user()->password)){
            throw ValidationException::withMessages([
                'current_password' => 'رمز فعلی شما اشتباه است'
            ]);
        }else{
            Auth::user()->update([
                'password' => Hash::make($validated['password']),
            ]);
            return redirect()->signedRoute('profile')->with('status','رمز جدید ثبت شد');
        }

    }


}
