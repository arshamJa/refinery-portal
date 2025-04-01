<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserInfo;
use App\UserRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhoneListPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
//        return $user->hasAnyRole([ UserRole::SUPER_ADMIN->value , UserRole::ADMIN->value, UserRole::OPERATOR->value]);
//        return $user->hasAnyRole(['ادمین', 'کاربر']);
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
//        return $user->role === 'admin' || $user->role === 'operator_phones';
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserInfo $userInfo): bool
    {
//        return $user->can('ویرایش تلفن');
    }
}
