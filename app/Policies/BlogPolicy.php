<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BlogPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator_news';
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator_news';
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'operator_news';
    }
}
