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
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

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
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    public function assignRole(Role $role): self
    {
        $this->roles()->syncWithoutDetaching($role);
        return $this;
    }
    public function removeRole(Role $role): self
    {
        $this->roles()->detach($role);
        return $this;
    }
    public function syncRoles(array $roles): self
    {
        $this->roles()->sync($roles);
        return $this;
    }
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }
    public function hasPermissionTo(Permission $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermissionTo($permission)) {
                return true;
            }
        }
        return false;
    }
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
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
