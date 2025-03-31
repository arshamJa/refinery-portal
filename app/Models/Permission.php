<?php
//
//namespace App\Models;
//
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//
//class Permission extends Model
//{
//    use HasFactory;
//
//    protected $fillable = ['name'];
//
//    /**
//     * The roles that belong to the permission.
//     */
//    public function roles(): BelongsToMany
//    {
//        return $this->belongsToMany(Role::class);
//    }
//    /**
//     * The users that belong to the permission.
//     */
//    public function users(): BelongsToMany
//    {
//        return $this->belongsToMany(User::class);
//    }
//    /**
//     * Check if the permission belongs to a specific role.
//     *
//     * @param  string  $roleName
//     * @return bool
//     */
//    public function belongsToRole(string $roleName): bool
//    {
//        return $this->roles()->where('name', $roleName)->exists();
//    }
//    /**
//     * Check if the permission belongs to a specific user.
//     *
//     * @param  int  $userId
//     * @return bool
//     */
//    public function belongsToUser(int $userId): bool
//    {
//        return $this->users()->where('id', $userId)->exists();
//    }
//}
