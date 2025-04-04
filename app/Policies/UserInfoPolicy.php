<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserInfo;
use App\UserRole;

class UserInfoPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewUserTable(User $user): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }
    public function viewUsers(User $user): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function createNewUser(User $user): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function updateUsers(User $user, UserInfo $userInfo): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserInfo $userInfo): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }
}
