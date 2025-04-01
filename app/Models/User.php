<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable ,SoftDeletes;
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
    public function hasRole(string $role): bool
    {
        if (!$this->relationLoaded('roles')) {
            $this->load('roles'); // Eager load if not already loaded
        }
        return $this->roles->contains('name', $role);
    }
    public function hasPermissionTo($permission): bool
    {
        // Eager load roles before checking permissions
        if (!$this->relationLoaded('roles')) {
            $this->load('roles.permissions'); // Eager load roles and their permissions
        }
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
            if ($permission === null) {
                return false;
            }
        }
        foreach ($this->roles as $role) {
            if ($role->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }
    public function assignPermission(Permission $permission)
    {
        $this->permissions()->attach($permission);
    }
    public function removePermission(Permission $permission)
    {
        $this->permissions()->detach($permission);
    }
    public function getAllPermissions(): BelongsToMany
    {
        return $this->permissions()->with('roles.permissions');
    }


    public function otp(): HasOne
    {
        return $this->hasOne(VerificationCode::class)->chaperone();
    }
    public function organizations():BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }
    public function user_info() : HasOne
    {
        return $this->hasOne(UserInfo::class)->chaperone();
    }
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class)->chaperone();
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
}
