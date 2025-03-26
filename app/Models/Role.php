<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function givePermissionTo(Permission $permission): self
    {
        if (! $this->permissions()->where('permissions.id', $permission->id)->exists()) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    public function revokePermissionTo(Permission $permission): self
    {
        $this->permissions()->detach($permission);

        return $this;
    }

    public function syncPermissions(array $permissions): self
    {
        $this->permissions()->sync($permissions);

        return $this;
    }

    public function hasPermissionTo(Permission $permission): bool
    {
        return $this->permissions()->where('permissions.id', $permission->id)->exists();
    }
}
