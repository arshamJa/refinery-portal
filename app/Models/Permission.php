<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    /**
     * Assign the permission to a user.
     *
     * @param User $user
     * @return self
     */
    public function assignToUser(User $user): self
    {
        if (! $this->users()->where('users.id', $user->id)->exists()) {
            $this->users()->attach($user);
        }
        return $this;
    }
    /**
     * Revoke the permission from a user.
     *
     * @param User $user
     * @return self
     */
    public function removeFromUser(User $user): self
    {
        $this->users()->detach($user);
        return $this;
    }
    /**
     * Sync the users associated with the permission.
     *
     * @param array $users
     * @return self
     */
    public function syncUsers(array $users): self
    {
        $this->users()->sync($users);
        return $this;
    }
    /**
     * Check if the permission is assigned to a user.
     *
     * @param User $user
     * @return bool
     */
    public function isAssignedToUser(User $user): bool
    {
        return $this->users()->where('users.id', $user->id)->exists();
    }
}
