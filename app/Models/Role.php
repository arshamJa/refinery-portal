<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//
//class Role extends Model
//{
//    use HasFactory;
//
//    protected $fillable = ['name'];
//
//    /**
//     * The permissions that belong to the role.
//     */
//    public function permissions(): BelongsToMany
//    {
//        return $this->belongsToMany(Permission::class);
//    }
//
//    /**
//     * The users that belong to the role.
//     */
//    public function users(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class);
//    }
//
//    /**
//     * Assign a permission to the role.
//     *
//     * @param  Permission  $permission
//     * @return void
//     */
//    public function assignPermission(Permission $permission)
//    {
//        $this->permissions()->attach($permission);
//    }
//
//    /**
//     * Remove a permission from the role.
//     *
//     * @param  Permission  $permission
//     * @return void
//     */
//    public function removePermission(Permission $permission)
//    {
//        $this->permissions()->detach($permission);
//    }
//
//    /**
//     * Assign a user to the role.
//     *
//     * @param  User  $user
//     * @return void
//     */
//    public function assignUser(User $user)
//    {
//        $this->users()->attach($user);
//    }
//
//    /**
//     * Remove a user from the role.
//     *
//     * @param  User  $user
//     * @return void
//     */
//    public function removeUser(User $user)
//    {
//        $this->users()->detach($user);
//    }
//
//    /**
//     * Check if the role has a specific permission.
//     *
//     * @param  string  $permissionName
//     * @return bool
//     */
//    public function hasPermission(string $permissionName): bool
//    {
//        return $this->permissions()->where('name', $permissionName)->exists();
//    }
//}
