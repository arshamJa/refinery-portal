<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewEmployeeRequest;
use App\Http\Requests\StoreNewUserRequest;
use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class NewUserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create-user');
        return view('newUser.create' , [
            'departments' => Department::all()
            ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewUserRequest $request)
    {
        Gate::authorize('create-user');
        $meeting = (bool) $request->create_meeting;
        $phoneList = (bool) $request->phoneList;
        $blog = (bool) $request->blog;
        $chat = (bool) $request->chat;
        $dictionary = (bool) $request->dictionary;
        $request->validated();
        $newUser = User::create([
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'p_code' => $request->p_code,
        ]);
        $departments = Department::find($request->departmentId);
        UserInfo::create([
            'user_id' => $newUser->id,
            'department_id' => $request->departmentId,
            'full_name' => $request->full_name,
            'work_phone' => $request->work_phone,
            'house_phone' => $request->house_phone,
            'phone' => $request->phone,
            'n_code' => $request->n_code,
            'position' => $request->position,
            'create_meeting' => $meeting,
            'is_phoneList_allowed' => $phoneList,
            'is_blog_allowed' => $blog,
            'is_chat_allowed' => $chat,
            'is_dictionary_allowed' => $dictionary,
        ]);
        $organizations = Organization::where('department_id',$departments->id)->get();
        foreach ($organizations as $organization){
            $organization->users()->attach($newUser->id);
        }
        return to_route('newUser.index')->with('status','کاربر جدید ساخته شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       Gate::authorize('view-user',$id);
       $userInfo = UserInfo::find($id);
       $users = User::with('organizations:id,organization_name')->where('id',$userInfo->user_id)->get();
       return view('newUser.show',[
           'userInfo'=> $userInfo,
           'users' => $users
       ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('update-user',$id);
        $userInfo = UserInfo::with(['user:id,role,p_code','department:id,department_name'])->find($id);
        $departments = Department::get(['id','department_name']);
        return view('newUser.edit',['userInfo' => $userInfo , 'departments' => $departments]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreNewUserRequest $request, string $id)
    {
        Gate::authorize('update-user',$id);
        $phoneList = (bool) $request->phoneList;
        $blog = (bool) $request->blog;
        $chat = (bool) $request->chat;
        $dictionary = (bool) $request->dictionary;
        $request->validated();

        $userInfo = UserInfo::find($id);

        $user = User::find($userInfo->user_id);
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->p_code = $request->p_code;
        $user->save();


        $userInfo->user_id = $user->id;
        $userInfo->department_id = $request->departmentId;
        $userInfo->full_name = $request->full_name;
        $userInfo->work_phone = $request->work_phone;
        $userInfo->house_phone = $request->house_phone;
        $userInfo->phone = $request->phone;
        $userInfo->n_code = $request->n_code;
        $userInfo->position = $request->position;
        $userInfo->is_phoneList_allowed = $phoneList;
        $userInfo->is_blog_allowed = $blog;
        $userInfo->is_chat_allowed = $chat;
        $userInfo->is_dictionary_allowed = $dictionary;

        $userInfo->save();


        $departments = Department::find($request->departmentId);
        $organizations = Organization::where('department_id',$departments->id)->get();
        foreach ($organizations as $organization){
            if (DB::table('organization_user')
                ->where('organization_id',$organization->id)
                ->where('user_id',$user->id)
                ->exists())
            {
                $organization->users()->updateExistingPivot($user->id, [
                    'updated_at' => now(),
                ]);
            }else{
                $organization->users()->attach($user->id);
            }
        }

        return to_route('newUser.index')->with('status','کاربر با موفقیت بروز شد');
    }
}
