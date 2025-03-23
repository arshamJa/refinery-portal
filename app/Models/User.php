<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable ,SoftDeletes, HasRoles;
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

    public function operators(): HasMany
    {
        return $this->hasMany(Operator::class)->chaperone();
    }
    public function otp(): HasOne
    {
        return $this->hasOne(VerificationCode::class)->chaperone();
    }

//    public function departments(): BelongsToMany
//    {
//        return $this->belongsToMany(Department::class);
//    }
    public function organizations():BelongsToMany
    {
        return $this->belongsToMany(Organization::class);
    }
    public function user_info() : HasOne
    {
        return $this->hasOne(UserInfo::class)->chaperone();
    }
    public function full_name()
    {
        return $this->user_info()->where('user_id',auth()->user()->id)->value('full_name');
    }
    public function profilePhoto()
    {
        return url('storage/'.$this->profile_photo_path);
    }
//    public function meetings():BelongsToMany
//    {
//        return $this->belongsToMany(Meeting::class);
//    }
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class)->chaperone();
    }
    public function meetingUsers():HasMany
    {
        return $this->hasMany(MeetingUser::class)->chaperone();
    }
}
