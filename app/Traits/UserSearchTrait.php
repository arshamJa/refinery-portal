<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait UserSearchTrait
{
    public function applyUserSearch(Request $request, $query)
    {
        if ($request->filled('full_name') ||
            $request->filled('n_code') ||
            $request->filled('position') ||
            $request->filled('p_code') ||
            $request->filled('department_name') ||
            $request->filled('role')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('full_name')) {
                    $q->where('full_name', 'like', '%'.$request->input('full_name').'%');
                }
                if ($request->filled('n_code')) {
                    $q->orWhere('n_code', 'like', '%'.$request->input('n_code').'%');
                }
                if ($request->filled('position')) {
                    $q->orWhere('position', 'like', '%'.$request->input('position').'%');
                }
            });
            $query->where(function ($q) use ($request) {
                if ($request->filled('p_code')) {
                    $q->whereHas('user', function ($pCode) use ($request) {
                        $pCode->where('p_code', 'like', '%'.$request->input('p_code').'%');
                    });
                }
                if ($request->filled('department_name')) {
                    $q->orWhereHas('department', function ($department) use ($request) {
                        $department->where('department_name', 'like', '%'.$request->input('department_name').'%');
                    });
                }
            });
            if ($request->filled('role')) {
                $query->whereHas('user.roles', function ($roleQuery) use ($request) {
                    $roleQuery->where('role_id', $request->input('role'));
                });
            }
        }
        return $query;
    }
}
