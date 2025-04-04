<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserInfo;
use App\UserRole;

class PhoneListPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return !$user->hasRole(UserRole::USER->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return !$user->hasRole(UserRole::USER->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasRole(UserRole::SUPER_ADMIN->value) || $user->hasRole(UserRole::ADMIN->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserInfo $userInfo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserInfo $userInfo): bool
    {
        return false;
    }
}
