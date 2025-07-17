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
//    public function permissions(): BelongsToMany
//    {
//        return $this->belongsToMany(Permission::class);
//    }

    // Many-to-many relationship with User
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function assignUser(User $user)
    {
        $this->users()->attach($user);
    }
    public function removeUser(User $user)
    {
        $this->users()->detach($user);
    }
//    public function assignPermission(Permission $permission)
//    {
//        $this->permissions()->attach($permission);
//    }
    // Helper Functions
//    public function syncPermissions(array $permissions): self
//    {
//        // Ensure the relationship is loaded to avoid N+1 issues
//        if (! $this->relationLoaded('permissions')) {
//            $this->load('permissions');
//        }
//        // Sync permission IDs
//        $this->permissions()->sync($permissions);
//        return $this;
//    }
//    public function removePermission(Permission $permission): void
//    {
//        $this->permissions()->detach($permission);
//    }

//    public function hasPermission(string $permissionName): bool
//    {
//        return $this->permissions()->where('name', $permissionName)->exists();
//    }
}
