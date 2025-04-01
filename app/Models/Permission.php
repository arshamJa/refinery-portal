<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Many-to-many relationship with Role
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Many-to-many relationship with User
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


    public function belongsToRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
    public function belongsToUser(int $userId): bool
    {
        return $this->users()->where('id', $userId)->exists();
    }
}
