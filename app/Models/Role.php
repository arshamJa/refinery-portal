<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Many-to-many relationship with Permission
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    // Many-to-many relationship with User
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // Helper Functions
    public function assignPermission(Permission $permission): void
    {
        $this->permissions()->attach($permission);
    }
    public function removePermission(Permission $permission): void
    {
        $this->permissions()->detach($permission);
    }
    public function assignUser(User $user)
    {
        $this->users()->attach($user);
    }
    public function removeUser(User $user)
    {
        $this->users()->detach($user);
    }
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }
}
