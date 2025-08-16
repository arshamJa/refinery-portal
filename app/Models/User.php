<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable ,SoftDeletes;
    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->p_code === 'Samael') {
                throw new \Exception("Super admin cannot be deleted.");
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'password',
        'p_code',
        'profile_photo_path'
    ];
    protected $casts = [
        'role' => UserRole::class,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function taskUsers(): HasMany
    {
        return $this->hasMany(TaskUser::class);
    }


    public function getTranslatedRole(): string
    {
        return match (true) {
            $this->hasRole(UserRole::SUPER_ADMIN->value) => __('Samael'),
            $this->hasRole(UserRole::ADMIN->value) => __('ادمین'),
            $this->hasRole(UserRole::OPERATOR->value) => __('اپراتور'),
            $this->hasRole(UserRole::USER->value) => __('کاربر'),
            default => __('نامشخص'),
        };
    }


    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    public function assignRole(Role $role): self
        {
            // Eager load roles before syncing
            if (! $this->relationLoaded('roles')) {
                $this->load('roles');
            }
            $this->roles()->syncWithoutDetaching($role);
            return $this;
        }
    public function removeRole(Role $role): self
    {
        // Eager load roles before detaching
        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }
        $this->roles()->detach($role);
        return $this;
    }
    public function syncRoles(array $roles): self
    {
        // Eager load roles before syncing
        if (! $this->relationLoaded('roles')) {
            $this->load('roles');
        }
        $this->roles()->sync($roles);
        return $this;
    }
    public function syncPermissions(array $permissions): self
    {
        if (! $this->relationLoaded('permissions')) {
            $this->load('permissions');
        }
        $this->permissions()->sync($permissions);
        return $this;
    }
    public function hasRole(string $role): bool
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles'); // Eager load if not already loaded
        }
        return $this->roles->contains('name', $role);
    }
    public function hasAnyRoles(array $roles): bool
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles');
        }
        return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }
//    public function hasPermissionTo($permission): bool
//    {
//        // If permission is passed as a string, get Permission model
//        if (is_string($permission)) {
//            $permission = Permission::where('name', $permission)->first();
//            if ($permission === null) {
//                return false;
//            }
//        }
//        // Check direct permissions assigned to user
//        if ($this->permissions->contains('id', $permission->id)) {
//            return true;
//        }
//        // Eager load roles and their permissions if not already loaded
//        if (!$this->relationLoaded('roles')) {
//            $this->load('roles.permissions');
//        }
//        // Check permissions assigned via roles
//        foreach ($this->roles as $role) {
//            if ($role->hasPermissionTo($permission)) {
//                return true;
//            }
//        }
//        return false;
//    }
    public function hasPermissionTo($permission): bool
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
            if ($permission === null) {
                return false;
            }
        }
        if (! $this->relationLoaded('permissions')) {
            $this->load('permissions');
        }
        return $this->permissions->contains('id', $permission->id);
    }
    // use this for checking which user has a specific permission
//    function userHasPermission($permission): bool
//    {
//        $user = Auth::user()->load('permissions');
//        // Check if the authenticated user has the specified permission
//        return $user->permissions->contains('name', $permission);
//    }
    public function assignPermission(Permission $permission)
    {
        $this->permissions()->attach($permission);
    }
    public function removePermission(Permission $permission)
    {
        $this->permissions()->detach($permission);
    }
//    public function getAllPermissions(): BelongsToMany
//    {
//        return $this->permissions()->with('roles.permissions');
//    }
    public function getAllPermissions(): BelongsToMany
    {
        return $this->permissions();
    }

    public function organizations():BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user', 'user_id', 'organization_id');
    }
    public function user_info() : HasOne
    {
        return $this->hasOne(UserInfo::class)->chaperone();
    }
    public function meetingUsers():HasMany
    {
        return $this->hasMany(MeetingUser::class)->chaperone();
    }
    public function full_name()
    {
        return $this->user_info()->where('user_id',auth()->user()->id)->value('full_name');
    }
    public function profilePhoto()
    {
        return url('storage/'.$this->profile_photo_path);
    }

//    public function notifications():HasMany
//    {
//        return $this->hasMany(Notification::class, 'recipient_id');
//    }
    public function notifications():MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
