<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\UserInfo;
use App\Traits\UserSearchTrait;
use Illuminate\Http\Request;

class UsersTableController extends Controller
{
    use UserSearchTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UserInfo::with('user:id,p_code', 'department:id,department_name', 'user.roles')
            ->where('full_name','!=','Arsham Jamali')
            ->select(['id','user_id','department_id','full_name','n_code','position']);
        if (auth()->user()->hasRole('super-admin')){
            $roles = \App\Models\Role::get(['id','name']); // Fetch all roles for the dropdown
        }else{
            $roles = Role::where('name','!=','super-admin')->get(['id','name']);
        }

        $originalUsersCount = $query->count(); // Count before any filtering

        $query = $this->applyUserSearch($request, $query); // Use the trait

        $users = $query->paginate(5)->appends($request->except('page')); // Preserve all search parameters

        $filteredUsersCount = $users->total();

        return view('users.index',[
           'userInfos' => $users , 'roles' => $roles ,
            'originalUsersCount' => $originalUsersCount, 'filteredUsersCount' => $filteredUsersCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
