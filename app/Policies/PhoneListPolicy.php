<?php

namespace App\Policies;

use App\Models\PhoneList;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PhoneListPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator_phones';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator_phones';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator_phones';
    }
}
